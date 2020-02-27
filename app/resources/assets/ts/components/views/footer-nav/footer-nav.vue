<template>
  <div class="footer">
    <slot></slot>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  name: 'footer-nav'
})
</script>

<style lang="scss" scoped>
.footer {
  display: flex;
  position: fixed;
  bottom: 0;
  width: 100vw;
  height: 80px;

  border-top: 1px solid #adadad;
}
</style>
