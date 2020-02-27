<template>
  <el-card :body-style="{ padding: '0px' }" :shadow="'always'">
    <div class="thumbnail">
      <img
        src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png"
        class="image"
      />
    </div>
    <div style="padding: 14px;">
      <h3>バンデット</h3>
      <div>よみうりランド不動の人気No.1コースター</div>
      <div class="select-wrapper">
        <el-checkbox :model="check" label="GO!" border />
      </div>
    </div>
  </el-card>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  name: 'facility-card',
  data() {
    return {
      check: false
    }
  },
  props: {
    model: Boolean
  },
  created() {
    console.log(this.check)
  }
})
</script>

<style lang="scss" scope>
h3 {
  margin: 4px 0;
}

.time {
  font-size: 13px;
  color: #999;
}

.select-wrapper {
  display: flex;
  justify-content: flex-start;
  margin: 10px 0;
}

.image {
  width: 100%;
  display: block;
}
</style>
