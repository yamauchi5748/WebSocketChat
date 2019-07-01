<template>
    <div class="chat">
        <div class="chat-body">
            <ChatChannel 
                :user_id="state.user_id"
            />
            <Talk
                :timeline="state.timeline"
                :user_id="state.user_id"
            />
            <Message />
        </div>
    </div>
</template>
<script>
import Message from "./Message";
import Talk from "./Talk";
import ChatChannel from "./ChatChannel";
import store from "../chat/store";

export default {
    components: { ChatChannel, Talk, Message },
    props: {
        user_id : Number
    },
    data() {
        return {
            state: store.state,
        };
    },
    mounted() {
        store.state.user_id = this.user_id;
        let room_id;

        for(let i = 1; i <= 4; i++){
            if(i == this.user_id){
                continue;
            }else if(i < this.user_id){
                room_id = "" + i + this.user_id;
            }else{
                room_id = "" + this.user_id + i;
            }
            this.connectChannel(room_id);    
        }
        this.connectChannel(3);
    },
    methods: {
        connectChannel(room_id) {
            Echo.join('room.' + room_id)
            .here((users) => {
                console.log("参加しました");
            })
            .joining((user) => {
                console.log(user.name);
            })
            .leaving((user) => {
                console.log(user.name);
            })
            .listen('MessageRecieved', (e) => {
                store.recieveMessage(e.messages);
            });
        }
    }
};

</script>