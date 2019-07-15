<template>
  <div class="room-list-item" @click="action(room)">
    <img class="user-list-item-profile-img" :src="'storage/images/' + room.users[1].id + '.jpg'" v-if="!room.is_group" />
    <div class="room-list-item-information-left">
      <span class="room-list-item-name" v-if="room.is_group">{{ room.group_name }}</span>
      <span
        class="room-list-item-name"
        v-if="!room.is_group && room.users[1]"
      >{{ room.users[1].name }}</span>
      <span
        class="latest-content"
        v-if="content && content.content_type == 'text'"
      >{{ content.message }}</span>
      <span class="latest-content" v-if="content && content.content_type == 'image'">send an image</span>
      <span class="latest-content" v-if="content && content.content_type == 'video'">send a video</span>
    </div>
    <div class="room-list-item-information-right">
      <span class="update-date" v-if="content">{{ content.created_at }}</span>
      <span
        class="room-list-item-unread"
        :id="'badge-' + index"
        v-if="badgeChecker(index, room.is_group)"
      >{{ badge_counter[index] }}</span>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    room: Object,
    index: Number
  },
  data() {
    return {
      activeItemKey: null,
      badge_counter: []
    };
  },
  computed: {
    content: function() {
      const contents = this.room.contents;
      return contents[contents.length - 1];
    }
  },
  methods: {
    badgeChecker(index, is_group) {
      let badge = document.getElementById("badge-" + index);
      let counter = 0;
      if (is_group) {
        for (let message of this.$root.new_group_messages) {
          if (message.room_id == this.$root.rooms[index].id) {
            counter++;
          }
        }
      } else {
        for (let message of this.$root.new_personal_messages) {
          if (message.room_id == this.$root.rooms[index].id) {
            counter++;
          }
        }
      }
      this.badge_counter[index] = counter;
      if (counter > 0) {
        return true;
      }
      return false;
    },
    action(room) {
      this.$root.now_room = room;
      this.$root.getMessages();
      this.$root.newMessageUpdate();
      this.$root.checkAt();
    }
  }
};
</script>
<style>
.room-list-item {
  width: 100%;
  height: 80px;
  padding: 10px;
  display: flex;
  align-items: stretch;
  user-select: none;
}
.room-list-item:hover {
  background-color: whitesmoke;
}
.room-list-item-information-left {
  flex: 1 1;

  display: flex;
  flex-direction: column;
  align-items: stretch;
}
.room-list-item-name {
  font-weight: bold;
}
.latest-content {
  color: gray;
  width: 7em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.room-list-item-information-right {
  flex: 0 0 100px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
}
.update-date {
  font-size: 0.6rem;
  text-align: right;
  color: gray;
}
.room-list-item-unread {
  align-self: center;

  display: inline-flex;
  margin-top: 7px;
  padding: 0.25rem 0.75rem;
  justify-content: center;
  align-items: center;
  background-color: limegreen;
  font-size: 1rem;
  border-radius: 2rem;
}
.user-list-item-profile-img {
  max-width: 48px;
  border-radius: 50%;
  flex: 0 0 48px;
  height: 48px;
  user-select: none;
}
</style>
