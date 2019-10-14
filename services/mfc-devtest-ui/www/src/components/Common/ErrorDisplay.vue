<template>
<div class="container">
  <h1 class="h3 mb-3 font-weight-normal text-danger">Application Error</h1>
  <p>{{error}}</p>
</div>
</template>

<script>
export default {
  props: {
    error: {
      value: [Object, Boolean],
      required: true,
      default: false,
    },
  },
};
</script>
