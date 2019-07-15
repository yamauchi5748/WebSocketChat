<template>
  <div class="page-frends">
    <list-filter class="filter"></list-filter>
    <div class="wrapper-list-accordion">
      <accordion class="list-accordion-item">
        <template #header="methods">
          <div @click="methods.changeShow" class="header-accordion">プロフィール</div>
        </template>
        <template #contents>
          <div class="contents-accordion">
            <div class="list-item">
              <img class="icon-item-img" src="/img/profile.jpg" />
              <span class="name-item">{{ $root.user_id }}</span>
            </div>
          </div>
        </template>
      </accordion>
      <accordion class="list-accordion-item">
        <template #header="methods">
          <div @click="methods.changeShow" class="header-accordion">グループ</div>
        </template>
        <template #contents>
          <div class="contents-accordion">
            <div v-for="(room, key) in roomList" :key="key" :room="room" class="list-item">
              <img class="icon-item-img" src="/img/profile.jpg" />
              <span class="name-item">{{ room.group_name }}</span>
            </div>
          </div>
        </template>
      </accordion>
      <accordion class="list-accordion-item">
        <template #header="methods">
          <div @click="methods.changeShow" class="header-accordion">友だち</div>
        </template>
        <template #contents>
          <div class="contents-accordion">
            <div v-for="(user, key) in userList" :key="key" :user="user" class="list-item">
              <img class="icon-item-img" :src="'/storage/images/' + user.id  + '.jpg'" />
              <span class="name-item">{{ user.name }}</span>
            </div>
          </div>
        </template>
      </accordion>
    </div>
  </div>
</template>
<script>
import ListFilter from "./frends/ListFilter.vue";
import Accordion from "./Accordion.vue";
export default {
  components: { ListFilter, Accordion },
  computed: {
    roomList: function() {
      return this.$root.rooms.filter(room => {
        if (room.is_group) {
          return room.group_name.indexOf(this.$root.search_key) != -1;
        } else {
          return null;
        }
      });
    },
    userList: function(){
      return this.$root.user_list.filter(user => {
        return user.name.indexOf(this.$root.search_key) != -1;
      });
    },
    existsListItems() {
      if (this.roomList.length == 0) {
        return false;
      }
      return true;
    }
  }
};
</script>
<style scoped>
.page-frends {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}
.filter {
  flex: 0 0 56px;
}
.wrapper-list-accordion {
  flex: 1 1;
  width: 100%;
  margin: 0;
  padding: 0;
  flex-direction: column;
  align-items: stretch;
  overflow: auto;
}
.list-accordion-item {
  display: block;
  flex-direction: column;
  width: 100%;
  margin: 0;
  padding: 0;
  background-color: white;
}
.header-accordion {
  position: sticky;
  top: 0;
  display: flex;
  padding-left: 16px;
  align-items: center;
  height: 40px;
  border-top: solid 1px silver;
  border-bottom: solid 1px silver;
  background-color: ghostwhite;
  color: slategrey;
}
.contents-accordion {
  display: flex;
  flex-direction: column;
}
.list-item {
  display: flex;
  align-items: center;
  height: 80px;
}
.list-item:hover {
  background-color: whitesmoke;
}
.icon-item-img {
  display: block;
  margin-left: 16px;
  height: 60px;
  width: 60px;
  border-radius: 50%;
  object-fit: cover;
}
.name-item {
  display: block;
  margin-left: 16px;
  font-size: 1.1rem;
  font-weight: bold;
  color: black;
}
</style>