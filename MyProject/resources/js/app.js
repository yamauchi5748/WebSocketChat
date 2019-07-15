
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
      user_id: null,
      now_room: null,
      rooms: [],
      new_group_messages: [],
      new_personal_messages: [],
      timeline: [],
      listItems: [],
      activeItemKey: null
    }
  },
  methods: {
    //timelineクリア
    clearTimeline() {
      this.timeline.splice(0);
    },
    //ルームをソート
    sortRoom() {
      for (let index = 1; index < this.rooms.length;) {
        const room = this.rooms[index];
        const forward = this.rooms[index - 1];
        const exist_room_content = room.contents.length == 0 ? false : true;
        const exist_forward_content = forward.contents.length == 0 ? false : true;

        if (exist_room_content) {
          console.log(forward.group_name, forward.created_at, (room.contents[0].created_at > forward.created_at));
        }
        if (exist_room_content && (!exist_forward_content || room.contents[0].created_at > forward.contents[0].created_at) && (room.contents[0].created_at > forward.created_at)) {
          this.rooms[index] = forward;
          this.rooms[index - 1] = room;

          index == 1 ? index++ : index--;
        } else {
          index++
        }
      }
    },
    // テキスト投稿
    postMessage(text) {
      axios.post("/api/message", {
        user_id: this.user_id,
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
    checkAt(room_id) {
      axios.post("/api/check-at", {
        user_id: this.user_id,
        room_id: room_id
      });
    },
    // 投稿されたメッセージがこれで呼ばれる
    recieveMessage(message) {
      // 送信者名を追加
      for (let user of this.listItems) {
        if (user.id == message.sender_id) {
          message.sender_name = user.name;
        }
      }
      // 自ユーザのみ既読表示
      if (message.sender_id == this.user_id) {
        message.already_read = this.now_room.is_group ? 0 : false;
      };

      // 投稿時にルーム内にいるなら投稿を表示し、参加状況を更新する
      if (this.now_room && message.room_id == this.now_room.id) {
        console.log(message)
        this.timeline.push(message);
        this, this.checkAt(this.now_room.id);
      } else {
        // 新しいメッセージを新着ボックスに格納
        console.log(message);
        message.is_group ? this.new_group_messages.push(message) : this.new_personal_messages.push(message)
      }
      // ルームにメッセージを格納
      for (let room of this.rooms) {
        if (room.id == message.room_id) {
          room.contents.unshift(message);
          console.log(room.contents[0].message);
        }

      }
      // ルームをソート
      this.sortRoom();
    },
    //既読処理
    alreadyReadUpdate(target) {
      for (let chat of this.timeline) {
        if (chat.id == target.id) {
          console.log(chat.already_read);
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
          console.log(res.data);
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
      let url = "/api/rooms/" + this.now_room.id + "/messages"
      axios.get(url, {
        room_id: this.now_room.id
      })
        .then(res => {
          let messages = res.data;
          for (let message of messages) {
            console.log("tinpooooo", message);
            // 送信者名を追加
            for (let user of this.listItems) {
              if (user.id == message.sender_id) {
                message.sender_name = user.name;
              }
            }
            this.timeline.push(message);
          }
          console.log(this.timeline)

        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // チャットルームのwebsocketチャンネル確立
    connectChannel(room_id) {
      Echo.join('room.' + room_id)
        .here((users) => {
          console.log("参加しました");
        })
        .joining((user) => {
          console.log('入室', user.name);
        })
        .leaving((user) => {
          console.log('体質', user.name);
        })
        .listen('MessageRecieved', (e) => {
          this.recieveMessage(e.message);
        });
    },
    connectPrivateChannel(room_id) {
      // プライベートチャンネル接続
      Echo.private('user.' + this.user_id + '.room.' + room_id)
        .listen('AlreadyRead', (e) => {
          console.log('tinpo', e.chat);
          this.alreadyReadUpdate(e.chat);
        });
    },
    // ルーム作成
    addRoom(join_users, is_group, group_name) {
      admin = is_group ? this.user_id : null;
      axios.post("/api/rooms", {
        join_users: join_users,
        is_group: is_group,
        group_name: group_name,
        admin: admin
      })
        .then(res => {
          for (let user of this.listItems) {
            if (user.id == join_users[0]) {
              user.room_id = res.data.id
            }

          }
          console.log("addRoom", res.data);
          this.rooms.unshift(res.data);
          this.connectChannel(res.data.id);
          this.connectPrivateChannel(res.data.id);
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
          this.clearTimeline();
        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    },
    // ルーム退出
    exitRoom() {
      axios.delete("api/rooms/" + this.now_room.id + "/users/" + this.user_id)
        .then(res => {
          console.log("退出しました", res.data);
          this.rooms = res.data;
          this.clearTimeline();
        })
        .catch(error => {
          console.log(error)
          console.log('データの取得に失敗しました。')
        });
    }
  },
  mounted() {
    // ユーザー検索
    axios.get("/api/users")
      .then(res => {
        let users = res.data

        for (let user of users) {
          this.listItems.push({
            id: user.id,
            name: user.name,
            room_id: null
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
          this.rooms.push(room);
          console.log("room", room.contents);
          this.connectChannel(room.id);
          this.connectPrivateChannel(room.id);
        }
        this.sortRoom();
        console.log(this.rooms[0]);
      })
      .catch(error => {
        console.log(error)
        console.log('データの取得に失敗しました。')
      });
    // 新着メッセージを検知
    axios.get("/api/new-messages")
      .then(res => {
        console.log(res.data);
        for (let key of Object.keys(res.data)) {
          console.log(res.data[key].is_group);
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

