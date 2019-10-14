<template>
<div v-if="!loading && !error" class="fragment">

  <div class="mb-3">
    <a href="employees/create" class="prev-btn btn btn-primary btn-sm">Create New Employee</a>
  </div>

  <ListDisplay :total="total" :getPage="getPage" :perPage="perPage" :items="employees" :pages="pages" :pageNumber="pageNumber">

    <template v-slot:listData="{item}">
      <div class="col-md-11">
        <div class="card-body">
          <p class="card-title"><b>{{item.last_name}}, {{item.first_name}}</b></p>
          <p class="card-text">{{item.name}}</p>
          <p class="card-text">{{item.email}}</p>
          <p class="card-text">{{item.phone}}</p>
        </div>
      </div>

    </template>

    <template v-slot:listActions="{item}">

      <div class="col-md-1">
        <svg v-if="$store.getters.user.role === 'Admin' || ($store.getters.user.role === 'Manager' && $store.getters.user.companies.indexOf(item.company) !== -1)" @click="$router.push('employees/edit?id=' + item.id)" class="icon" viewBox="0 0 100 100">
          <title>Edit Employee: {{item.last_name}}, {{item.first_name}}</title>
          <use xlink:href="../../assets/edit-solid.svg#editIcon"></use>
        </svg>
        <br>
        <svg v-if="$store.getters.user.role === 'Admin' || ($store.getters.user.role === 'Manager' && $store.getters.user.companies.indexOf(item.company) !== -1)" @click="deleteEmployee(item.id)" class="icon trash-icon" viewBox="0 0 100 100">
          <title>Delete Employee: {{item.last_name}}, {{item.first_name}}</title>
          <use xlink:href="../../assets/trash-alt-solid.svg#trashIcon"></use>
        </svg>
      </div>

    </template>

  </ListDisplay>

  <div v-if="employees.length === 0">
    <div class="mb-3"><a href="employees/create" class="prev-btn btn btn-primary btn-sm">Create New Employee</a></div>
    <h1 class="h3 mb-3 font-weight-normal">No employees found</h1>
  </div>

</div>

<ErrorDisplay v-else-if="error" :error="error" />

<LoadingDisplay v-else />
</template>

<style>
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
      employees: [],
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
    deleteEmployee(id) {
      this.loading = true;
      const apiUrl = `${process.env.VUE_APP_API_URL}/employees/${id}`;
      axios({
        url: apiUrl,
        method: 'DELETE',
      })
        .then((resp) => {
          this.loading = false;
          this.$router.push('/employees').catch((err) => {});
          window.location.reload();
        })
        .catch((err) => {
          this.error = err.toString();
        });
    },
    fetchData() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        const apiUrl = `${process.env.VUE_APP_API_URL}/employees?page=${Number(this.pageNumber)}&perPage=${Number(this.perPage)}`;
        axios({
          url: apiUrl,
          method: 'GET',
        })
          .then((resp) => {
            this.loading = false;
            this.employees = resp.data.data;
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
