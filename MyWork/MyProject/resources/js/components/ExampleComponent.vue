<template>
  <div>
    ascs
    <div class="row">
      <div class="col-sm-6">
        <ul class="pagination">
          <li :class="{disabled: current_page <= 1}"><a href="#" @click="change(1)">&laquo;</a></li>
          <li :class="{disabled: current_page <= 1}"><a href="#" @click="change(current_page - 1)">&lt;</a></li>
          <li v-for="page in pages" :key="page" :class="{active: page === current_page}">
            <a href="#" @click="change(page)">{{page}}</a>
          </li>
          <li :class="{disabled: current_page >= last_page}"><a href="#" @click="change(current_page + 1)">&gt;</a></li>
          <li :class="{disabled: current_page >= last_page}"><a href="#" @click="change(last_page)">&raquo;</a></li>
        </ul>
      </div>
      <div style="margin-top: 40px" class="col-sm-6 text-right">全 {{total}} 件中 {{from}} 〜 {{to}} 件表示</div>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr><th>ID</th><th>氏名</th><th>メールアドレス</th></tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{user.id}}</td>
          <td>{{user.name}}</td>
          <td>{{user.email}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        users: [],
        current_page: 1,
        last_page: 1,
        total: 1,
        from: 0,
        to: 0
      }
    },
    mounted() {
      this.load(1)
    },
    methods: {
      load(page) {
        axios.get('/api/user?page=' + page).then(({data}) => {
          this.users = data.data
          this.current_page = data.current_page
          this.last_page = data.last_page
          this.total = data.total
          this.from = data.from
          this.to = data.to
        })
      },
      change(page) {
        if (page >= 1 && page <= this.last_page) this.load(page)
      }
    },
    computed: {
      pages() {
        let start = _.max([this.current_page - 2, 1])
        let end = _.min([start + 5, this.last_page + 1])
        start = _.max([end - 5, 1])
        return _.range(start, end)
      },
    }
  }
</script>