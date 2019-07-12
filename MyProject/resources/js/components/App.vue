<template>
  <div class="chat">
    <div>
      <ChatChannel />
    </div>
    <div class="chat-body">
      <div class="talk" v-if="isTimeline()">
        <Talk />
      </div>
      <div class="message">
        <Message />
      </div>
    </div>
  </div>
</template>

<style>
.chat-body {
  width: 700px;
  background: #4682b4;
}
.talk {
  margin-bottom: 10px;
}
</style>

<script>
import Message from "./Message";
import Talk from "./Talk";
import ChatChannel from "./ChatChannel";

export default {
  components: { ChatChannel, Talk, Message },
  props: {
    user_id: String
  },
  data() {
    return {
      state: this.$root
    };
  },
  mounted() {
    this.$root.user_id = this.user_id;
  },
  methods: {
    isTimeline() {
      return this.$root.timeline.length != 0;
    }
  }
};
</script>