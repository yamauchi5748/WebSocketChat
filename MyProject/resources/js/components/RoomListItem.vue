<template>
  <div class="room-list-item">
    <div class="room-list-item-information-left">
      <span class="room-list-item-name">ルーム名</span>
      <span class="latest-content">DDSK</span>
    </div>
    <div class="room-list-item-information-right">
      <span class="update-date">午後 12:34</span>
      <span class="room-list-item-unread">3</span>
    </div>
  </div>
</template>
<script>
export default {};
</script>
<style>
.room-list-item {
  width: 100%;
  height: 80px;
  padding: 10px;
  display: flex;
  align-items: stretch;
  user-select: none;
}
.room-list-item:hover {
  background-color: whitesmoke;
}
.room-list-item-information-left {
  flex: 1 1;

  display: flex;
  flex-direction: column;
  align-items: stretch;
}
.room-list-item-name {
  font-weight: bold;
}
.latest-content {
  color: gray;
}
.room-list-item-information-right {
  flex: 0 0 80px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
}
.update-date {
  font-size: 0.8rem;
  text-align: right;
  color: gray;
}
.room-list-item-unread {
  align-self: center;

  display: inline-flex;
  margin-top: 7px;
  padding: 0.25rem 0.75rem;
  justify-content: center;
  align-items: center;
  background-color: limegreen;
  font-size: 1rem;
  border-radius: 2rem;
}
</style>
