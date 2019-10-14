<template>
<div v-if="!loading && !error" class="fragment">

  <div class="mb-3">
    <a href="companies/create" class="prev-btn btn btn-primary btn-sm">Create New Company</a>
  </div>

  <ListDisplay :total="total" :getPage="getPage" :perPage="perPage" :items="companies" :pages="pages" :pageNumber="pageNumber">

    <template v-slot:listData="{item}">
      <div class="img-col col">
        <div class="img"><img :src="item.logo" class="card-img" :alt="item.name"></div>
      </div>

      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title">{{item.name}}</h5>
          <p class="card-text">{{item.email}}</p>
          <p class="card-text">{{item.website}}</p>
        </div>
      </div>

    </template>

    <template v-slot:listActions="{item}">

      <div class="col-md-1">
        <svg v-if="$store.getters.user.role === 'Admin' || ($store.getters.user.role === 'Manager' && $store.getters.user.companies.indexOf(item.id) !== -1)" @click="$router.push('companies/edit?id=' + item.id)" class="icon" viewBox="0 0 100 100">
          <title>Edit Company: {{item.name}}</title>
          <use xlink:href="../../assets/edit-solid.svg#editIcon"></use>
        </svg>
        <br>
        <svg v-if="$store.getters.user.role === 'Admin' || ($store.getters.user.role === 'Manager' && $store.getters.user.companies.indexOf(item.id) !== -1)" @click="deleteCompany(item.id)" class="icon trash-icon" viewBox="0 0 100 100">
          <title>Delete Company: {{item.name}}</title>
          <use xlink:href="../../assets/trash-alt-solid.svg#trashIcon"></use>
        </svg>
        <!--<br>
        <svg @click="$router.push('employees?company_id=' + item.id)" class="icon users-icon" viewBox="0 0 100 100">
          <title>View Users for Company: {{item.name}}</title>
          <use xlink:href="../../assets/users-solid.svg#usersIcon"></use>
        </svg>-->
      </div>

    </template>

  </ListDisplay>

  <div v-if="companies.length === 0">
    <div class="mb-3"><a href="companies/create" class="prev-btn btn btn-primary btn-sm">Create New Company</a></div>
    <h1 class="h3 mb-3 font-weight-normal">No companies found</h1>
  </div>

</div>

<ErrorDisplay v-else-if="error" :error="error" />

<LoadingDisplay v-else />
</template>

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
      companies: [],
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
    deleteCompany(id) {
      this.loading = true;
      const apiUrl = `${process.env.VUE_APP_API_URL}/companies/${id}`;
      axios({
        url: apiUrl,
        method: 'DELETE',
      })
        .then((resp) => {
          this.loading = false;
          this.$router.push('/companies').catch((err) => {});
          window.location.reload();
        })
        .catch((err) => {
          this.error = err.toString();
        });
    },
    fetchData() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        const apiUrl = `${process.env.VUE_APP_API_URL}/companies?page=${Number(this.pageNumber)}&perPage=${Number(this.perPage)}`;
        axios({
          url: apiUrl,
          method: 'GET',
        })
          .then((resp) => {
            this.loading = false;
            this.companies = resp.data.data;
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
