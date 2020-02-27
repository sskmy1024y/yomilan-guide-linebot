<template>
  <div class="facility-card">
    <el-card :body-style="{ padding: '0px' }" :shadow="'always'">
      <div class="thumbnail">
        <img
          src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"
          class="image"
        />
      </div>
      <div class="detail-container">
        <h3>{{ name }}</h3>
        <div>{{ description }}</div>
        <div class="select-wrapper">
          <div class="rate-container">
            <el-rate
              v-model="rate"
              disabled
              show-score
              text-color="#ff9900"
              score-template="人気度 {value}"
            />
          </div>
          <el-checkbox v-model="check" label="行きたい!" border />
        </div>
      </div>
    </el-card>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  name: 'facility-card',
  data() {
    return {
      check: false,
      rate: 5
    }
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  computed: {
    name(): String {
      return this.item.name
    },
    description(): String {
      return this.item.description
    }
  }
})
</script>

<style lang="scss" scope>
h3 {
  margin: 4px 0;
}

.facility-card {
  white-space: normal;
  word-wrap: break-word;

  .thumbnail {
    display: block;
    width: 100%;
    height: 180px;

    .image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .detail-container {
    width: 275px;
    padding: 14px;
    box-sizing: border-box;

    .select-wrapper {
      display: flex;
      justify-content: space-between;
      margin: 10px 0;
      .rate-container {
        display: flex;
        padding-right: 8px;
        white-space: initial;
      }
    }
  }
}
</style>

<style lang="scss">
.facility-card {
  .el-card {
    border-radius: 8px;
  }
}
</style>
