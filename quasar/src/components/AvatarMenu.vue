<template>
  <q-menu auto-clos>
    <q-list style="min-width: 100px">
      <q-item clickable>
        <q-item-section>New tab</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>New incognito tab</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Recent tabs</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>History</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>Downloads</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Settings</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Help &amp; Feedback</q-item-section>
      </q-item>
    </q-list>
  </q-menu>
</template>

<script>

export default {
  name: 'AvatarMenu',
}
</script>

<style lang="scss" scoped></style>
