<template>
<header>

  <nav class="navbar navbar-expand-lg navbar-light" style="background:#fff;border-bottom:1px solid #eee;">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item links">
        <router-link class="nav-link" style="font-size:1.3rem;" :to="{ name: 'home' }">M4C DevTest</router-link>
      </li>
    </ul>

    <span v-if="$store.getters.authStatus === 'loading'">
      <div class="lds-ellipsis ml-auto">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </span>

    <div v-else class="fragment ml-auto">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav ml-auto">
          <li class="nav-item links">
            <router-link class="nav-link" v-if="!$store.getters.isLoggedIn && curpage !== '/login'" :to="{ name: 'login' }">Login</router-link>
          </li>
          <li class="nav-item links">
            <router-link class="nav-link" v-if="$store.getters.isLoggedIn && $store.getters.user.role === 'Admin'" :to="{ name: 'users' }">Users</router-link>
          </li>
          <li class="nav-item links">
            <router-link class="nav-link" v-if="$store.getters.isLoggedIn" :to="{ name: 'companies' }">Companies</router-link>
          </li>
          <li class="nav-item links">
            <router-link class="nav-link" v-if="$store.getters.isLoggedIn" :to="{ name: 'employees' }">Employees</router-link>
          </li>
        </ul>

        <span v-if="$store.getters.isLoggedIn" style="display:inline-block;height:36px;border-right:1px solid #ddd;">&nbsp;</span>

        <span class="linkspan" v-if="$store.getters.isLoggedIn">Logged in as: {{$store.getters.user.name}}</span>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item links"><a class="nav-link" href="#" v-if="$store.getters.isLoggedIn" @click="logout($event)">Logout</a></li>
        </ul>

      </div>

    </div>

  </nav>

</header>
</template>

<script>
export default {
  data() {
    return {
      curpage: '',
    };
  },
  created() {
    this.curpage = window.location.pathname;
  },
  methods: {
    logout(e) {
      e.preventDefault();
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/login').catch((err) => {});
          window.location.reload();
        })
        .catch(err => this.$store.dispatch('apiError', err));
    },
  },
};
</script>
