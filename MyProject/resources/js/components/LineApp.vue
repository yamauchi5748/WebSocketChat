<template>
  <div class="line">
    <the-global-navigation class="navigation"></the-global-navigation>
    <router-view class="application"></router-view>
  </div>
</template>
<script>
import TheGlobalNavigation from "./TheGlobalNavigation"
export default {
  components: {
    TheGlobalNavigation
  },
  props : {
    user : String
  },
  mounted() {
    this.$root.user = JSON.parse(this.user);
  },
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
.navigation{
  flex: 0 0 80px;
}
.application{
  flex:1 1;
  background-color: white;
}
</style>

