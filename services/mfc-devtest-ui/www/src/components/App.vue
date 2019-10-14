<template>
<div>

  <div class="fragment">
    <Header />
  </div>

  <div class="content">

    <router-view v-if="!error" />

    <ErrorDisplay v-else :error="error" />

  </div>

</div>
</template>

<script>
import Header from './Common/Header';
import ErrorDisplay from './Common/ErrorDisplay';

export default {
  components: {
    Header,
    ErrorDisplay
  },
  data() {
    return {
      error: false,
    };
  },
  created() {
    const store = this.$store;
    this.$http.interceptors.response.use(undefined, err => new Promise(function (resolve, reject) {
      console.log('EXPIRED TOKEN CHECKs', err);
      if (err.response && err.response.status === 401 && err.response.config && !err.response.config.__isRetryRequest) {
        store.dispatch('invalidateSession')
          .then(() => {
            this.$router.push('/login').catch((err) => {});
            window.location.reload();
          })
          .catch(err => store.dispatch('apiError', err));
      } else {
        throw err;
      }
    }));
  },

  errorCaptured(err, vm, info) {
    this.error = err;
    this.$store.dispatch('appError', err);
    return false;
    // err: error trace
    // vm: component in which error occured
    // info: Vue specific error information such as lifecycle hooks, events etc.
    // TODO: Perform any custom logic or log to server
    // return false to stop the propagation of errors further to parent or global error handler
  },
};
</script>

<style lang="scss">
@import "bootstrap/scss/functions";
@import "bootstrap/scss/variables";

.wrapper {
    margin-top: 22vh !important;
}

.form-div {
    margin: auto;
}

.form-div fieldset {
    border-radius: 6px;
    border: 1px solid lighten($primary, 40%);
    padding: 3px 24px 18px 24px;
    width: fit-content;
    margin: auto;
}

.form-div fieldset legend {
    font-size: 1.15rem;
    color: $primary;
    background: transparent;
    width: auto;
}

.form-div input {
    margin-top: 12px;
    margin-bottom: 12px;
}

.fragment {
    margin: 0;
    padding: 0;
}

header {
    margin-bottom: 24px;
    box-shadow: none;
}

#paginator button.btn {
    border-radius: 0;
    border: 1px solid lighten($link-hover-color, 20%);
}

#paginator button.prev-btn {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}
#paginator button.next-btn {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

.icon {
    width: 24px;
    height: 24px;
    margin-top: 4px;
    margin-right: -12px;
    cursor: pointer;
    fill: $primary;
}

.icon:hover {
    fill: $link-hover-color;
    filter: drop-shadow(0px 1px 1px #efefef);
}

.trash-icon {
    margin-top: 8px;
    margin-right: -6px;
}

.users-icon {
    margin-top: 6px;
    margin-right: -6px;
}

.img-col {
    width: 100px;
    height: 100px;
}

.img {
    width: 100px;
    height: 100px;
    background: #efefef;
    padding: 0;
}

.card-img {
    width: 75px;
    height: 100px;
}

.card-body {
    padding: 12px !important;
}

.card {
    position: relative;
    margin: auto;
    width: 440px;
    height: 100px;
}

.edit-btn {
    height: 100px;
}

.card-text,
.card-title {
    margin: 0 0 0 !important;
    color: #212529 !important;
}

.linkspan {
    color: #a0abb2;
    padding: 0 18px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.1rem;
    text-decoration: none;
    text-transform: uppercase;
}

body {
    color: #212529 !important;
}

.lds-ellipsis {
    display: inline-block;
    position: relative;
    width: 64px;
    height: 1em;
}
.lds-ellipsis div {
    position: absolute;
    top: 6px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #333;
    animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
    left: 6px;
    animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
    left: 6px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
    left: 26px;
    animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
    left: 45px;
    animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
}
@keyframes lds-ellipsis3 {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(0);
    }
}
@keyframes lds-ellipsis2 {
    0% {
        transform: translate(0, 0);
    }
    100% {
        transform: translate(19px, 0);
    }
}

#loader {
    margin: 30vh auto auto;
    text-align: center;
    width: 360px;
    height: 120px;
    padding: 0;
}
#loader:before {
    content: "";
    border-top: 11px solid $primary;
    border-right: 11px solid $primary;
    border-bottom: 11px solid $primary;
    border-left: 11px solid lighten($primary, 20%);
    animation: load 1.1s infinite linear;
    display: inline-block;
    border-radius: 50%;
    width: 50px;
    height: 50px;
}

#inputLogo #loader {
  margin-top: 48px;
}

@-webkit-keyframes load {
    0% {
        //Instead of the line below you could use @include transform($scale, $rotate, $transx, $transy, $skewx, $skewy, $originx, $originy)
        transform: rotate(0deg);
    }
    100% {
        //Instead of the line below you could use @include transform($scale, $rotate, $transx, $transy, $skewx, $skewy, $originx, $originy)
        transform: rotate(360deg);
    }
}
@keyframes load {
    0% {
        //Instead of the line below you could use @include transform($scale, $rotate, $transx, $transy, $skewx, $skewy, $originx, $originy)
        transform: rotate(0deg);
    }
    100% {
        //Instead of the line below you could use @include transform($scale, $rotate, $transx, $transy, $skewx, $skewy, $originx, $originy)
        transform: rotate(360deg);
    }
}

.wrapper {
    margin: 0;
    padding: 0;
}
</style>
