<template>
    <div>
        <div class="all-wrapper">
            <div class="dropdown-wrapper" @click="isActive = !isActive">
                <div class="dropdown-text">
                    {{ label }}
                </div>
                <i class="el-icon-caret-bottom"></i>
            </div>
            <transition>
                <div class="list-items" v-if="isActive">
                    <template v-if="existsListItems">
                        <template v-for="(value, index) in listItems">
                            <div class="list-item"
                                 v-if="!is_myId(index)"
                                 v-bind:key="index"
                                 :class="[index == activeItemKey ? 'active' : '' ]"
                                 @click="handleClickItem(index)"
                            >
                                {{value.name}}
                            </div>
                        </template>
                    </template>
                    <template v-else>
                        <div class="list-item">
                            選択肢がありません
                        </div>
                    </template>
                </div>
            </transition>
        </div>
        <div class="dropdown-bg" @click="isActive = false" v-if="isActive"></div>
    </div>
</template>

<style>
    .dropdown-bg {
        width: 100vw;
        height: 100vh;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 2;
    }
    .all-wrapper {
        position: relative;
    }
    .dropdown-wrapper {
        padding: 10px;
        padding-left: 20px;
        color: #666666;

        display: flex;
        align-items: center;
    }

    .dropdown-text {
        background-color: #F3F4F6;
        font-size: 16px;
    }
    .dropdown-text:hover {
        background-color: #D3F4F6;
        cursor: pointer;
    }

    i {
        font-size: 10px;
        margin-left: 6px;
    }

    .list-items {
        width: 260px;
        max-height: 300px;
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid #B9BFC9;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12), 0 0 6px 0 rgba(0, 0, 0, 0.04);
        position: absolute;
        overflow-y: scroll;
        z-index: 3;
        padding: 0.5rem 0;
    }
    .list-item {
        color: #333;
        font-size: 14px;
        line-height: 16px;
        padding: 0.75rem 1rem;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

        position: relative;
    }
    .list-item:not(.active):hover {
        background-color: #D3F4F6;
        cursor: pointer;
    }
    .list-item.active {
        color: #fff;
        background-color: #182A4B;
    }
    
    
</style>


<script>
    import store from "../chat/store";

    export default {
        props: {
            user_id : Number,
            label: {
                type: String
            },
            listItems: {
                type: Array
            },
            activeItemKey: {
                type: Number
            }
        },
        data() {
            return {
                isActive: false,
            };
        },
        mounted() {
            console.log(this.label);
            console.log(this.listItems[0].name);
            console.log(this.activeItemKey);
        },
        computed: {
            existsListItems() {
                return true;
            }
        },
        methods: {
            handleClickItem(key) {
                if (key == this.activeItemKey) {
                    return;
                }

                this.isActive = false;
                this.action(key);
            },
            action(key){
                store.state.activeItemKey = key;
                console.log(store.state.activeItemKey);

                if(this.user_id > key){
                    store.state.room_id = "" + (key+1) + this.user_id;
                }else{
                    store.state.room_id = "" + this.user_id + (key+1);
                }
                console.log(store.state.room_id);
                store.state.label = "user" + (key+1) + "▿";
            },
            is_myId(index){
                if(index+1 == this.user_id){
                    return true;
                }
            }
        }
    }
</script>