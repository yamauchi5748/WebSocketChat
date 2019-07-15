<template>
  <div class="accordion">
    <slot name="header" :changeShow="changeShow"></slot>
    <template v-if="show">
      <slot name="contents"></slot>
    </template>
  </div>
</template>
<script>
export default {
  data() {
    return {
      show: true
    };
  },
  methods: {
    changeShow() {
      console.log("a");
      this.show = !this.show;
    }
  }
};
</script>

<style>
.accordion {
  display: flex;
  margin: 0;
  padding: 0;
  justify-content: flex-start;
  flex-direction: column;
}
</style>

