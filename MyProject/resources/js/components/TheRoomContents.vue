<template>
  <div class="room">
    <the-room-contents-header></the-room-contents-header>
    <div class="room-talk">
      <room-contents-message v-for="(message,key) in timeLine" :key="key" :message="message"></room-contents-message>
    </div>
    <div class="input-area">
      <div class="input-item-list">
        <label class="input-img-wrapper">
          <img class="input-img-icon" src="/img/send-file.png" />
          <input class="input-img" type="file" @change="onDrop" />
        </label>
      </div>
      <div class="input-text-wrapper">
        <textarea class="input-text" v-model="text"></textarea>
        <img class="send-action" src="/img/send.png" @click="send" />
      </div>
    </div>
  </div>
</template>
<script>
import TheRoomContentsHeader from "./TheRoomContentsHeader";
import RoomContentsMessage from "./RoomContentsMessage";
export default {
  components: { TheRoomContentsHeader, RoomContentsMessage },
  data() {
    return {
      text: "",
      file: null
    };
  },
  computed: {
    timeLine: function() {
      if (this.$root.now_room) {
        console.log(this.$root.now_room.contents);
        console.log(this.$root.user_id);
        return this.$root.now_room.contents;
      }
      return false;
    }
  },
  methods: {
    onDrop: function(event) {
      this.file = event.target.files[0];
      this.send();
    },
    send() {
      // なぜかテキストと画像は別処理
      const sendText = this.text.trim().replace(/\n$/, "");
      if (this.text.length <= 0) {
        console.log("noneText");
      } else {
        this.text = "";
        this.$root.postMessage({
          type: "text",
          body: sendText
        });
      }

      //ファイルアップロード
      let data = new FormData();

      if (this.file && this.file.type.match("video")) {
        data.append("video", this.file);
      } else if (this.file && this.file.type.match("image")) {
        data.append("image", this.file);
      } else {
        console.log("画像なし");
        return;
      }
      console.log(this.file.type);

      this.$root.postFile(data);
      this.file = null;
    }
  }
};
</script>
<style scoped>
.room {
  display: flex;
  align-items: stretch;
  flex-direction: column;
  width: 100%;
  height: 100%;
}

.room-talk {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  overflow: auto;
  flex-grow: 1;
  background-color: darkslategray;
}

.input-area {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  flex: 0 0 140px;
}
.input-item-list {
  display: flex;
  justify-content: flex-start;
  align-items: center;

  flex: 0 0 40px;
  border-bottom: solid 1px silver;
}
.input-text-wrapper {
  display: flex;
  flex: 1 1;
  align-items: stretch;
}
.input-text {
  flex: 1 1;
  resize: none;
  border: none;
}
.send-action {
  flex: 0 0 100px;
  height: 100px;
  border-left: solid 1px silver;
}
.input-img-wrapper {
  padding: 0;
  margin: 0;
}
.input-img-icon {
  height: 30px;
  width: 30px;
}
.input-img {
  display: none;
}
</style>

