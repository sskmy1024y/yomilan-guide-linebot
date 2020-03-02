<template>
  <div class="wrapper">
    <div class="title-container">
      <h1>日時と人数を決めよう</h1>
      <div class="description">よみうりランドに行く日と、人数を決めてね</div>
      <div class="container">
        <div>
          <h2>日時</h2>
          <el-date-picker
            v-model="ownDatetime"
            type="datetime"
            placeholder="Select date and time"
            default-time="11:00"
            format="yyyy/MM/dd HH:mm"
            :picker-options="{
              selectableRange: '11:00:00 - 17:30:00'
            }"
            :clearable="false"
          ></el-date-picker>
        </div>
        <div>
          <h2>人数</h2>
          <div class="slide-container">
            <el-slider v-model="ownPeople" show-input :show-tooltip="false" :min="1" :max="20"></el-slider>
          </div>
          <small>人数は目安で構いません。20人よりも多い場合は、20にしてください。</small>
        </div>
        <div>
          <h2>代表者</h2>
          <div class="profile-card">
            <el-avatar size="medium" :src="profile.pictureUrl"></el-avatar>
            <div>{{profile.displayName}}</div>
          </div>
          <small>代表者を変更する場合は、代表になる人のLINEから登録してください。</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  name: 'select-date',
  props: {
    data: {
      type: Object,
      required: true
    },
    profile: {
      type: Object,
      default: {
        displayName: '',
        pictureUrl: ''
      }
    }
  },
  computed: {
    ownDatetime: {
      set(value) {
        this.$emit('set-datetime', value)
      },
      get() {
        return this.data.datetime
      }
    },
    ownPeople: {
      set(value) {
        this.$emit('set-people', value)
      },
      get() {
        return this.data.people
      }
    }
  }
})
</script>

<style lang="scss" scoped>
.wrapper {
  display: flex;
  flex-direction: column;
  margin: 24px 10px;
}

.title-container {
  .description {
    font-size: 14px;
    color: rgba(0, 0, 0, 0.64);
  }
  .container {
    margin: 8px 0;
    padding: 16px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.64);
    > div + div {
      margin-top: 24px;
    }
  }
  .slide-container {
    margin-left: 10px;
  }
  .profile-card {
    display: flex;
    align-items: center;
    padding: 4px;
    border: 1px solid rgba(0, 0, 0, 0.16);
    border-radius: 4px;

    > * + * {
      margin-left: 8px;
    }
  }
}

h1 {
  font-size: 24px;
  font-weight: 600;
  padding: 4px 0;
  margin: 0;
}

h2 {
  font-size: 18px;
  font-weight: 500;
  padding: 4px 0;
  margin: 0;
}

small {
  font-size: 12px;
  color: rgba(0, 0, 0, 0.48);
}
</style>
