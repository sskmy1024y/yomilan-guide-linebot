<template>
  <facility-card />
</template>

<script>
import FacilityCard from '../../components/views/card/facility-card.vue'
export default {
  name: 'liff-body',
  components: {
    FacilityCard
  },
  props: {
    limit: Number
  }
}
</script>
