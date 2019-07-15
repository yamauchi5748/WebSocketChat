<template>
  <nav class="global-navigation">
    <router-link class="item" to="/frends">
      <img src="/img/icon-frends.png" />
    </router-link>
    <router-link class="item" to="/chat">
      <img src="/img/icon-chat.png" />
    </router-link>
    <router-link class="item" to="/timeline">
      <img src="/img/icon-timeline.png" />
    </router-link>
    <router-link class="item other" to="/other">
      <img src="/img/icon-other.png" />
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
  display: flex;
  margin: 20px 0;
  justify-content: center;
  align-items: center;
}
.item img {
  display: block;
  height: 40px;
  width: 40px;
}
.other{
  margin-top: auto;
}
</style>
