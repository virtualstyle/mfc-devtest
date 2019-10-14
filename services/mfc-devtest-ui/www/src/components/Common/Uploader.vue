<template>
  <div v-if="!error" id="uploader-div" style="margin-bottom:24px;">
    <div class="fragment">
      <vue-dropzone  ref="dropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-complete="afterComplete" @vdropzone-file-added="dropStart" @vdropzone-success="uploadSuccess" @vdropzone-error="uploadError"></vue-dropzone>

      <div v-if="uploading" style="padding-top:24px;"">
        <div class="lds-ellipsis ml-auto">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        Uploading logo, please wait...
      </div>

      <button v-if="file" type="button" class="btn btn-lg btn-primary btn-block" @click="removeAllFiles">Remove File</button>
    </div>

  </div>

  <ErrorDisplay v-else :error="error" />

</template>
<script>
import axios from 'axios';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import ErrorDisplay from '../Common/ErrorDisplay';
export default {
  name: 'app',
  components: {
    vueDropzone: vue2Dropzone,
    ErrorDisplay
  },
  data: function () {
    return {
      dropzoneOptions: {
          url: `${process.env.VUE_APP_API_URL}/upload`,
          maxFilesize: 2, // MB
          maxFiles: 1,
          headers: { "Authorization": localStorage.getItem('token') },
          dictDefaultMessage: "Drag file or click here to upload logo",
          resizeWidth: 100,
          resizeHeight: 100,
          acceptedFiles: 'image/*'
      },
      file: false,
      filename: '',
      error: false,
      loading: false,
      uploading: false,
    }
  },
  props: {
    setFilename: {
      type: Function,
      required: true,
    },
    setUploading: {
      type: Function,
      required: true,
    },
    clearOldFile: {
      type: Function,
      required: true,
    },
    isEdit: {
      type: Boolean,
      required: true,
      default: false
    },
    oldfilename: {
      type: String,
      required: false,
      default: ''
    }
  },
  methods: {

    dropStart() {
      this.uploading = true;
      this.setUploading(true);
    },

    clearUpload(clearfile, old) {
      this.uploading = true;
      this.setUploading(true);
      axios({
          url: `${process.env.VUE_APP_API_URL}/upload/${clearfile}`,
          method: 'DELETE',
        })
        .then((resp) => {
          if(!old) {
            this.file = false;
          }
          this.uploading = false;
          this.setUploading(false);
        })
        .catch((err) => {
          this.error = err.toString();
        });
    },
    removeAllFiles() {
      this.$refs.dropzone.removeAllFiles();
      this.clearUpload('temp|' + this.filename);
    },
    uploadSuccess(file, response) {
      this.uploading = false;
      this.setUploading(false);
      if(this.isEdit) {
        this.clearUpload('logos|' + this.oldfilename.substring(this.oldfilename.lastIndexOf('/')+1), true);
        this.oldfilename = '';
        this.clearOldFile();
      }
      this.filename = response.filename;
      this.setFilename(this.filename);
    },
    uploadError(file, message, xhr) {
      this.uploading = false;
      this.setUploading(false);
      if(message.error && message.error.status_code === 401) {
        this.$store.dispatch('invalidateSession')
        .then(() => {
          this.$router.push('/login').catch((err) => {});
          window.location.reload();
        })
        .catch(err => this.$store.dispatch('apiError', err));
      }
    },
    afterComplete(file) {
      this.uploading = false;
      this.setUploading(false);
      this.file = true;
    }
  }
}
</script>
