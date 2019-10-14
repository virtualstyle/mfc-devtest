<template>
<div class="fragment">

  <div v-if="!loading && !error" class="container form-div">

    <fieldset>
      <legend v-if="!id">Create New Employee</legend>
      <legend v-else>Edit Employee</legend>

      <form class="form-employee" @submit.prevent="saveEmployee" style="width:480px">
        <label for="inputFirstName" class="sr-only">First Name</label>
        <input style="margin-top:0;" v-model="first_name" type="text" id="inputFirstName" class="form-control" placeholder="First name" required autofocus>
        <label for="inputLastName" class="sr-only">Last Name</label>
        <input style="margin-top:0;" v-model="last_name" type="text" id="inputLastName" class="form-control" placeholder="Last name" required autofocus>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input v-model="email" type="email" id="inputEmail" class="form-control" placeholder="Email address"  autofocus>
        <label for="inputPhone" class="sr-only">Phone</label>
        <input v-model="phone" type="text" id="inputPhone" class="form-control" placeholder="Phone" autofocus>

        <label for="inputCompany" class="sr-only">Company</label>
        <select v-model="company" required id="inputCompany" class="custom-select custom-select-lg mb-3">
          <option disabled value="">Select company</option>
          <option v-for="option in options" :key="option.value" :value="option.value">{{ option.text }}</option>

        </select>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Save Employee</button>
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
      employee: {},
      options: [],
      loading: false,
      error: false,
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      company: 0
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
            var push = true;
            if($store.getters.user.role === 'Manager') {
              if($store.getters.user.companies.indexOf(c.id) === -1) {
                push = false;
              }
            }
            if(push) {
              t.options.push({
                text: c.name,
                value: c.id
              });
            }
          });

        })
        .catch((err) => {
          this.error = err.toString();
        });
    },

    saveEmployee() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        var urlId = this.id? '/' + this.id : ''
        axios({
            url: `${process.env.VUE_APP_API_URL}/employees${urlId}`,
            data: {
              first_name: this.first_name,
              last_name: this.last_name,
              email: this.email,
              phone: this.phone,
              company: this.company,
            },
            method: this.id? 'PUT' : 'POST',
          })
          .then((resp) => {
            this.loading = false;
            this.$router.push('/employees')
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
        axios({
            url: `${process.env.VUE_APP_API_URL}/employees/${this.id}`,
            method: 'GET',
          })
          .then((resp) => {
            resp = resp.data;
            this.loading = false;
            this.first_name = resp.first_name;
            this.last_name = resp.last_name;
            this.email = resp.email;
            this.phone = resp.phone;
            this.company = resp.company;
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
