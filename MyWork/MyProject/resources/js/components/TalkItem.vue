<template>
  <div class="talk-item">
    <div class="talk-body">
      <div class="talk-detail-list">
        送信内容：{{talk}}
        <p class="talk-text" v-if="talk.message.type === 'text'">
          <span v-for="(line, index) in talk.message.body.split('\n')" :key="index">{{line}}</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    talk: Object,
    user_id: Number
  }
};
</script>
