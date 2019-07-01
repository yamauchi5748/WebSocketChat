const store = {
    state: {
      user_id: null,
      room_id: null,
      timeline: [],
      label: "ユーザーを選択して下さい▿",
      listItems: [
        { name : "user1"},
        { name : "user2"},
        { name : "user3"},
        { name : "user4"}
      ],
      activeItemKey: null
    },
    // POSTするとこれが呼ばれる
    postMessage(text) {
      axios.post("/api/message", {
        user_id: this.state.user_id,
        room_id: this.state.room_id,
        text
      });
    },
    // チャンネル受信時にコールバックでこれが呼ばれる
    recieveMessage(messages) {
      this.state.timeline = [
        ...this.state.timeline,
        messages
      ];
    }
  };

  export default store;