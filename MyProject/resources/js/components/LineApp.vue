<template>
  <div class="line">
    <div class="left-item">
      <the-room-list-filter class="room-list-filter-position"></the-room-list-filter>
      <the-room-list class="room-list-position"></the-room-list>
      <drag-container>
        <template #button>
          <span class="create-channel-button">+</span>
        </template>
        <template #header>トークを開始</template>
        <template #content>
          <div class="create-channel-content">
            <user-list-item v-for="index in 10" :key="index"></user-list-item>
          </div>
        </template>
        <template #actions="props">
          <span class="create-button">作成</span>
          <span @click="props.close" class="cancel-button">キャンセル</span>
        </template>
      </drag-container>
    </div>
    <div class="right-item">
      <the-room-contents></the-room-contents>
    </div>
  </div>
</template>
<script>
import TheRoomListFilter from "./TheRoomListFilter.vue";
import TheRoomList from "./TheRoomList.vue";

import DragContainer from "./DragContainer.vue";
import UserListItem from "./UserListItem.vue";

import TheRoomContents from "./TheRoomContents";
export default {
  components: {
    TheRoomListFilter,
    TheRoomList,
    TheRoomContents,
    DragContainer,
    UserListItem
  }
};
</script>
<style scoped>
.line {
  display: flex;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  overflow: hidden;
  background-color: white;
}
.left-item {
  position: relative;
  flex: 0 0 360px;
  display: flex;
  flex-direction: column;
}
.room-list-filter-position {
  flex: 0 0 40px;
}
.room-list-position {
  flex: 1 1;
}
.right-item {
  flex: 1 1;
}
.create-channel-button {
  display: flex;
  justify-content: center;
  align-items: center;

  position: absolute;
  z-index: 9998;
  width: 60px;
  height: 60px;
  bottom: 20px;
  right: 20px;

  cursor: pointer;
  border-radius: 50%;
  font-size: 2rem;
  color: white;
  background-color: limegreen;
  user-select: none;
}
.create-channel-content {
  flex: 1 1;
  display: flex;
  flex-direction: column;
  overflow: auto;
}
.create-button {
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

