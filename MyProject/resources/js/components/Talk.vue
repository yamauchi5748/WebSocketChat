<template>
  <div class="talk" id="talk">
    <div class="talk-inside">
      <div class="talk-list">
        <TalkItem v-for="(talk, index) in timeline" :key="index" :talk="talk"/>
      </div>
    </div>
  </div>
</template>
<script>
import TalkItem from "./TalkItem";

export default {
  components: { TalkItem },
  data() {
    return {
      timeline: this.$root.timeline
    };
  }
};
</script>
