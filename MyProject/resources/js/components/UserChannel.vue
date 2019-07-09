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
            <template v-for="(value, index) in $root.listItems">
              <div
                class="list-item"
                v-if="is_myId(value.id, index)"
                v-bind:key="index"
                :class="[index == activeItemKey ? 'active' : '' ]"
                @click="handleClickItem(index, value.id, value.name, value.room_id)"
              >
                <span
                  :id="'badge-' + index"
                  class="badge"
                  v-if="badgeChecker(index)"
                >{{ badge_counter[index] }}</span>
                {{value.name}}
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
</style>

<script>
export default {
  data() {
    return {
      label: "ユーザーを選択して下さい▿",
      isActive: false,
      activeItemKey: null,
      badge_counter: []
    };
  },
  computed: {
    existsListItems() {
      if (this.$root.listItems.length == 0) {
        return false;
      }
      return true;
    }
  },
  methods: {
    badgeChecker(index) {
      let badge = document.getElementById("badge-" + index);
      let counter = 0;
      for (let message of this.$root.new_personal_messages) {
        if (message.room_id == this.$root.listItems[index].room_id) {
          counter++;
        }
      }
      this.badge_counter[index] = counter;
      console.log(this.badge_counter[index]);
      if (counter > 0) {
        return true;
      }
      return false;
    },
    changeActive() {
      this.isActive = !this.isActive;
    },
    handleClickItem(key, id, name, room_id) {
      if (key == this.activeItemKey) {
        return;
      }

      this.isActive = false;
      this.action(key, id, name, room_id);
    },
    action(key, id, name, room_id) {
      this.$root.activeItemKey = key;
      for (let room of this.$root.rooms) {
        if (room.id == room_id) {
          this.$root.now_room = room;
        }
      }
      this.label = name + "▿";
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