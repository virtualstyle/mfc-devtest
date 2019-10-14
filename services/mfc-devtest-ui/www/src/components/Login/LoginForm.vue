<template>
<div class="container form-div">

  <fieldset>
    <legend>Please Sign In</legend>

    <form class="form-signin" @submit.prevent="login" style="width:320px;">
      <label for="inputEmail" class="sr-only">Email address</label>
      <input style="margin-top:0;" v-model="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input v-model="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required style="margin-top:12px;margin-bottom:12px;">
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
  </fieldset>

</div>
</template>

<script>
import {
  mapActions,
} from 'vuex';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    login() {
      const email = this.email;
      const password = this.password;
      this.$store.dispatch('login', {
        email,
        password,
      })
        .then(() => this.$router.push('/'))
        .catch((err) => {
          if (err.response.status === 403) {
            this.$store.dispatch('authError', err);
          } else {
            this.$store.dispatch('apiError', err);
          }
          localStorage.removeItem('token');
          localStorage.removeItem('user');
        });
    },
  },
  /* methods: {
    ...mapActions([
      'login'
    ]),
  } */
};
</script>
