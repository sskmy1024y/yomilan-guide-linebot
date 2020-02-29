<template>
  <div class="facility-card">
    <el-card :body-style="{ padding: '0px' }" :shadow="'always'">
      <div class="thumbnail">
        <img :src="imagePath" class="image" />
      </div>
      <div class="detail-container">
        <h3>{{ name }}</h3>
        <text-elipsis :line-height="20" :line-limit="2">{{ description }}</text-elipsis>
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
          <el-checkbox v-model="selected" label="行きたい!" border />
        </div>
      </div>
    </el-card>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import TextElipsis from '../../base/text-elipsis/text-elipsis.vue'

export default Vue.extend({
  name: 'facility-card',
  data() {
    return {
      rate: 5
    }
  },
  components: {
    TextElipsis
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
    },
    imagePath(): String {
      return (
        this.item.image_path ??
        'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png'
      )
    },
    selected: {
      set(value) {
        this.$emit('set-select', this.item.id, value)
      },
      get() {
        return this.item.selected
      }
    }
  }
})
</script>

<style lang="scss" scope>
h3 {
  margin: 0;
  font-size: 18px;
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
    display: flex;
    width: 275px;
    min-height: 147px;
    padding: 10px;
    font-size: 14px;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;

    .select-wrapper {
      display: flex;
      justify-content: space-between;
      align-self: flex-end;
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
