<template>
  <span class="wrapper-create-room-button">
    <span @click="open" class="text-create-room-button">+</span>
    <drag-container v-if="show">
      <template #contents="prop">
        <div class="wrapper">
          <div @mousedown="prop.event" class="title">
            <span class="title-text">トークを開始</span>
            <span @click="close" @mousedown.stop class="close-button">×</span>
          </div>
          <label class="wrapper-group-name">
            <span class="text-group-name">グループ名：</span>
            <input
              class="input-group-name"
              type="text"
              v-model="group_name"
              placeholder="グループ名を入力してください"
            />
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
            <span class="create-button" @click="close(), create()">作成</span>
            <span @click="close" class="cancel-button">キャンセル</span>
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
.wrapper-create-room-button {
  display: block;
  position: absolute;
  z-index: 9998;
  bottom: 20px;
  right: 20px;
}
.text-create-room-button {
  display: flex;
  justify-content: center;
  align-items: center;

  width: 60px;
  height: 60px;

  border-radius: 50%;
  background-color: limegreen;
  font-size: 2rem;
  color: white;
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
  display: flex;
  flex: 0 0 40px;
  align-items: center;
  margin: 0;
}
.text-group-name {
  display: inline-block;
}
.input-group-name {
  flex: 1 1;
  border: none;
  outline: none;
}
.user-list {
  flex: 1 1;
  display: flex;
  flex-direction: column;
  border-top: solid 1px gray;
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
</style>
