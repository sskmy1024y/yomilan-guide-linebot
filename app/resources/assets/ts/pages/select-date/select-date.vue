<template>
  <div>
    <div class="title-container">
      <h1>日時を決めよう</h1>
      <div class="description">よみうりランドに行く日を決めてね</div>
      <div class="container">
        <el-date-picker
          v-model="ownDatetime"
          type="datetime"
          placeholder="Select date and time"
          default-time="11:00"
          format="yyyy/MM/dd HH:mm"
        ></el-date-picker>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  name: 'select-date',
  props: {
    datetime: {
      type: Date,
      required: true
    }
  },
  computed: {
    ownDatetime: {
      set(value) {
        this.$emit('set-datetime', value)
      },
      get() {
        return this.datetime
      }
    }
  }
})
</script>


<style lang="scss" scoped>
.title-container {
  margin: 10px;
  h1 {
    font-size: 24px;
    font-weight: 00;
    padding: 4px 0;
    margin: 0;
  }
  .description {
    font-size: 12px;
    color: rgba(0, 0, 0, 0.64);
  }
  .container {
    margin: 8px 0;
  }
}
</style>
