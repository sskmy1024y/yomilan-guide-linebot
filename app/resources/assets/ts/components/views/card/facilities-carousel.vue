<template>
  <div id="facility-carousel" class="carousel">
    <facility-card
      v-for="(facility, index) in items"
      :key="index"
      :item="facility"
      :selected-ids="selectedIds"
      @set-select="select"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import FacilityCard from './facility-card.vue'

export default Vue.extend({
  name: 'facilities-carousel',
  components: {
    FacilityCard
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    selectedIds: {
      type: Array,
      required: true
    }
  },
  methods: {
    select(id: Number) {
      this.$emit('set-select', id)
    }
  }
})
</script>

<style lang="scss" scoped>
.carousel {
  display: flex;
  white-space: nowrap;
  overflow-x: auto;
  -ms-overflow-style: none;
  scrollbar-width: none;
  -webkit-overflow-scrolling: touch;

  &::-webkit-scrollbar {
    display: none;
  }

  > *:first-child {
    padding-left: calc((100vw - 275px) / 2);
  }
  > * + * {
    padding-left: 16px;
  }
  > *:last-child {
    padding-right: calc((100vw - 275px) / 2 - 16px);
  }
}
</style>
