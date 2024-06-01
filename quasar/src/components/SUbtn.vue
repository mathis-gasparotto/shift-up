<template>
  <q-btn :label="label" :icon="icon" :color="color !== 'gradient' ? color : 'white'" :to="to" :rounded="rounded"
    :outline="outline" flat :class="'btn-' + color" class="q-py-sm" no-caps :type="type" :loading="loading"
    :text-color="textColor ? textColor : undefined" />
</template>

<script>
export default {
  name: 'SUbtn',
  props: {
    label: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: undefined
    },
    color: {
      type: String,
      default: 'gradient'
    },
    to: {
      type: [String, Object],
      default: null
    },
    rounded: {
      type: Boolean,
      default: false
    },
    outline: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'button'
    },
    loading: {
      type: Boolean,
      default: false
    },
    textColor: {
      type: String,
      default: null
    }
  }
}
</script>

<style lang="scss" scoped>
.btn {
  &-gradient {
    background: linear-gradient(97.77deg, $primary -1.13%, $secondary 110.54%) !important;
  }

  &-grey {
    background-color: $grey !important;
    color: #fff !important;

    &-light {
      background-color: $grey-light;
      color: #505059 !important;
    }
  }
}
</style>
