<template>
  <div>
    <div class="all-wrapper">
      <div class="dropdown-wrapper" @click="changeActive()">
        <div class="dropdown-text">{{ label }}</div>
        <i class="el-icon-caret-bottom"></i>
      </div>
      <transition>
        <div class="list-items" v-if="isActive">
          <template v-if="existsListItems">
            <div class="user-search-box">
              検索:
              <input type="text" v-model="search_key" />
            </div>
            <template v-for="(room, index) in roomList">
              <div
                class="list-item"
                v-if="!room.is_group"
                v-bind:key="index"
                :class="[index == activeItemKey ? 'active' : '' ]"
                @click="handleClickItem(index, room)"
              >
                <div
                  :id="'badge-' + index"
                  class="badge"
                  v-if="badgeChecker(index, room.id)"
                >{{ badge_counter[index] }}</div>
                {{room.users[1].name}}
                <div
                  class="latest-talk-contents"
                  v-if="room.contents[0] && room.contents[0].content_type == 'text'"
                >{{ room.contents[0].message }}</div>
                <div
                  class="latest-talk-contents"
                  v-if="room.contents[0] && room.contents[0].content_type == 'image'"
                >send an image</div>
                <div
                  class="latest-talk-contents"
                  v-if="room.contents[0] && room.contents[0].content_type == 'video'"
                >send a video</div>
              </div>
            </template>
          </template>
          <template v-else>
            <div class="list-item">選択肢がありません</div>
          </template>
        </div>
      </transition>
    </div>
    <div class="dropdown-bg" @click="isActive = false" v-if="isActive"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      label: "ユーザーを選択して下さい▿",
      search_key: "",
      isActive: false,
      activeItemKey: null,
      badge_counter: []
    };
  },
  computed: {
    existsListItems() {
      if (this.$root.rooms.length == 0) {
        return false;
      }
      return true;
    },
    roomList: function() {
      console.log(this.search_key);
      return this.$root.rooms.filter(room => room.users[1].name.indexOf(this.search_key) != -1);
    }
  },
  methods: {
    badgeChecker(index, room_id) {
      let badge = document.getElementById("badge-" + index);
      let counter = 0;
      for (let message of this.$root.new_personal_messages) {
        if (message.room_id == room_id) {
          counter++;
        }
      }
      this.badge_counter[index] = counter;
      if (counter > 0) {
        return true;
      }
      return false;
    },
    changeActive() {
      this.isActive = !this.isActive;
    },
    handleClickItem(key, room) {
      if (key == this.activeItemKey) {
        return;
      }

      this.isActive = false;
      this.action(key, room);
    },
    action(key, room) {
      this.$root.activeItemKey = key;
      this.$root.now_room = room;
      this.label = room.users[1].name + "▿";
      this.$root.clearTimeline();
      this.$root.getMessages();
      this.$root.newMessageUpdate();
      this.$root.checkAt(this.$root.now_room.id);
    },
    existsListItem(index) {
      // データ取得が完了するまで表示しない
      return this.$root.listItems[index].room_id ? true : false;
    },
    is_myId(id, index) {
      if (id != this.$root.user_id && this.existsListItem(index)) {
        return true;
      }
      return false;
    }
  }
};
</script>

<style>
.dropdown-bg {
  width: 100vw;
  height: 100vh;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 2;
}
.all-wrapper {
  position: relative;
}
.dropdown-wrapper {
  padding: 10px;
  padding-left: 20px;
  color: #666666;

  display: flex;
  align-items: center;
}

.dropdown-text {
  background-color: #f3f4f6;
  font-size: 16px;
}
.dropdown-text:hover {
  background-color: #d3f4f6;
  cursor: pointer;
}

i {
  font-size: 10px;
  margin-left: 6px;
}

.list-items {
  width: 260px;
  max-height: 300px;
  background-color: #fff;
  border-radius: 2px;
  border: 1px solid #b9bfc9;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
  position: absolute;
  overflow-y: scroll;
  z-index: 3;
  padding: 0.5rem 0;
}
.list-item {
  clear: both;
  color: #333;
  font-size: 14px;
  line-height: 16px;
  padding: 0.75rem 1rem;

  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;

  position: relative;
}
.list-item:not(.active):hover {
  background-color: #d3f4f6;
  cursor: pointer;
}
.list-item.active {
  color: #fff;
  background-color: #182a4b;
}
.badge {
  margin-top: 0%;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: lawngreen; /*背景色*/
  text-align: center;
  line-height: 15px;
}
.latest-talk-contents {
  color: #b9bfc9;
}
.user-search-box {
  margin-left: 15px;
}
</style>