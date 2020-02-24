<template>
    <div class="heading">
        <div class="title">{{ title }}</div>
    </div>
</template>

<script lang="ts">
export default {
    name: "page-header",
    props: {
        title: {
            type: String,
            required: true
        }
    }
};
</script>

<style scoped lang="scss">
.heading {
    display: flex;
    align-items: center;
    padding: 12px 16px;

    &.title {
        padding-top: 14px;
    }
}
</style>
