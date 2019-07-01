<template>
    <div class="main">
        <div class="input-group">
            <span class="input-group-btn">
                <button id="personal" class="btn btn-primary btn-lg" @click="change('personal')">個チャ</button>
            </span>
            <span class="input-group-btn">
                <button id="group" class="btn btn-success active" @click="change('group')">グループ</button>
            </span>
        </div>
        <div class="user-channel">
            <UserChannel
                :user_id="state.user_id"
                :label="state.label"
                :listItems="state.listItems"
                :activeItemKey="state.activeItemKey"
            />
        </div>
    </div>
</template>

<script>
    import store from "../chat/store";
    import UserChannel from "./UserChannel";

    export default {
        components: { UserChannel },
        props: {
            user_id : Number,
        },
        data(){
            return {
                state : store.state,
            }
        },
        methods: {
            change(id){
                let personal_btn = document.getElementById("personal");
                let group_btn = document.getElementById("group");
                let user_channel = document.getElementsByClassName("user-channel")[0];

                if(id == "personal"){
                    personal_btn.setAttribute("class", "btn btn-primary btn-lg");
                    group_btn.setAttribute("class", "btn btn-success active");
                    user_channel.setAttribute("style", "display: block;");

                    store.state.label = "ユーザーを選択して下さい▿";
                    store.state.room_id = null;
                }else if(id == "group"){
                    personal_btn.setAttribute("class", "btn btn-success active");
                    group_btn.setAttribute("class", "btn btn-primary btn-lg");

                    store.state.room_id = 3;
                    user_channel.setAttribute("style", "display: none;");
                }
            }
        },
    }
</script>

<style scoped>
    .input-group-btn{
        padding-right: 10px;
    }
</style>