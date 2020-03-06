<template>
  <div class="container">
    <el-button v-if="showPrev" class="prev" plain icon="el-icon-arrow-left" @click="onPrev">戻る</el-button>
    <el-button v-if="showNext && !done" class="next" type="primary" @click="onNext">
      次へ
      <i class="el-icon-arrow-right"></i>
    </el-button>
    <el-button v-else-if="done" class="next" type="success" icon="el-icon-check" @click="onNext">完了</el-button>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  name: 'select-footer',
  props: {
    showPrev: {
      type: Boolean,
      default: true
    },
    showNext: {
      type: Boolean,
      default: true
    },
    done: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    onNext() {
      this.$emit('next')
    },
    onPrev() {
      this.$emit('prev')
    }
  }
})
</script>

<style lang="scss" scoped>
.container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  margin: 0 10px;

  .prev {
    margin-right: auto;
  }

  .next {
    margin-left: auto;
  }
}
</style>
