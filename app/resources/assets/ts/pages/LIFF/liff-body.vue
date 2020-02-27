<template>
  <facilities-carousel :items="facilities" />
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
      facilities: [facility, facility, facility]
    }
  },
  components: {
    FacilitiesCarousel
  },
  props: {
    limit: Number
  }
}
</script>
