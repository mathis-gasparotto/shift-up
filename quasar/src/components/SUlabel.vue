<template>
  <label
    :for="name"
    class="text-weight-medium"
    :class="{ 'label-required': required }"
    >{{ label }}</label
  >
</template>

<script>
export default {
  name: 'SUlabel',
  props: {
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    required: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss" scoped></style>
