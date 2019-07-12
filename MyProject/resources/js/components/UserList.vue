<template>
  <div>
    <span>
      <button id="group-edit" class="btn btn-success active" @click="changeActive()">ユーザ一覧</button>
    </span>
    <transition>
      <div class="user-list" v-if="isActive">
        <template class="edit-box" v-for="(user, index) in userList">
          <div v-bind:key="index">{{ user.name }}</div>
        </template>
      </div>
    </transition>
    <div class="dropdown-bg" @click="isActive = false" v-if="isActive"></div>
  </div>
</template>

<style>
.user-list {
  width: 260px;
  max-height: 200px;
  background-color: #fff;
  border-radius: 2px;
  border: 1px solid #b9bfc9;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
  position: absolute;
  overflow-y: scroll;
  z-index: 3;
  padding: 0.5rem 0;
}
</style>

<script>
export default {
  data() {
    return {
      isActive: false
    };
  },
  computed: {
    userList: function() {
      return this.$root.now_room.users;
    }
  },
  methods: {
    changeActive() {
      this.isActive = !this.isActive;
    }
  }
};
</script>

