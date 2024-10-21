<template>
  <h1
    class="text-h2 q-my-none flex items-center"
    :class="{ 'cursor-loading': editLoading }"
  >
    <q-skeleton
      v-if="loading || !title"
      width="150px"
    />
    <span
      v-else
      @click.prevent="handleTitleClick"
      :contenteditable="editMode && !editLoading"
      ref="titleSpan"
      class="q-px-xs title"
      :class="{ 'text-grey': editLoading }"
      @mouseover="hover = true"
      @mouseleave="hover = false"
      @keypress="handleKeyPressTitle"
    >
      {{ title }}
    </span>
    <q-icon
      v-if="!editMode && !loading && hover"
      class="q-ml-xs"
      name="edit"
      size="15px"
    />
    <q-icon
      v-else-if="editMode && !editLoading"
      @click="saveTitle"
      class="q-ml-xs cursor-pointer"
      name="save"
      size="20px"
    />
    <q-spinner
      v-else-if="editMode"
      class="q-ml-xs"
      size="20px"
    />
  </h1>
</template>

<script>
export default {
  name: 'SUEntityTitle',
  emits: ['edit'],
  props: {
    title: {
      type: String
    },
    loading: {
      type: Boolean,
      default: false
    },
    editLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      lastClick: null,
      editMode: false,
      hover: false
    }
  },
  methods: {
    handleTitleClick() {
      // if double click
      if (this.lastClick && new Date() - this.lastClick < 400) {
        this.editMode = true
      } else {
        this.lastClick = new Date()
      }
    },
    saveTitle() {
      if (!this.editMode) return
      const value = this.$refs.titleSpan.innerText
      this.$emit('edit', value)
    },
    handleKeyPressTitle(event) {
      if (event.key === 'Enter') {
        event.preventDefault()
        this.saveTitle()
      }
    },
    reset() {
      this.editMode = false
      this.$refs.titleSpan.innerText = this.title
    }
  }
}
</script>

<style lang="scss" scoped>
.title {
  user-select: none;
}
</style>
