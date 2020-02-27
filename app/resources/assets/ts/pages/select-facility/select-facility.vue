<template>
  <div>
    <div class="title-container">
      <h1>おすすめアトラクション</h1>
      <div class="description">周りたいアトラクションを選ぶことで、コースを作るときに自動的に組み込まれるよ</div>
    </div>
    <facilities-carousel :items="facilities" @set-select="select" />
    <want-facility-list :items="selectedList" @set-select="select" />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import FacilitiesCarousel from '../../components/views/card/facilities-carousel.vue'
import WantFacilityList from '../../components/views/want-list/want-facility-list.vue'

const facility = {
  name: 'バンデット',
  description: 'よみうりランド不動の人気No.1コースター',
  price: 1200,
  image_path: null,
  area_id: 6,
  type: 'attraction',
  latitude: 35.6257,
  longitude: 139.5181,
  use_pass: true,
  for_child: false,
  is_indoor: false,
  capacity: '28人乗り',
  age_limit: '小学生以上',
  physical_limit: '身長120cm以上',
  require_time: 10,
  enable: true,
  url: 'http://www.yomiuriland.com/attraction/bandit.html'
}

export default Vue.extend({
  name: 'select-facility',
  data() {
    return {
      selectedIds: []
    }
  },
  components: {
    FacilitiesCarousel,
    WantFacilityList
  },
  methods: {
    select(id, select = undefined) {
      const scroll = document.getElementById('facility-carousel')
      if (select === undefined) {
        select = !this.selected(id)
      }
      this.selectedIds = select
        ? [...this.selectedIds, id]
        : this.selectedIds.filter(_id => _id !== id)
      if (scroll.scrollLeft < scroll.scrollWidth) {
        document.getElementById('facility-carousel').scrollLeft +=
          scroll.scrollWidth / this.facilities.length - 16
      }
    },
    selected(id) {
      return this.selectedIds.includes(id)
    }
  },
  computed: {
    facilities() {
      return [1, 2, 3].map(id => {
        const selected = this.selected(id)
        return {
          ...facility,
          id,
          selected
        }
      })
    },
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
