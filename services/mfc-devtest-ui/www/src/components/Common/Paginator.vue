<template>
<div id="paginator">
  <p>Page {{pageNumber}} of {{pages}}<br> Showing {{perPage * (pageNumber - 1) + 1}} through {{Math.min(perPage * pageNumber, total)}} of {{total}} items</p>

  <button type="button" :disabled="pageNumber === 1" @click="getPage(pageNumber - 1)" class="prev-btn btn btn-primary btn-sm">Previous</button>

  <button v-for="n in pages" type="button" :disabled="pageNumber === n" @click="getPage(n)" class="btn btn-primary btn-sm">{{n}}</button>

  <button type="button" :disabled="pageNumber >= pages" @click="getPage(pageNumber + 1)" class="next-btn btn btn-primary btn-sm">Next</button>

</div>
</template>

<script>
export default {
  props: {
    pages: {
      type: Number,
      required: true,
      default: 1,
    },
    pageNumber: {
      type: Number,
      required: true,
      default: 1,
    },
    perPage: {
      type: Number,
      required: true,
      default: 1,
    },
    total: {
      type: Number,
      required: true,
      default: 1,
    },
    getPage: {
      type: Function,
      required: true,
    },
  },
};
</script>
