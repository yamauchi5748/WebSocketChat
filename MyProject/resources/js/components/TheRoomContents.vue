<template>
  <div class="room">
    <the-room-contents-header></the-room-contents-header>
    <div class="room-talk">
      <room-contents-message v-for="(message,key) in messages" :message="message" :key="key"></room-contents-message>
    </div>
    <div class="input-area">
      <div class="input-item-list">
        <label class="input-img-wrapper">
          <img class="input-img-icon" src="/img/send-file.png" />
          <input class="input-img" type="file" />
        </label>
      </div>
      <div class="input-text-wrapper">
        <textarea class="input-text"></textarea>
        <img class="send-action" src="/img/send.png" />
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
      messages: [
        {
          text: "unko"
        },
        {
          image:
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1WwD38PnpXqMBlQa4SNFgwifwPFYKylqFxD_KL5E5sXPg_g4HDw"
        },
        {
          video: "./video/temp.mp4"
        }
      ]
    };
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

