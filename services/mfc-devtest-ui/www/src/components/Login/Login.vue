<template>
<div v-if="$store.getters.authStatus !== 'loading'" class="wrapper">
  <div v-if="$store.getters.authStatus === ''">
    <LoginForm />
  </div>

  <div v-if="$store.getters.authStatus === 'error'">
    <h6 class="text-danger">Error: Unknown username or password</h6>
    <LoginForm />
  </div>

  <ErrorDisplay v-else-if="$store.getters.authStatus === 'api_error'" :error="$store.getters.err" />

</div>

<LoadingDisplay v-else />
</template>

<script>
import LoginForm from './LoginForm';
import ErrorDisplay from '../Common/ErrorDisplay';
import LoadingDisplay from '../Common/LoadingDisplay';

export default {
  components: {
    ErrorDisplay,
    LoadingDisplay,
    LoginForm,
  },
};
</script>
