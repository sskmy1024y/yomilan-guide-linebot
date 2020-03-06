<template>
  <div v-loading.fullscreen.lock="loading">
    <div class="main">
      <select-date
        v-if="currentPage === 1"
        :data="data"
        :profile="profile"
        @set-datetime="setDatetime"
        @set-people="setPeople"
      />
      <select-facility
        v-if="currentPage === 2"
        :facilities="facilities"
        :selectedIds="data.selectedIds"
        @select="select"
      />
    </div>
    {{ data.groupId }}
    <footer-nav>
      <select-footer
        :showPrev="currentPage > 1"
        :showNext="data.groupId !== ''"
        :done="currentPage === 2"
        @prev="onPrevPage"
        @next="onNextPage"
      />
    </footer-nav>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import SelectDate from '../select-date/select-date.vue'
import SelectFooter from '../select-facility/select-footer.vue'
import SelectFacility from '../select-facility/select-facility.vue'
import FooterNav from '../../components/views/footer-nav/footer-nav.vue'
import { formatDate } from '../../utils/datetime'
import LIFF from 'liff-type'

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
  name: 'liff-body',
  data() {
    return {
      data: {
        groupId: '', // 個人ではなくgroupID or roomID
        datetime: new Date(
          new Date(new Date().setDate(new Date().getDate() + 1)).setHours(11, 0)
        ),
        people: 2,
        selectedIds: []
      },
      profile: {
        displayName: '',
        pictureUrl: ''
      },
      loading: true,
      currentPage: 1
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
      this.data.selectedIds = ids
    },
    selected(id) {
      return this.data.selectedIds.includes(id)
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
          if (this.data.groupId !== '') {
            fetch(
              `https://${location.hostname}:${location.port}/api/linebot/postback`,
              {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({
                  ...this.data,
                  datetime: formatDate(this.data.datetime)
                })
              }
            ).then(() => {
              liff.closeWindow()
            })
          }
        })
        return
      }
      this.currentPage += 1
    },
    setDatetime(datetime) {
      this.data.datetime = datetime
    },
    setPeople(num) {
      this.data.people = num
    },
    setLINEData() {
      const context = liff.getContext()
      if (context && context.type !== 'none') {
        this.data.groupId = context.roomId ?? context.groupId ?? context.userId ?? ''
      }
      liff
        .getProfile()
        .then(({ displayName, pictureUrl }) => {
          this.profile = {
            displayName,
            pictureUrl
          }
        })
        .catch(e => {
          this.profile.displayName = 'この端末からは登録できません'
        })
    }
  },
  props: {
    limit: Number,
    facilities: {
      type: Array,
      required: true
    }
  },
  computed: {
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
})
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
