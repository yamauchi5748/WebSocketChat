<template>
  <div class="talk-item">
    <div class="talk-body">
      <div :class="getClassName(talk)" v-if="talk.sender_id == this.$root.user_id">
        <p class="talk-sender-name">{{ talk.sender_name }}</p>
        <div class="talk-clear"></div>
        <div class="talk-box">
          <span
            class="talk-already-read"
            v-if="$root.now_room.is_group"
          >Read {{ talk.already_read }}</span>
          <span class="talk-already-read" v-else>
            <span v-if="talk.already_read">Read</span>
          </span>
          <div class="talk-clear"></div>
          <div>{{ talk.created_at }}</div>
        </div>
        <div class="talk-message talk-message-right" v-if="talk.content_type == 'text'">{{ talk.message }}</div>
        <img a class="talk-message" :src="talk.image" v-if="talk.content_type == 'image'" />
        <video
          a
          class="talk-message"
          :src="talk.video"
          v-if="talk.content_type == 'video'"
          controls
        ></video>
      </div>
      <div :class="getClassName(talk)" v-else>
        <p class="talk-sender-name">{{ talk.sender_name }}</p>
        <div class="talk-message talk-message-left" v-if="talk.content_type == 'text'">{{ talk.message }}</div>
        <img class="talk-message" :src="talk.image" v-if="talk.content_type == 'image'" />
        <video
          a
          class="talk-message"
          :src="talk.video"
          v-if="talk.content_type == 'video'"
          controls
        ></video>
        <div>{{ talk.created_at }}</div>
      </div>
    </div>
    <div class="talk-clear"></div>
  </div>
</template>

<style>
/* メッセージ全般のスタイル */
.talk-body {
  margin: 0px;
  padding: 0 14px; /*吹き出しがタイムラインの側面にひっつかない様に隙間を開ける*/
  word-wrap: break-word; /* 吹き出し内で自動で改行 */
  white-space: normal; /*指定widthに合わせて、文字を自動的に改行*/
  margin-top: 20px; /*上下の吹き出しがひっつかない様に隙間を入れる*/
  max-width: 100%; /*文字が長くなった時に吹き出しがタイムラインからはみ出さない様にする*/
}
.talk-box {
  color: #fff; /*テキストを白にする*/
  float: left;
}
/* メッセージ１（左側） */
.talk-detail-left {
  float: left; /*吹き出しをtalkに対して左寄せ*/
  line-height: 1.3em;
}
.talk-detail-left .talk-message {
  color: #333; /*テキストを黒にする*/
  background: #fff;
  border: 2px;
  border-radius: 30px 30px 30px 0px; /*左下だけ尖らせて吹き出し感を出す*/
}
/* メッセージ２（右側） */
.talk-detail-right {
  float: right; /*吹き出しをtalkに対して右寄せ*/
  line-height: 1.3em;
}

.talk-detail-right .talk-message {
  background: lawngreen;
  border: 2px solid lawngreen;
  border-radius: 30px 30px 0px 30px; /*右下だけ尖らせて吹き出し感を出す*/
  margin-left: 5px; /*右側の発言だとわかる様に、吹き出し左側に隙間を入れる*/
}
/* 回り込みを解除 */
.talk-clear {
  clear: both; /* 左メッセージと右メッセージの回り込み(float)の効果の干渉を防ぐために必要（これが無いと、自分より下のメッセージにfloatが影響する） */
}
/* ユーザー名 */
.talk-sender-name {
  font-size: 18px;
}
.talk-detail-right .talk-sender-name {
  float: right;
}
/* 既読 */
.talk-already-read {
  font-size: 12px;
  float: right;
}
/* メッセージ */
.talk-message {
  max-width: 300px;
  padding-right: 10px; /*文字や画像（コンテンツ）の外側に隙間を入れる*/
  padding-left: 10px; /*文字や画像（コンテンツ）の外側に隙間を入れる*/
  font-size: 21px;
}
.talk-message-right {
  float: right;
}
.talk-message-left {
  float: left;
}
</style>

<script>
export default {
  props: {
    talk: Object
  },
  methods: {
    getClassName(talk) {
      return talk.sender_id == this.$root.user_id
        ? "talk-detail-right"
        : "talk-detail-left";
    }
  }
};
</script>
