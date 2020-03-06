<template>
  <div class="wrapper">
    <h2>
      チェックリスト
      <el-badge v-if="items.length > 0" class="mark" :value="items.length" />
    </h2>
    <div v-if="items.length > 0" class="list-container">
      <want-facility-list-item
        v-for="(facility, index) in items"
        :key="index"
        :id="facility.id"
        :name="facility.name"
        @trash="trash"
      />
    </div>
    <div v-else class="list-container dummy">
      <i class="el-icon-close" />
      <div>リストが空です</div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import WantFacilityListItem from './want-facility-list-item.vue'

export default Vue.extend({
  name: 'want-facility-list',
  components: {
    WantFacilityListItem
  },
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  methods: {
    trash(id: Number) {
      this.$emit('set-select', id, false)
    }
  }
})
</script>

<style lang="scss" scoped>
.wrapper {
  margin: 20px 10px;
}

h2 {
  margin: 0;
  padding-bottom: 4px;
  font-size: 18px;
  font-weight: 400;
}

.list-container {
  min-height: 20px;
  border-radius: 4px;

  > *:first-child {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
  }
  > *:last-child {
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
  }
}

.dummy {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: rgba(0, 0, 0, 0.48);
  border: 1px solid #ebebeb;
  background-color: rgba(0, 0, 0, 0.16);

  .el-icon-close {
    margin: 10px;
    font-size: 25px;
  }

  div {
    font-size: 300;
    font-size: 14px;
    padding-bottom: 10px;
  }
}
</style>
