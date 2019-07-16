<template>
  <span class="wrapper-edit-room-button">
    <img @click="open" class="img-edit-room-button" src="/img/edit.png" />
    <drag-container v-if="show">
      <template #contents="prop">
        <div class="wrapper">
          <div @mousedown="prop.event" class="title">
            <span class="title-text">グループ編集</span>
            <span @click="close" @mousedown.stop class="close-button">×</span>
          </div>
          <label class="wrapper-group-name">
            <span class="text-group-name">ルーム名</span>
          </label>
          <div class="user-list">
            <label v-for="(user, index) in userList" :key="index" :user="user" class="user-item">
              <img
                @error="imgSrcErrorHandler"
                class="user-item-profile-img"
                :src="'storage/images/' + user.id + '.jpg'"
              />
              <span class="user-item-profile-name">{{ user.name }}</span>
              <input
                class="user-item-checkbox"
                type="checkbox"
                :value="user.id"
                v-model="join_users"
              />
            </label>
          </div>
          <div class="actions">
            <span class="update-button">友達を招待</span>
            <span @click="close" class="cancel-button">トーク</span>
          </div>
        </div>
      </template>
    </drag-container>
  </span>
</template>
<script>
import DragContainer from "../DragContainer.vue";
export default {
  components: { DragContainer },
  data() {
    return {
      show: false,
      join_users: [],
      group_name: "",
      default_img_src: "/img/profile.jpg"
    };
  },
  computed: {
    userList() {
      return this.$root.user_list.filter(user => user.id != this.$root.user.id);
    }
  },
  methods: {
    open() {
      this.show = true;
    },
    close() {
      this.show = false;
    },
    create() {
      if (this.group_name.length == 0) {
        console.log("値を入力してください");
        return;
      }
      this.join_users.push(this.$root.user.id);
      this.$root.addRoom(this.join_users, true, this.group_name);
      this.join_users.splice(0);
      this.group_name = "";
    },
    imgSrcErrorHandler(e) {
      e.target.src = this.default_img_src;
    }
  }
};
</script>
<style scoped>
.wrapper-edit-room-button {
  display: block;
}
.img-edit-room-button {
  display: block;

  width: 30px;
  height: 30px;
  cursor: pointer;
  user-select: none;
}
.wrapper {
  display: flex;
  flex-direction: column;
  width: 380px;
  height: 460px;
  border: solid 1px gray;
  background-color: white;
}
.title {
  display: inline-flex;
  padding-left: 10px;
  height: 40px;
  align-items: center;
  color: white;
  cursor: default;
  background-color: darkslategray;
}
.title-text {
  user-select: none;
  flex: 1 1;
}
.close-button {
  cursor: pointer;
  flex: 0 0 40px;
  font-size: 1.8rem;
  font-weight: bold;
  text-align: center;
  color: gray;
  user-select: none;
}
.wrapper-group-name {
  flex: 0 0 40px;
  display: flex;
  align-items: center;
  padding-left: 10px;
  margin: 0;
}
.user-list {
  flex: 1 1;
  display: flex;
  flex-direction: column;
  border-top: solid 1px gray;
  overflow: auto;
}

.user-item {
  display: flex;
  align-items: center;
  height: 60px;
  margin: 0;
  padding: 8px 12px;
  border-bottom: solid 1px silver;
}
.user-item-profile-img {
  border-radius: 50%;
  flex: 0 0 48px;
  width: 48px;
  height: 48px;
  user-select: none;
}
.user-item-profile-name {
  display: inline-block;
  padding-left: 10px;
  flex: 1 1;
  user-select: none;
}
.user-item-checkbox {
  flex: 0 0 24px;
  height: 24px;
  margin: 12px;
  appearance: none;
  border: none;
  border: solid 1px gray;
  border-radius: 50%;
  background-color: transparent;
}
.user-item-checkbox:checked {
  background-color: limegreen;
}
.actions {
  flex: 0 0 70px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-top: solid 1px gray;
}
.update-button {
  display: inline-block;
  width: 100px;
  margin-right: 10px;
  padding: 8px 0;
  text-align: center;
  border: solid 1px silver;
  background-color: limegreen;
  cursor: pointer;
  user-select: none;
}
.cancel-button {
  display: inline-block;
  width: 100px;
  margin-left: 10px;
  padding: 8px 0;
  text-align: center;
  border: solid 1px silver;
  cursor: pointer;
  user-select: none;
}
</style>
