<template>
  <div>
    <span>
      <button id="group-edit" class="btn btn-primary btn-lg" @click="editActive()">編集</button>
    </span>
    <transition>
      <div class="group-edit-list" v-if="isActive">
        <template class="edit-box" v-for="(value, index) in $root.now_room">
          <div v-bind:key="index">
            <div v-if="index == 'group_name'">
              <span>新しいグループ名</span>
              <textarea v-model="update_group.name" placeholder="新しいグループ名を入力してください"></textarea>
            </div>
            <div v-if="index == 'users'">
              <div>
                <span>ルームメンバー</span>
                <template v-for="(user, index) in update_group.users">
                  <div
                    :id="user.id"
                    v-bind:key="index"
                    @click="removeUser(user, index)"
                  >{{ user.name }}</div>
                </template>
              </div>
              <br />
              <div>
                <span>メンバー招待</span>
                <template v-for="(user, index) in user_list">
                  <div
                    :id="user.id"
                    v-bind:key="index"
                    @click="userAdd(user, index)"
                  >{{ user.name }}</div>
                </template>
              </div>
            </div>
          </div>
        </template>
        <div>
          <div class="group-update-wrapper">
            <button id="group-update" class="btn btn-success active" @click="editSave()">保存</button>
          </div>
          <div>
            <button id="group-delete" class="btn btn-primary btn-lg exit" @click="roomDelete()">ルーム削除</button>
          </div>
        </div>
      </div>
    </transition>
    <div class="dropdown-bg" @click="isActive = false" v-if="isActive"></div>
  </div>
</template>

<style>
.group-edit-list {
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
.group-update-wrapper {
  margin-bottom: 10px;
}
</style>

<script>
export default {
  data() {
    return {
      label : this.props_label,
      isActive: false,
      update_group: {
        id: "id",
        name: "name",
        users: []
      },
      user_list: []
    };
  },
  computed: {
    userList: function() {
      let list = [];
      for (let user of this.$root.listItems) {
        let flag = true;
        for (let target of this.$root.now_room.users) {
          if (user.id == target.id) {
            flag = false;
          }
        }
        flag ? list.push(user) : null;
      }
      return list;
    },
    roomId: function() {
      return this.$root.now_room.id;
    },
    groupName: function() {
      return this.$root.now_room.group_name;
    },
    users: function() {
      return this.$root.now_room.users;
    }
  },
  methods: {
    editActive() {
      this.update_group.id = this.roomId;
      this.update_group.name = this.groupName;
      this.update_group.users = this.users;
      this.user_list = this.userList;
      this.isActive = !this.isActive;
    },
    userAdd(user, index) {
      this.update_group.users.push(user);
      this.user_list = this.userList;
    },
    removeUser(user, index) {
      this.update_group.users.splice(index, 1);
      this.user_list = this.userList;
    },
    editSave() {
      console.log(
        this.update_group.id,
        this.update_group.name,
        this.update_group.users
      );
      this.$root.updateRoomStatus(this.update_group);
      this.isActive = !this.isActive;
    },
    roomDelete(){
      if(confirm("ルームを削除しますか？")){
        this.$root.deleteRoom();
        this.isActive = !this.isActive;
        this.$parent.label = "グループを選択してください▿"
      }else{
        console.log("no");
      }
    }
  }
};
</script>

