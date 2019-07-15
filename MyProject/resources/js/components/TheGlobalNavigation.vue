<template>
  <nav class="global-navigation">
    <router-link class="item" to="/frends">
      <img class="icon" src="/img/icon-frends.png" />
    </router-link>
    <router-link class="item" to="/chat">
      <img class="icon" src="/img/icon-chat.png" /><span v-if="2" class="batch">{{2}}</span>
    </router-link>
    <router-link class="item" to="/timeline">
      <img class="icon" src="/img/icon-timeline.png" />
    </router-link>
    <router-link class="item other" to="/other">
      <img class="icon" src="/img/icon-other.png" />
    </router-link>
  </nav>
</template>

<script>
export default {}; 
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
.item .batch{
  position: absolute;
  top:-8px;
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
.other{
  margin-top: auto;
}
</style>
