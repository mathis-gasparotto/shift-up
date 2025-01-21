<template>
  <Modal
    v-if="team"
    :title="$t('subscription.choicePlanModal.title', { name: team.name })"
    ref="modal"
    buttonsAlign="full"
    width="1000px"
  >
    <div
      v-if="team.hasStripeSubscriptionSchedule"
      class="text-center"
    >
      <p>{{ $t('subscription.choicePlanModal.changePlanAlreadyScheduled') }}</p>
      <p>{{ $t('subscription.choicePlanModal.canModifyChange') }}</p>
    </div>
    <div
      v-else-if="team.hasStripeSubscription"
      class="text-center"
    >
      <p>{{ $t('subscription.choicePlanModal.changePlanWarning') }}</p>
    </div>
    <div class="text-center">
      <p class="d-inline q-mb-none">{{ $t('subscription.choicePlanModal.monthly') }}</p>
      <q-toggle
        v-model="recurrence"
        true-value="YEAR"
        false-value="MONTH"
        :label="$t('subscription.choicePlanModal.yearly')"
      />
    </div>
    <div class="flex justify-evenly">
      <template v-if="plansLoading">
        <SubscriptionCardSkeleton />
        <SubscriptionCardSkeleton />
        <SubscriptionCardSkeleton />
      </template>
      <SubscriptionCard
        v-else
        v-for="plan in selectedPlans"
        :key="plan.id"
        @click="selectedPlanId = plan.id"
        :selected="selectedPlanId === plan.id"
        :subscription="plan"
        :current="team.subscriptionPrice?.id === plan.price.id"
        :scheduled="plan.scheduled"
        :canceled="plan.canceled"
      />
    </div>
    <template #buttons>
      <SUbtn
        :label="$t('subscription.choicePlanModal.confirm')"
        color="gradient"
        rounded
        class="w-100"
        :loading="stripeSessionLoading"
        @click="submit"
        :disabled="!selectedPlanId || !selectedPlanPrice || selectedPlanPrice?.id === (team.hasStripeSubscriptionSchedule ? team.scheduledSubscriptionPrice?.id : team.subscriptionPrice?.id) || (selectedPlanPrice?.price <= 0 && team.scheduledSubscriptionPrice?.id === selectedPlanPrice?.id)"
      />
    </template>
  </Modal>
</template>

<script>
import Modal from 'src/components/Modal.vue'
import SUbtn from 'src/components/SUbtn.vue'
import { displayError } from 'src/helpers/translatting'
import SubscriptionCard from 'src/components/Subscription/SubscriptionCard.vue'
import SubscriptionCardSkeleton from 'src/components/Subscription/SubscriptionCardSkeleton.vue'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'TeamPlansModal',
  emits: ['submited'],
  components: {
    Modal,
    SUbtn,
    SubscriptionCard,
    SubscriptionCardSkeleton
  },
  props: {
    team: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      plansLoading: true,
      plans: [],
      selectedPlanId: null,
      stripeSessionLoading: false,
      recurrence: 'MONTH'
    }
  },
  created() {
    this.selectedPlanId = this.team.subscriptionPrice?.subscription?.id
    this.loadPlans()
  },
  computed: {
    selectedPlans() {
      let plans = []
      this.plans.forEach((plan) => {
        const price = plan.prices?.find((p) => p.recurrence === this.recurrence)
        if (!price) return
        plans.push({
          ...plan,
          price: price,
          scheduled: this.team.scheduledSubscriptionPrice?.id === price.id && price.price > 0 ? new Date(this.team.subscriptionEndAt) : false,
          canceled: this.team.scheduledSubscriptionPrice?.price <= 0 ? new Date(this.team.subscriptionEndAt) : false
        })
      })
      return plans
    },
    selectedPlanPrice() {
      return this.selectedPlans.find((p) => p.id === this.selectedPlanId)?.price
    }
  },
  methods: {
    openModal() {
      this.$refs.modal.open = true
    },
    loadPlans() {
      this.plansLoading = true
      this.$resources.subscriptions
        .list()
        .then((res) => {
          this.plans = res.data.sort((a, b) => a.prices.find((p) => p.recurrence === 'MONTH').price - b.prices.find((p) => p.recurrence === 'MONTH').price)
        })
        .catch((err) => {
          this.plans = []
          displayError(err, this.$t('subscription.choicePlanModal.loadPlansError'))
        })
        .finally(() => {
          this.plansLoading = false
        })
    },
    submit() {
      this.stripeSessionLoading = true
      this.$resources.teams
        .createChild(this.team.id, 'choice_subscription', {
          subscriptionPrice: this.selectedPlans.find((p) => p.id === this.selectedPlanId)?.price['@id']
        })
        .then((res) => {
          if (res.object === 'checkout.session') {
            window.location.href = res.url
          } else {
            setTimeout(() => {
              successNotify(this.$t('subscription.choicePlanModal.updateSuccess'))
              this.selectedPlanId = this.team.subscriptionPrice?.subscription?.id
              this.$refs.modal.open = false
              this.stripeSessionLoading = false
              this.$emit('submited')
            }, 1000)
          }
        })
        .catch((err) => {
          displayError(err, this.$t('subscription.choicePlanModal.createStripeSessionError'))
          this.stripeSessionLoading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped></style>
