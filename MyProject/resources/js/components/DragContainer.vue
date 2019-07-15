<template>
  <!--
  <div>
    <div v-on:click.stop="show=true">
      <slot name="button"></slot>
    </div>
    <div v-show="show" :style="{width,height}" ref="drag" class="drag-container">
      <div class="drag-container-header">
        <span
          class="drag-header-text"
          ref="header"
          @mousedown="startEventHandler"
          @touchstart="startEventHandler"
        >
          <slot name="header"></slot>
        </span>
        <span @click="close" class="drag-container-cancel-button">×</span>
      </div>
      <div class="drag-container-contents">
        <slot name="content"></slot>
      </div>
      <div class="drag-container-actions">
        <slot name="actions" :close="close" :open="open"></slot>
      </div>
   </div>  
  </div>
  -->
  <div ref="drag" class="drag-container">
    <slot name="contents" :grip="startEventHandler">
      testtest
    </slot>
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
      show: false,
      x: null,
      y: null,
      dragDOM: null,
      headerDOM: null
    };
  },
  mounted() {
    this.dragDOM = this.$refs.drag;
    this.headerDOM = this.$refs.header;
  },
  methods: {
    close() {
      this.show = false;
    },
    open() {
      this.show = true;
    },
    startEventHandler(e) {
      if (!this.show) return;
      if (e.type === "mousedown") {
        var event = e;
      } else {
        var event = e.changedTouches[0];
      }
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
      this.headerDOM.addEventListener("mouseup", this.upEventHandler);
      this.headerDOM.addEventListener("touchend", this.upEventHandler);
    },
    upEventHandler(e) {
      document.body.removeEventListener("mousemove", this.moveEventHandler);
      this.headerDOM.removeEventListener("mouseup", this.upEventHandler);
      document.body.removeEventListener("touchmove", this.moveEventHandler);
      this.headerDOM.removeEventListener("touchend", this.upEventHandler);
    }
  }
};
</script>
<style>
.drag-container {
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 50px;
  left: 120px;
  z-index: 9999;
  background-color: white;
}
.drag-container-header {
  display: inline-flex;
  padding-left: 10px;
  height: 40px;
  align-items: center;
  color: white;
  cursor: default;
  background-color: darkslategray;
}
.drag-header-text {
  user-select: none;
  flex: 1 1;
}
.drag-container-cancel-button {
  cursor: pointer;
  flex: 0 0 40px;
  font-weight: bold;
  font-size: 1.8rem;
  text-align: center;
  color: gray;
}
.drag-container-contents {
  flex: 1 1;
  display: flex;
  flex-direction: column;
  border-left: solid 1px gray;
  border-right: solid 1px gray;
}
.drag-container-actions {
  flex: 0 0 70px;

  display: flex;
  justify-content: center;
  align-items: center;
  border: solid 1px gray;
  border-top: solid 1px silver;
}
</style>