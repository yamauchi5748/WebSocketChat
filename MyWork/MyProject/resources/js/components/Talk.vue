<template>
  <div class="talk" id="talk">
      <div class="talk-inside">
          <div class="talk-list">
           Timeline内容： {{ timeline }}
              <TalkItem
                v-for="(talk, index) in timeline"
                :key="index"
                :talk="talk"
                :user_id="user_id"
              />
          </div>
      </div>
  </div>
</template>

<script>
import store from "../chat/store";
import TalkItem from "./TalkItem";

export default {
  components: { TalkItem },
  props: {
    timeline: Array,
    user_id: Number
  }
};
</script>
