<template>
  <div v-loading.fullscreen.lock="loading">
    <div class="main">
      <select-date
        v-if="currentPage === 1"
        :datetime="datetime"
        @set-datetime="setDatetime"
      />
      <select-facility
        v-if="currentPage === 2"
        :facilities="facilities"
        :selectedIds="selectedIds"
        @select="select"
      />
    </div>
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
import SelectDate from '../select-date/select-date.vue'
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
      loading: true,
      lineId: '', // 個人ではなくgroupID or roomID
      currentPage: 1,
      datetime: new Date(
        new Date(new Date().setDate(new Date().getDate() + 1)).setHours(11, 0)
      ),
      selectedIds: []
    }
  },
  components: {
    SelectDate,
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
        this.$confirm(
          '入力された情報を元にコースを生成します。よろしいですか？',
          '確認',
          {
            confirmButtonText: 'はい',
            cancelButtonText: 'キャンセル',
            type: 'warning',
            customClass: 'small-confirm'
          }
        ).then(() => {
          if (this.lineId !== '') {
            liff.closeWindow()
          }
        })
        return
      }
      this.currentPage += 1
    },
    setDatetime(datetime) {
      this.datetime = datetime
    },
    setLINEData() {
      const context = liff.getContext()
      if (context && context.type !== 'none') {
        this.lineId = context.roomId ?? context.groupId ?? ''
      }
    }
  },
  props: {
    limit: Number
  },
  computed: {
    facilities() {
      return [1, 2, 3, 4, 5, 6, 7].map(id => {
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
  },
  created() {
    liff
      .init({
        liffId: '1653895916-Q4beDgJp'
      })
      .then(() => {
        // start to use LIFF's api
        this.loading = false
        this.setLINEData()
      })
    document.addEventListener(
      'touchstart',
      event => {
        if (event.touches.length > 1) {
          event.preventDefault()
        }
      },
      true
    )
  }
}
</script>

<style lang="scss" scoped>
.main {
  overflow-y: scroll;
  padding-bottom: 64px;
  -ms-overflow-style: none;
  scrollbar-width: none;
  -webkit-overflow-scrolling: touch;
}
</style>
