<template>
    <div class="input-group">
        <input type="text" class="form-control form-control-lg" v-model="text">
        <span class="input-group-btn">
            <button class="btn btn-primary btn-lg" @click="send">送信</button>
        </span>
    </div>
</template>

<script src="https://echo-all-you-need-is-tuna.noplan.cc/socket.io/socket.io.js"></script>
<script>
    import store from "../chat/store";

    export default {
        data(){
            return {
                text:'uko'
            }
        },
        methods: {
            send() {
                const sendText = this.text.trim().replace(/\n$/, "");
                this.text = '';
                store.postMessage({
                    type: "text",
                    body: sendText
                });
            }
        },
    }
</script>

<style scoped>

</style>