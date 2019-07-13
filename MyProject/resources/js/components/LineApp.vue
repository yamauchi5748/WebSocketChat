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
            <label
              v-for="(user, index) in userList"
              :key="index"
              :user="user"
              class="user-list-item"
            >
              <img class="user-list-item-profile-img" :src="image" />
              <span class="user-list-item-profile-name">{{ user.name }}</span>
              <input
                class="user-list-item-checkbox"
                type="checkbox"
                :value="user.id"
                v-model="join_users"
              />
            </label>
          </div>
        </template>
        <template #actions="props">
          <span class="create-button" @click="test()">作成</span>
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
  },
  props: {
    user_id: String
  },
  data() {
    return {
      image:
        "data:image/jpeg;base64,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",
      join_users: []
    };
  },
  mounted() {
    this.$root.user_id = this.user_id;
  },
  computed: {
    userList() {
      return this.$root.user_list;
    }
  },
  methods: {
    test() {
      console.log(this.join_users);
    }
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
.user-list-item {
  display: flex;
  align-items: center;
  height: 60px;
  margin: 0;
  padding: 8px 12px;
  border-bottom: solid 1px silver;
}
.user-list-item-profile-img {
  border-radius: 50%;
  flex: 0 0 48px;
  height: 48px;
  user-select: none;
}
.user-list-item-profile-name {
  display: inline-block;
  padding-left: 10px;
  flex: 1 1;
  user-select: none;
}
.user-list-item-checkbox {
  flex: 0 0 24px;
  height: 24px;
  margin: 12px;
  appearance: none;
  border: none;
  border: solid 1px gray;
  border-radius: 50%;
  background-color: transparent;
}
.user-list-item-checkbox:checked {
  background-color: limegreen;
}
</style>

