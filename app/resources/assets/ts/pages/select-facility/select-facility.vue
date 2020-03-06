<template>
  <div>
    <div class="title-container">
      <h1>おすすめアトラクション</h1>
      <div class="description">周りたいアトラクションを選ぶことで、コースを作るときに自動的に組み込まれるよ</div>
    </div>
    <facilities-carousel :items="facilities" :selected-ids="selectedIds" @set-select="select" />
    <want-facility-list :items="selectedList" @set-select="select" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import FacilitiesCarousel from '../../components/views/card/facilities-carousel.vue'
import WantFacilityList from '../../components/views/want-list/want-facility-list.vue'
import { scrollTo } from '../../utils/scroll'

export default Vue.extend({
  name: 'select-facility',
  components: {
    FacilitiesCarousel,
    WantFacilityList
  },
  props: {
    facilities: {
      type: Array,
      required: true
    },
    selectedIds: {
      type: Array,
      required: true
    }
  },
  methods: {
    select(id, select = undefined) {
      const scroll = document.getElementById('facility-carousel')
      if (select === undefined) {
        select = !this.selected(id)
      }
      this.$emit(
        'select',
        select
          ? [...this.selectedIds, id]
          : this.selectedIds.filter(_id => _id !== id)
      )
      if (scroll.scrollLeft < scroll.scrollWidth && select !== false) {
        scrollTo(
          document.getElementById('facility-carousel'),
          scroll.scrollLeft + scroll.scrollWidth / this.facilities.length - 8,
          300
        )
      }
    },
    selected(id) {
      return this.selectedIds.includes(id)
    }
  },
  computed: {
    selectedList() {
      return this.facilities.filter(facility => this.selected(facility.id))
    }
  }
})
</script>

<style lang="scss" scoped>
.title-container {
  margin: 10px;
  h1 {
    font-size: 24px;
    font-weight: 00;
    padding: 4px 0;
    margin: 0;
  }
  .description {
    font-size: 12px;
    color: rgba(0, 0, 0, 0.64);
  }
}
</style>
