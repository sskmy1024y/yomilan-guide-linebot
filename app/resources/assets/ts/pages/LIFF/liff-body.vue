<template>
  <div>
    <facilities-carousel :items="facilities" @set-select="select" />
    <div>{{selectedIds}}</div>
  </div>
</template>

<script>
import FacilitiesCarousel from '../../components/views/card/facilities-carousel.vue'

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

export default {
  name: 'liff-body',
  data() {
    return {
      selectedIds: []
    }
  },
  components: {
    FacilitiesCarousel
  },
  methods: {
    select(id, selected = undefined) {
      const scroll = document.getElementById('facility-carousel')
      if (selected === undefined) {
        selected = this.selectedIds.includes(id)
      }
      this.selectedIds = selected
        ? this.selectedIds.filter(_id => _id !== id)
        : [...this.selectedIds, id]
      if (scroll.scrollLeft < scroll.scrollWidth) {
        document.getElementById('facility-carousel').scrollLeft +=
          scroll.scrollWidth / this.facilities.length - 16
      }
    }
  },
  props: {
    limit: Number
  },
  computed: {
    facilities() {
      return [1, 2, 3].map(id => {
        const selected = this.selectedIds.includes(id)
        return {
          ...facility,
          id,
          selected
        }
      })
    }
  }
}
</script>
