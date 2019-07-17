<template>
  <div class="page-chat">
    <div class="left-item">
      <the-room-list-filter class="room-list-filter-position"></the-room-list-filter>
      <the-room-list class="room-list-position"></the-room-list>
      <the-create-room-button></the-create-room-button>
    </div>
    <div class="right-item">
      <the-room-contents></the-room-contents>
    </div>
  </div>
</template>
<script>
import TheRoomListFilter from "./chat/TheRoomListFilter.vue";
import TheRoomList from "./chat/TheRoomList.vue";
import TheCreateRoomButton from "./chat/TheCreateRoomButton.vue";
import TheRoomContents from "./chat/TheRoomContents";
export default {
  components: {
    TheRoomListFilter,
    TheRoomList,
    TheCreateRoomButton,
    TheRoomContents
  }
};
</script>
<style scoped>
.page-chat {
  display: flex;
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
</style>

