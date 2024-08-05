<template>
  <div>
    <TeamCardSkeleton v-if="loading" />
    <div
      class="teams-list flex gap-20"
      v-else-if="teams.length > 0"
    >
      <TeamCard
        v-for="team in teams"
        :key="team.id"
        :team="team"
        link
      />
    </div>
    <div v-else>{{ $t('team.list.noTeam') }}</div>
  </div>
</template>

<script>
import TeamCard from 'src/components/Teams/TeamCard.vue'
import TeamCardSkeleton from 'src/components/Teams/TeamCardSkeleton.vue'

export default {
  name: 'TeamList',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    teams: {
      type: Array,
      required: true
    }
  },
  components: {
    TeamCard,
    TeamCardSkeleton
  }
}
</script>

<style lang="scss" scoped></style>
