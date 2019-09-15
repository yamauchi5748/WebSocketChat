<template>
  <nav class="global-navigation">
    <router-link class="item" to="/frends">
      <img class="icon" src="/img/icon-frends.png" />
    </router-link>
    <router-link class="item" to="/chat">
      <img class="icon" src="/img/icon-chat.png" />
      <span class="badge" v-show="badge_count > 0">{{ badge_count }}</span>
    </router-link>
    <router-link class="item" to="/timeline">
      <img class="icon" src="/img/icon-timeline.png" />
    </router-link>
    <div>
      <button class="item button" @click="logout">ログアウト</button>
    </div>
  </nav>
</template>

<script>
export default {
  computed: {
    badge_count: function() {
      return (
        this.$root.new_group_messages.length +
        this.$root.new_personal_messages.length
      );
    }
  },
  methods: {
    logout: function(){
      axios.post("/logout")
      .catch(error => {
        location.href = '/';
      });
    }
  }
};
</script>

<style scoped>
.global-navigation {
  display: flex;
  padding: 30px 0;
  flex-direction: column;
  align-items: center;
  background-color: darkslategray;
}
.item {
  position: relative;
  display: flex;
  margin: 20px 0;
  justify-content: center;
  align-items: center;
}
.item .icon {
  display: block;
  height: 40px;
  width: 40px;
}
.item .badge {
  position: absolute;
  top: -8px;
  right: -10px;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 23px;
  width: 23px;
  border-radius: 50%;
  color: white;
  background-color: red;
}
.other {
  margin-top: auto;
}
.button {
  display: block;
  background-color: darkslategray;
  border-style: none;
  color: darkgrey;
  outline: none; 
  font-size: 0.9em;
}
</style>
