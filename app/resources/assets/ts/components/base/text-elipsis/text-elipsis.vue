<template>
  <div class="elipsis" :style="style">
    <slot></slot>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  name: 'text-elipsis',
  props: {
    lineLimit: {
      type: Number,
      required: true
    },
    lineHeight: {
      type: Number,
      required: true
    }
  },
  computed: {
    style() {
      return {
        '--line-height': this.lineHeight,
        '--line-limit': this.lineLimit,
        '--max-height': `${this.lineHeight * this.lineLimit}px`
      }
    }
  },
  created() {
    console.log(this.lineLimit)
  }
})
</script>

<style lang="scss" scoped>
.elipsis {
  display: --webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  -webkit-line-clamp: var(--line-limit);
  // Fallback for -webkit-line-clamp
  max-height: var(--max-height);
  line-height: var(--line-height) px;
  // For english
  overflow-wrap: break-word;
}
</style>
