<template>
<div class="fragment">

  <div v-if="!loading && !error" class="container form-div">

    <fieldset>
      <legend v-if="!id">Create New User</legend>
      <legend v-else>Edit User</legend>

      <form class="form-user" @submit.prevent="saveUser" style="width:480px">
        <label for="inputName" class="sr-only">Name</label>
        <input style="margin-top:0;" v-model="name" type="text" id="inputName" class="form-control" placeholder="Name" required autofocus>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input v-model="email" type="email" id="inputEmail" class="form-control" placeholder="Email address"  autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input v-model="password" type="password" id="inputPassword" class="form-control" placeholder="Password" autofocus>
        <label for="inputPasswordConfirm" class="sr-only">Confirm Password</label>
        <input v-model="passwordConfirm" type="password" id="inputPasswordConfirm" class="form-control" placeholder="Confirm Password" autofocus>

        <label for="inputCompany" class="sr-only">Company</label>
        <select v-model="selectedCompanies" multiple required id="inputCompany" class="custom-select custom-select-lg mb-3">
          <option v-for="option in options" :key="option.value" :value="option.value">{{ option.text }}</option>

        </select>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Save User</button>
      </form>

    </fieldset>

  </div>

  <ErrorDisplay v-else-if="error" :error="error" />

  <LoadingDisplay v-else />

</div>
</template>

<script>
import axios from 'axios';
import ErrorDisplay from '../Common/ErrorDisplay';
import LoadingDisplay from '../Common/LoadingDisplay';

export default {
  components: {
    ErrorDisplay,
    LoadingDisplay
  },
  data() {
    return {
      user: {},
      options: [],
      loading: false,
      error: false,
      name: '',
      email: '',
      password: '',
      passwordConfirm: '',
      role: 'Manager',
      companies: [],
      selectedCompanies: []
    };
  },
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    if (this.id) {
      this.fetchData();
    }
    this.fetchCompanies();
  },
  props: {
    id: {
      type: Number,
      required: false,
      default: null,
    },
  },
  methods: {

    fetchCompanies() {
      //this.loading = true;
      axios({
          url: `${process.env.VUE_APP_API_URL}/companies`,
          method: 'GET',
        })
        .then((resp) => {
          resp = resp.data;
          //this.loading = false;
          var t = this;
          resp.forEach(function(c) {
            t.options.push({
              text: c.name,
              value: c.id
            });
          });

        })
        .catch((err) => {
          this.error = err.toString();
        });
    },

    saveUser() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        var urlId = this.id? '/' + this.id : ''
        axios({
            url: `${process.env.VUE_APP_API_URL}/users${urlId}`,
            data: {
              name: this.name,
              email: this.email,
              companies: this.selectedCompanies,
              password: this.password,
              role: 'Manager'
            },
            method: this.id? 'PUT' : 'POST',
          })
          .then((resp) => {
            this.loading = false;
            this.$router.push('/users')
            resolve(resp);
          })
          .catch((err) => {
            this.error = err.toString();
            reject(err);
          });
      });
    },

    fetchData() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        var t = this;
        axios({
            url: `${process.env.VUE_APP_API_URL}/users/${this.id}`,
            method: 'GET',
          })
          .then((resp) => {
          console.log(resp);
            resp = resp.data;
            this.loading = false;
            this.name = resp.name;
            this.email = resp.email;
            this.companies = resp.companies;
            resp.companies.forEach(function(company){
              t.selectedCompanies.push(company.id);
            });
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
