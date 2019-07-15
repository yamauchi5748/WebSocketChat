<template>
  <div class="room-talk-item" :class="{aite:isAite}">
    <span class="room-contents-message-information">
      <span class="room-contents-message-already-read">{{ message.already_read }}</span>
      <span class="room-contents-message-date">{{ message.created_at }}</span>
    </span>
    <div
      class="room-talk-item-text"
      v-if="message.content_type == 'text'"
      :class="{aitetext:isAite}"
    >{{ message.message }}</div>
    <img
      class="room-talk-item-image"
      v-else-if="message.content_type == 'image'"
      :src="message.image"
    />
    <video
      class="room-talk-item-video"
      v-else-if="message.content_type == 'video'"
      :src="message.video"
      controls
    ></video>
  </div>
</template>
<script>
export default {
  props: {
    message: Object
  },
  computed: {
    isAite() {
      return this.message.sender_id != this.$root.user.id;
    }
  },
  mounted() {
    //** */ スクロールを最下部にする **//
    var obj = this.$parent.$el.children[1];//スクロールオブジェクト取得
    obj.scrollTop = obj.scrollHeight;
  }
};
</script>
<style>
.room-talk-item {
  display: flex;
  justify-content: flex-end;
  flex-direction: row;
  font-size: 1.2rem;
  margin: 16px;
}
.room-talk-item * {
  display: inline-block;
  max-width: 350px;
}
.room-talk-item-text {
  padding: 6px 10px;
  word-wrap: break-word;
  white-space: normal; /*指定widthに合わせて、文字を自動的に改行*/
  border-radius: 15px;
  background-color: lawngreen;
}
.room-contents-message-information {
  align-self: flex-end;

  display: inline-flex;
  margin: 0 6px;
  flex-direction: column;
  font-size: 0.8rem;
}
.aite {
  flex-direction: row-reverse;
}
.aitetext {
  background-color: ghostwhite;
}
</style>

