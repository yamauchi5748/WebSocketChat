<template>
  <div class="room-header">
    <span
      class="room-header-name"
      v-if="$root.now_room && $root.now_room.is_group"
    >{{ $root.now_room.group_name }}</span>
    <span
      class="room-header-name"
      v-if="$root.now_room && !$root.now_room.is_group"
    >{{ $root.now_room.users[1].name }}</span>
    <span class="room-header-menu" v-if="$root.now_room && $root.now_room.is_group">
      <img
        class="leave-action"
        src="data:image/png;base64,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"
        @click="roomExit()"
      />
      <drag-container>
        <template #button>
          <img class="room-edit-button" src="/img/edit.png" />
        </template>
        <template #header>グループを編集</template>
        <template #content>
          <div class="room-edit-content">
            <div class="room-edit-name">ルーム名</div>
            <div class="room-edit-user-list">
              <user-list-item v-for="index in 10" :key="index"></user-list-item>
            </div>
          </div>
        </template>
        <template #actions="props">
          <span class="update-button">友達を招待</span>
          <span class="cancel-button">トーク</span>
        </template>
      </drag-container>
    </span>
  </div>
</template>
<script>
import DragContainer from "./DragContainer.vue";
import UserListItem from "./UserListItem.vue";
export default {
  components: { DragContainer, UserListItem },
  data() {
    return {
      show: true
    };
  },
  methods: {
    roomExit() {
      let text = null;
      if (this.$root.user_id == this.$root.now_room.admin) {
        text = "管理者が退出するとルームが削除されますが退出しますか？";
      } else {
        text = "ルームから退出しますか？";
      }
      if (confirm(text)) {
        this.$root.exitRoom();
        this.$root.clearTimeline();
        this.$root.now_room = null;
        this.label = "グループを選択してください▿";
      } else {
        console.log("キャンセル");
      }
    }
  },
};
</script>
<style scoped>
.room-header {
  display: flex;
  padding: 10px;
  flex: 0 0 50px;
  align-items: center;
  background-color: whitesmoke;
}
.room-header-name {
  font-weight: bold;
  flex: 1 1;
  user-select: none;
}
.room-header-menu {
  display: flex;
  flex: 0 0;
}
.room-header-menu img {
  display: inline-block;
  margin: 0 8px;
  width: 30px;
  height: 100%;
}
.room-edit-button {
  cursor: pointer;
}
.room-edit-content {
  display: flex;
  height: 100%;
  flex-direction: column;
}
.room-edit-name {
  flex: 0 0 40px;
  display: flex;
  padding: 3px 5px;
  font-weight: bold;
  font-size: 1.4rem;
  background-color: ghostwhite;
}
.room-edit-user-list {
  flex: 1 1;
  display: block;
  flex-direction: column;
  overflow: auto;
}
.update-button {
  margin-right: 10px;
  width: 80px;
  padding: 8px 0;
  text-align: center;
  border: solid 1px silver;
  background-color: limegreen;
  cursor: pointer;
  user-select: none;
}
.cancel-button {
  margin-left: 10px;
  width: 100px;
  padding: 8px 0;
  text-align: center;
  border: solid 1px silver;
  cursor: pointer;
  user-select: none;
}
</style>
