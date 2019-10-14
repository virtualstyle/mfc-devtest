<template>
<div class="fragment">

  <div v-if="!loading && !error" class="container form-div">

    <fieldset>
      <legend v-if="!id">Create New Company</legend>
      <legend v-else>Edit Company</legend>

      <form class="form-company" @submit.prevent="saveCompany" style="width:480px">
        <label for="inputName" class="sr-only">Company Name</label>
        <input style="margin-top:0;" v-model="name" type="text" id="inputName" class="form-control" placeholder="Company name" required autofocus>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input v-model="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputWebsite" class="sr-only">Website</label>
        <input v-model="website" type="text" id="inputWebsite" class="form-control" placeholder="Website" required autofocus>

        <div v-if="id && oldfilename">
          <div class="img" style="margin:24px auto;">
            <img :src="oldfilename" class="card-img" :alt="name">
          </div>
        </div>

        <label for="inputLogo"  class="sr-only">Company Logo Image</label>
        <Uploader :clearOldFile="clearOldFile" :isEdit ="!!id" :oldfilename="filename"  :setFilename="setFilename" :setUploading="setUploading" id="inputLogo" ref="uploader" />

        <button v-if="!uploading" class="btn btn-lg btn-primary btn-block" type="submit">Save Company</button>
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
import Uploader from '../Common/Uploader';

export default {
  components: {
    ErrorDisplay,
    LoadingDisplay,
    Uploader
  },
  data() {
    return {
      company: {},
      loading: false,
      error: false,
      curpage: '',
      name: '',
      email: '',
      website: '',
      uploading: false,
      filename: '',
      oldfilename: ''
    };
  },
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    document.addEventListener('beforeunload', this.clearUpload);
    if (this.id) {
      this.fetchData();
    }
  },
  props: {
    id: {
      type: Number,
      required: false,
      default: null,
    },
  },
  methods: {

    setFilename(filename) {
      this.filename = filename;
    },

    setUploading(uploading) {
      this.uploading = uploading;
    },

    clearOldFile() {
      this.oldfilename = '';
    },

    saveCompany() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        var urlId = this.id? '/' + this.id : ''
        axios({
            url: `${process.env.VUE_APP_API_URL}/companies${urlId}`,
            data: {
              name: this.name,
              email: this.email,
              website: this.website,
              logo: this.filename,
            },
            method: this.id? 'PUT' : 'POST',
          })
          .then((resp) => {
            this.loading = false;
            this.$router.push('/companies')
            resolve(resp);
          })
          .catch((err) => {
            this.error = err.toString();
            reject(err);
          });
      });
    },

    clearUpload() {
      this.$refs.uploader.clearUpload();
    },

    fetchData() {
      return new Promise((resolve, reject) => {
        this.loading = true;
        axios({
            url: `${process.env.VUE_APP_API_URL}/companies/${this.id}`,
            method: 'GET',
          })
          .then((resp) => {
            resp = resp.data;
            this.loading = false;
            this.name = resp.name;
            this.email = resp.email;
            this.website = resp.website;
            this.filename = resp.logo;
            this.oldfilename = resp.logo;
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
