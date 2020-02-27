<template>
  <div>
    <select-facility
      v-if="currentPage === 2"
      :facilities="facilities"
      :selectedIds="selectedIds"
      @select="select"
    />
    <footer-nav>
      <select-footer
        :showPrev="currentPage > 1"
        :done="currentPage === 2"
        @prev="onPrevPage"
        @next="onNextPage"
      />
    </footer-nav>
  </div>
</template>

<script>
import SelectFooter from '../select-facility/select-footer.vue'
import SelectFacility from '../select-facility/select-facility.vue'
import FooterNav from '../../components/views/footer-nav/footer-nav.vue'

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
      currentPage: 1,
      selectedIds: []
    }
  },
  components: {
    SelectFooter,
    SelectFacility,
    FooterNav
  },
  methods: {
    select(ids) {
      this.selectedIds = ids
    },
    selected(id) {
      return this.selectedIds.includes(id)
    },
    onPrevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1
      }
    },
    onNextPage() {
      if (this.currentPage === 2) {
        // TODO: 登録作業 & LIFFを閉じる
        return
      }
      this.currentPage += 1
    }
  },
  props: {
    limit: Number
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
}
</script>
