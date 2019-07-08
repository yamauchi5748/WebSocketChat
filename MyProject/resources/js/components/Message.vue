<template>
  <div class="input-group">
    <input type="text" class="form-control form-control-lg" v-model="text" />
    <span class="input-item">
      <label class="input-item__label">
        <span v-if="this.file">{{ this.file.name }}</span>
        <span v-else>画像を選択</span>
        <input type="file" @change="onDrop" />
      </label>
    </span>
    <span class="input-group-btn">
      <button class="btn btn-primary btn-lg" @click="send">送信</button>
    </span>
  </div>
</template>

<script src="https://echo-all-you-need-is-tuna.noplan.cc/socket.io/socket.io.js"></script>
<script>
export default {
  data() {
    return {
      text: "",
      file: null
    };
  },
  methods: {
    onDrop: function(event) {
      console.log("otinnnnn", event);
      this.file = event.target.files[0];
    },
    send() {
      // なぜかテキストと画像は別処理
      let data = new FormData();
      data.append("image", this.file);
      this.$root.postFile(data);
      this.file = null;
      const sendText = this.text.trim().replace(/\n$/, "");
      if(this.text.length <= 0){
        console.log("noneText");
          return;
      }
      this.text = "";
      this.$root.postMessage({
        type: "text",
        body: sendText
      });
    }
  }
};
</script>

<style scoped>
.input-item {
  background-color: aliceblue;
}

label > input {
  display: none;
}

label {
  padding: 0 1rem;
  border: solid 1px #888;
}

label::after {
  content: "+";
  font-size: 1rem;
  color: #888;
  padding-left: 1rem;
}
</style>