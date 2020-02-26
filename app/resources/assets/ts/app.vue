<template>
  <div id="spa-contents"><page-header title="hoge" />{{ 'aaaa' }}</div>
</template>

<script>
import PageHeader from './components/base/header/page-header.vue'

export default {
  name: 'app',
  components: {
    PageHeader
  }
}
</script>
