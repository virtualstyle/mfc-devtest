<template>
<div v-if="!loading && !error" class="fragment">

  <div class="mb-3">
    <a href="users/create" class="prev-btn btn btn-primary btn-sm">Create New User</a>
  </div>

  <ListDisplay :total="total" :getPage="getPage" :perPage="perPage" :items="users" :pages="pages" :pageNumber="pageNumber">

    <template v-slot:listData="{item}">
      <div class="col-md-11">
        <div class="card-body">
          <p class="card-title"><b>{{item.name}}</b></p>
          <p class="card-text">{{item.email}}</p>
          <p class="card-text">{{item.role}}</p>
          <p class="card-text">
            <b>Companies: </b>
            <span v-for="company in item.companies">{{company.name}}, </span>
          </p>
        </div>
      </div>

    </template>

    <template v-slot:listActions="{item}">

      <div class="col-md-1">
        <svg @click="$router.push('users/edit?id=' + item.id)" class="icon" viewBox="0 0 100 100">
          <title>Edit User: {{item.name}}</title>
          <use xlink:href="../../assets/edit-solid.svg#editIcon"></use>
        </svg>
        <br>
        <svg @click="deleteUser(item.id)" class="icon trash-icon" viewBox="0 0 100 100">
          <title>Delete User: {{item.name}}</title>
          <use xlink:href="../../assets/trash-alt-solid.svg#trashIcon"></use>
        </svg>
      </div>

    </template>

  </ListDisplay>

  <div v-if="users.length === 0">
    <div class="mb-3"><a href="users/create" class="prev-btn btn btn-primary btn-sm">Create New User</a></div>
    <h1 class="h3 mb-3 font-weight-normal">No users found</h1>
  </div>

</div>

<ErrorDisplay v-else-if="error" :error="error" />

<LoadingDisplay v-else />
</template>

<style>
.card {
  height: auto;
}
.card-body{
  padding: 3px;
}
.card-text{
  font-size: .8rem;
}
</style>

<script>
import axios from 'axios';
import ErrorDisplay from '../Common/ErrorDisplay';
import LoadingDisplay from '../Common/LoadingDisplay';
import ListDisplay from '../Common/ListDisplay';

export default {
  components: {
    ErrorDisplay,
    LoadingDisplay,
    ListDisplay,
  },
  data() {
    return {
      pageNumber: 1,
      pages: 0,
      total: 0,
      users: [],
      loading: false,
      error: false,
    };
  },
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.fetchData();
  },
  props: {
    perPage: {
      type: Number,
      required: false,
      default: 10,
    },
  },
  watch: {
    // call again the method if the route changes
    $route: 'fetchData',
  },
  methods: {
    getPage(page) {
      this.pageNumber = page;
      this.fetchData();
    },
    deleteUser(id) {
      this.loading = true;
      const apiUrl = `${process.env.VUE_APP_API_URL}/users/${id}`;
      axios({
        url: apiUrl,
        method: 'DELETE',
      })
        .then((resp) => {
          this.loading = false;
          this.$router.push('/users').catch((err) => {});
          window.location.reload();
        })
        .catch((err) => {
          this.error = err.toString();
        });
    },
    fetchData() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        const apiUrl = `${process.env.VUE_APP_API_URL}/users?page=${Number(this.pageNumber)}&perPage=${Number(this.perPage)}`;
        axios({
          url: apiUrl,
          method: 'GET',
        })
          .then((resp) => {
            this.loading = false;
            this.users = resp.data;
            this.pages = resp.data.last_page;
            this.pageNumber = resp.data.current_page;
            this.total = resp.data.total;
            resolve(resp);
          })
          .catch((err) => {
            this.error = err.toString();
            reject(err);
          });
      });
    },
  },
};
</script>
