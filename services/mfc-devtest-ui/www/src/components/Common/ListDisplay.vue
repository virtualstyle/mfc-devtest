<template>
<div class="fragment">

  <div class="container d-flex flex-wrap">

    <div v-for="item in items" class="card mb-3">

      <div class="align-row row no-gutters">

        <slot name="listData" :item="item"></slot>
        <slot name="listActions" :item="item"></slot>

      </div>

    </div>

  </div>

  <Paginator v-if="pages > 1" :total="total" :perPage="perPage" :getPage="getPage" :pages="pages" :pageNumber="pageNumber" />

</div>
</template>

<script>
import Paginator from './Paginator';

export default {
  components: {
    Paginator,
  },
  props: {
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
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
      default: 8,
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
