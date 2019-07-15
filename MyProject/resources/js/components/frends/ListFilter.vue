<template>
  <label class="wrapper-input-search">
    <img class="icon-search" src="/img/icon-search.png" />
    <input class="input-search" placeholder="名前で検索" type="text" />
  </label>
</template>
<style>
.wrapper-input-search {
  display: flex;
  margin: 0;
  padding: 12px 0px;
  align-items: center;
}
.icon-search {
  flex: 0 0 24px;
  width: 24px;
  height: 24px;
  display: block;
  margin-left: 16px;
  object-fit: cover;
}
.input-search {
  flex: 1 1;
  display: block;
  margin-left: 8px;
  height: 100%;
  border: none;
  outline: none;
}
</style>
