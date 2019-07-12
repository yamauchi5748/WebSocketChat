<template>
  <div class="room-talk-item">
    <span class="room-contents-message-information">
      <span class="room-contents-message-already-read">既読数</span>
      <span class="room-contents-message-date">投稿日時</span>
    </span>
    <div class="room-talk-item-text" v-if="message.text">{{message.text}}</div>
    <img class="room-talk-item-image" v-else-if="message.image" :src="message.image" />
    <video class="room-talk-item-video" v-else-if="message.video" :src="message.video" controls></video>
  </div>
</template>
<script>
export default {
  props: {
    message: Object
  }
};
</script>
<style>
.room-talk-item {
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
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
  border-radius: 15px;
  background-color: lawngreen;
}
.room-contents-message-information {
  align-self: flex-end;

  display: inline-flex;
  margin: 0 6px;
  flex-direction: column;
  font-size: 0.4rem;
  color: silver;
}
</style>

