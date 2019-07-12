<template>
  <div class="main">
    <div class="input-group">
      <span class="input-group-btn">
        <button id="personal" class="btn btn-primary btn-lg" @click="change('personal')">
          <span
            id="personal-badge"
            class="badge"
            v-if="isNewMessage('personal')"
          >{{ $root.new_personal_messages.length }}</span>個チャ
        </button>
      </span>
      <span class="input-group-btn">
        <button id="group" class="btn btn-success active" @click="change('group')">
          <span
            id="group-badge"
            class="badge"
            v-if="isNewMessage('group')"
          >{{ $root.new_group_messages.length }}</span>グループ
        </button>
      </span>
      <span class="input-group-btn">
        <button id="group-add" class="btn btn-success active" @click="changeActive()">グループ作成</button>
      </span>
      <div class="create-group">
        <transition>
          <div class="list-items" v-if="isActive">
            <template v-if="existsListItems">
              <template v-for="(value, index) in $root.listItems">
                <div
                  class="list-item"
                  v-if="!is_myId(value.id)"
                  v-bind:key="index"
                  :class="[index == activeItemKey ? 'active' : '' ]"
                  @click="handleClickItem(index, value.id, value.name, value.room_id)"
                >
                  <input type="checkbox" :id="value.id" :value="value.id" v-model="join_users" />
                  <label :for="value.id">{{value.name}}</label>
                </div>
              </template>
              <div>
                <span>GroupName:</span>
                <p style="white-space: pre-line;">{{ group_name }}</p>
                <br />
                <textarea v-model="group_name" placeholder="グループ名を入力してください"></textarea>
              </div>
              <div>
                <button
                  id="group-create"
                  class="btn btn-success active"
                  @click="changeActive()"
                >グループ作成</button>
              </div>
            </template>
            <template v-else>
              <div class="list-item">選択肢がありません</div>
            </template>
          </div>
        </transition>
      </div>
      <div class="dropdown-bg" @click="isActive = false" v-if="isActive"></div>
    </div>
    <div class="user-channel">
      <UserChannel />
    </div>
    <div class="group-channel">
      <GroupChannel />
    </div>
    <div class="user-list-component" v-if="$root.now_room">
      <UserList />
    </div>
  </div>
</template>

<script>
import UserChannel from "./UserChannel";
import GroupChannel from "./GroupChannel";
import UserList from "./UserList";
import talk from "./Talk";

export default {
  components: { UserChannel, GroupChannel, UserList, talk },
  data() {
    return {
      group_name: "",
      join_users: [],
      isActive: false,
      activeItemKey: null
    };
  },
  computed: {
    existsListItems() {
      return true;
    }
  },
  methods: {
    change(id) {
      let personal_btn = document.getElementById("personal");
      let group_btn = document.getElementById("group");
      let user_channel = document.getElementsByClassName("user-channel")[0];
      let group_channel = document.getElementsByClassName("group-channel")[0];

      if (id == "personal") {
        personal_btn.setAttribute("class", "btn btn-primary btn-lg");
        group_btn.setAttribute("class", "btn btn-success active");
        user_channel.setAttribute("style", "display: block;");
        group_channel.setAttribute("style", "display: none;");
      } else if (id == "group") {
        personal_btn.setAttribute("class", "btn btn-success active");
        group_btn.setAttribute("class", "btn btn-primary btn-lg");

        user_channel.setAttribute("style", "display: none;");
        group_channel.setAttribute("style", "display: block;");
      }
    },
    changeActive() {
      this.isActive = !this.isActive;
      if (!this.isActive && this.group_name && this.join_users.length > 0) {
        this.join_users.push(this.$root.user_id);
        let users = [];

        for (let user of this.join_users) {
          users.push(user);
        }

        this.$root.addRoom(users, true, this.group_name);
        this.join_users.splice(0);
        this.group_name = "";
        return;
      }
      console.log("値を入力してください");
    },
    handleClickItem(key, id, name, room_id) {
      if (key == this.activeItemKey) {
        return;
      }

      this.action(key, id, name, room_id);
    },
    action(key, id, name) {
      this.activeItemKey = key;
    },
    is_myId(id) {
      if (id == this.$root.user_id) {
        return true;
      }
    },
    isNewMessage(channel) {
      if (channel == "group") {
        if (this.$root.new_group_messages.length > 0) {
          return true;
        }
      } else {
        if (this.$root.new_personal_messages.length > 0) {
          return true;
        }
      }
      return false;
    }
  }
};
</script>

<style scoped>
.input-group-btn {
  padding-top: 1%;
  padding-right: 10px;
}
.group-channel {
  display : none;
}
</style>