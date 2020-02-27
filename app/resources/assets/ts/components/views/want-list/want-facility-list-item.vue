<template>
  <div class="list-row">
    <div class="name">{{name}}</div>
    <div>
      <el-button type="danger" icon="el-icon-delete" size="small" circle @click="trash" />
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  name: 'want-facility-list-item',
  props: {
    id: {
      type: Number,
      required: true
    },
    name: {
      type: String,
      required: true
    }
  },
  methods: {
    trash() {
      this.$emit('trash', this.id)
    }
  }
})
</script>

<style lang="scss" scoped>
.list-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #fff;
  padding: 8px;

  .name {
    font-size: 16px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
}
</style>
