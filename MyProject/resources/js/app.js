
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './router';

Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('my-chat', require('./components/LineApp.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  data() {
    return {
      user: null,
      now_room: null,
      rooms: [],
      new_group_messages: [],
      new_personal_messages: [],
      user_list: [],
      activeItemKey: null,
      search_key: "",
    }
  },
  methods: {
    //ルームをソート
    sortRoom() {
      for (let index = 1; index < this.rooms.length;) {
        const room = this.rooms[index];
        const forward = this.rooms[index - 1];
        const exist_room_content = room.contents.length == 0 ? false : true;
        const exist_forward_content = forward.contents.length == 0 ? false : true;

        if (exist_room_content && (!exist_forward_content || room.contents[room.contents.length - 1].created_at > forward.contents[forward.contents.length - 1].created_at) && (room.contents[room.contents.length - 1].created_at > forward.created_at)) {
          this.rooms.splice(index, 1, forward);
          this.rooms.splice(index - 1, 1, room);

          index == 1 ? index++ : index--;
        } else {
          index++
        }
      }
    },
    // 年月日取得
    getDate(date) {
      const dt = new Date();
      const tmp1 = date.split("-");
      const tmp2 = tmp1[2].split(" ");

      const year = tmp1[0];
      const month = tmp1[1];
      const day = tmp2[0];
      let result = "";

      if (dt.getFullYear() > year) {
        result += year + '.' + month + '.' + day;
      } else {
        let week;
        switch (dt.getDay()) {
          case 0:
            week = '日'
            break;
          case 1:
            week = '月'
            break;
          case 2:
            week = '火'
            break;
          case 3:
            week = '水'
            break;
          case 4:
            week = '木'
            break;
          case 5:
            week = '金'
            break;
          case 6:
            week = '土'
            break;
        }
        result += month + '.' + day + '(' + week + ')';
      }
      return result;
    },
    // 時間取得
    getTime(date) {
      const tmp = date.split("-")[2].split(" ")[1].split(":");

      const hour = Number(tmp[0]);
      const minute = tmp[1];
      return hour + ":" + minute;
    },
    // 日付代入
    pushDate(room) {
      for (let index = 0; index < room.contents.length; index++) {
        const dt = this.getDate(room.contents[index].created_at);
        if (!room.forward_date || room.forward_date != dt) {
          room.contents.splice(index, 0, {
            message: dt,
            content_type: 'text',
            sender_id: 'system_manager',
            created_at: room.contents[index].created_at
          });
          index++;
          room.forward_date = dt;
        }
      }
    },
    // テキスト投稿
    postMessage(text) {
      axios.post("/api/message", {
        user_id: this.user.id,
        room_id: this.now_room.id,
        text
      });
    },
    // 画像投稿
    postFile(data) {
      console.log(data);
      axios
        .post("/api/rooms/" + this.now_room.id + "/file", data)
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    // ルームに参加すると呼ばれる
    checkAt() {
      axios.post("/api/check-at", {
        user_id: this.user.id,
        room_id: this.now_room.id
      });
    },
    // 投稿されたメッセージがこれで呼ばれる
    recieveMessage(message) {
      // 送信者名を追加
      message.sender_name = this.user_list.filter(user => user.id == message.sender_id)[0].name;

      // 自ユーザのみ既読表示
      if (message.sender_id == this.user.id) {
        message.already_read = this.now_room.is_group ? 0 : false;
      };

      // 投稿時にルーム内にいるなら投稿を表示し、参加状況を更新する
      if (this.now_room && message.room_id == this.now_room.id) {
        this.now_room.contents.push(message);
        this.checkAt(this.now_room.id);
      } else {
        // 新しいメッセージを新着ボックスに格納
        console.log(message);
        message.is_group ? this.new_group_messages.push(message) : this.new_personal_messages.push(message)
        // ルームにメッセージを格納
        for (let room of this.rooms) {
          if (room.id == message.room_id) {
            room.contents.push(message);
          }
        }
      }
      // ルームをソート
      console.log('sort');
      this.sortRoom();
    },
    //既読処理
    alreadyReadUpdate(target) {
      for (let chat of this.now_room.contents) {
        if (chat.id == target.id) {
          chat.already_read = this.now_room.is_group ? (parseInt(chat.already_read) + 1) : true;
        }
      }
    },
    newMessageUpdate() {
      // メッセージを既読したら新着ボックスから削除
      if (this.now_room.is_group) {
        for (let index = 0; index < this.new_group_messages.length; index++) {
          if (this.now_room.id == this.new_group_messages[index].room_id) {
            this.new_group_messages.splice(index, 1);
            index--;
          }
        }
      } else {
        for (let index = 0; index < this.new_personal_messages.length; index++) {
          if (this.now_room.id == this.new_personal_messages[index].room_id) {
            this.new_personal_messages.splice(index, 1);
            index--;
          }
        }
      }
    },
    // グループ情報更新
    updateRoomStatus(room) {
      axios.put('/api/rooms/' + this.now_room.id, room)
        .then(res => {
          for (let room of this.rooms) {
            if (room.id == res.data.id) {
              room = res.data;
              this.now_room = res.data;
            }
          }
        }).catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // チャット取得
    getMessages() {
      let index = 0;
      if (!this.now_room.contents[index]) {
        return;
      }
      console.log(this.now_room.contents[0].created_at)
      let url = "/api/rooms/" + this.now_room.id + "/messages/" +
        this.now_room.contents[0].created_at;
      axios.get(url)
        .then(res => {
          let messages = res.data;
          for (let message of messages) {
            const dt = this.getDate(message.created_at);
            if (this.now_room.forward_date != dt) {
              this.now_room.contents.unshift({
                message: dt,
                content_type: 'text',
                sender_id: 'system_manager',
                created_at: message.created_at
              });
              this.now_room.forward_date = dt;
            } else {
              this.now_room.contents.splice(1, 0, message);
            }
          }
        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // ルーム作成
    addRoom(join_users, is_group, group_name) {
      let users = [];
      for (let user of join_users) {
        users.push(user);
      }
      axios.post("/api/rooms", {
        join_users: users,
        is_group: is_group,
        group_name: group_name,
        admin: is_group ? this.user.id : null
      })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // ルーム削除
    deleteRoom() {
      axios.delete("api/rooms/" + this.now_room.id)
        .then(res => {
          console.log("削除しました");
          this.rooms = res.data;
        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // ルーム退出
    exitRoom() {
      axios.delete("api/rooms/" + this.now_room.id + "/users/" + this.user.id)
        .then(res => {
          console.log("退出しました", res.data);
          for (let index = 0; index < this.rooms.length; index++) {
            if (res.data == this.rooms[index].id) {
              this.rooms.splice(index, 1);
            }
          }
        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    }
  },
  mounted() {
    // プライベートチャンネル接続
    Echo.private('user.' + this.user.id)
      // ルーム作成イベント
      .listen('RoomRecieved', (e) => {
        console.log('roomStore', e.room);
        const room = e.room;

        this.rooms.unshift(room);
      })
      // ルーム更新イベント
      .listen('RoomUpdateRecieved', (e) => {
        console.log(e.room);
        // ルーム置換
        for (let index = 0; index < this.rooms.length; index++) {
          if (this.rooms[index].id == e.room.id) {
            this.rooms.splice(index, 1, e.room);
          }
        }
        this.now_room = e.room;
      })
      //　チャット取得イベント
      .listen('MessageRecieved', (e) => {
        this.recieveMessage(e.message);
      })
      //　既読処理発生イベント
      .listen('AlreadyRead', (e) => {
        this.alreadyReadUpdate(e.chat);
      });
    // ユーザー検索
    axios.get("/api/users")
      .then(res => {
        let users = res.data

        for (let user of users) {
          this.user_list.push({
            id: user.id,
            name: user.name
          });
        }
      })
      .catch(error => {
        console.log(error)
        console.log('データの取得に失敗しました。')
      });
    // ルーム検索
    axios.get("/api/rooms")
      .then(res => {
        for (let room of res.data) {
          room.contents.reverse();
          room.forward_date = null;
          this.pushDate(room);
          this.rooms.push(room);
        }
        this.sortRoom();
      })
      .catch(error => {
        console.log(error)
        console.log('データの取得に失敗しました。')
      });
    // 新着メッセージを検知
    axios.get("/api/new-messages")
      .then(res => {
        for (let key of Object.keys(res.data)) {
          if (res.data[key].is_group) {
            this.new_group_messages.push(res.data[key]);
          } else {
            this.new_personal_messages.push(res.data[key]);
          }
        }
      })
      .catch(error => {
        console.log(error)
        console.log('データの取得に失敗しました。')
      });
  },
  router
});

