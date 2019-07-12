<template>
  <div class="room-list">
    <room-list-item v-for="room in 4" :key="room"></room-list-item>
  </div>
</template>
<script>
import RoomListItem from "./RoomListItem";
export default {
  components: { RoomListItem }
};
</script>
<style lang="scss" scoped>
.room-list {
  display: flex;
  flex-direction: column;
  overflow: auto;
}
</style>

