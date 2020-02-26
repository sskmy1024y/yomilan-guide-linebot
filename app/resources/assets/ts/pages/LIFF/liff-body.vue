<template>
  <div>あああああああああああああああ {{ limit }}</div>
</template>

<script>
export default {
  name: "liff-body",
  props: {
    limit: Number
  },
  created() {
    console.log(this.limit);
  }
};
</script>
