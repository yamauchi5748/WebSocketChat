<template>
  <div class="room-list-filter-wrapper">
    <input class="room-list-filter" type="text" placeholder="ルーム名" />
  </div>
</template>
<script>
export default {};
</script>
<style scoped>
.room-list-filter-wrapper {
  border-bottom: solid 1px silver;
  padding: 6px 10px;
}
.room-list-filter {
  display: block;
  border: none;
  width: 100%;
  height: 100%;
}
.room-list-filter:focus {
  outline: none;
}
</style>
