<template>
  <div class="room-list">
    <room-list-item v-for="(room, index) in roomList" :key="index" :index="index" :room="room"></room-list-item>
  </div>
</template>
<script>
import RoomListItem from "./RoomListItem";
export default {
  components: { RoomListItem },
  data() {
    return {
      search_key: "",
      activeItemKey: null,
      badge_counter: []
    };
  },
  computed: {
    roomList: function() {
      return this.$root.rooms.filter(room => {
        if (room.is_group) {
          return room.group_name.indexOf(this.$root.search_key) != -1;
        } else {
          return room.users[1].name.indexOf(this.$root.search_key) != -1;
        }
      });
    },
    existsListItems() {
      if (this.roomList.length == 0) {
        return false;
      }
      return true;
    }
  }
};
</script>
<style lang="scss" scoped>
.room-list {
  display: flex;
  flex-direction: column;
  overflow: auto;
}
</style>

