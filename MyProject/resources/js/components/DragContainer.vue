<template>
  <div ref="drag" class="drag-container">
    <slot name="contents" :event="startEventHandler"></slot>
  </div>
</template>
<script>
export default {
  props: {
    width: {
      type: String,
      default: "380px"
    },
    height: {
      type: String,
      default: "460px"
    }
  },
  data() {
    return {
      x: null,
      y: null,
      dragDOM: null,
      targetDOM: null
    };
  },
  mounted() {
    this.dragDOM = this.$refs.drag;
  },
  methods: {
    startEventHandler(e) {
      if (e.type === "mousedown") {
        var event = e;
      } else {
        var event = e.changedTouches[0];
      }
      this.targetDOM = e.target;

      this.x = event.pageX - this.dragDOM.offsetLeft;
      this.y = event.pageY - this.dragDOM.offsetTop;
      document.body.addEventListener("mousemove", this.moveEventHandler);
      document.body.addEventListener("touchmove", this.moveEventHandler);
    },
    moveEventHandler(e) {
      if (e.type === "mousemove") {
        var event = e;
      } else {
        var event = e.changedTouches[0];
      }
      e.preventDefault();
      this.dragDOM.style.top = event.pageY - this.y + "px";
      this.dragDOM.style.left = event.pageX - this.x + "px";
      this.targetDOM.addEventListener("mouseup", this.upEventHandler);
      this.targetDOM.addEventListener("touchend", this.upEventHandler);
    },
    upEventHandler(e) {
      document.body.removeEventListener("mousemove", this.moveEventHandler);
      this.targetDOM.removeEventListener("mouseup", this.upEventHandler);
      document.body.removeEventListener("touchmove", this.moveEventHandler);
      this.targetDOM.removeEventListener("touchend", this.upEventHandler);

      this.targetDOM = null;
    }
  }
};
</script>
<style>
.drag-container {
  display: block;
  position: fixed;
  top: 120px;
  left: 120px;
  z-index: 9999;
}
</style>