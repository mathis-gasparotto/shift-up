<template>
  <Modal
    v-if="team"
    :title="$t('team.choicePlanModal.title', { name: team.name })"
    ref="modal"
    buttonsAlign="full"
    width="1000px"
  >
    <StripeCheckout
      ref="checkoutRef"
      :pk="publicKeyStripe"
      :session-id="sessionId"
    />
    <div class="flex justify-between">
      <template v-if="plansLoading">
        <SubscriptionCardSkeleton />
        <SubscriptionCardSkeleton />
        <SubscriptionCardSkeleton />
      </template>
      <SubscriptionCard
        v-else
        v-for="plan in plans"
        :key="plan.id"
        @click="selectedPlanId = plan.id"
        :selected="selectedPlanId === plan.id"
        :subscription="plan"
      />
    </div>
    <template #buttons>
      <SUbtn
        :label="$t('team.choicePlanModal.confirm')"
        color="gradient"
        rounded
        class="w-100"
        :loading="stripeSessionLoading"
        @click="submit"
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
import { StripeCheckout } from '@vue-stripe/vue-stripe'

export default {
  name: 'TeamSettingsModal',
  components: {
    Modal,
    SUbtn,
    SubscriptionCard,
    SubscriptionCardSkeleton,
    StripeCheckout
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
      publicKeyStripe: process.env.STRIPE_PUBLIC_KEY,
      sessionId: null
    }
  },
  created() {
    this.selectedPlanId = this.team.subscription?.id
    this.loadPlans()
  },
  methods: {
    loadPlans() {
      this.plansLoading = true
      this.$resources.subscriptions
        .list()
        .then((res) => {
          this.plans = res.data
        })
        .catch((err) => {
          this.plans = []
          displayError(err, this.$t('team.choicePlanModal.loadPlansError'))
        })
        .finally(() => {
          this.plansLoading = false
        })
    },
    submit() {
      this.stripeSessionLoading = true
      this.$resources.teams
        .createChild(this.team.id, 'choice_subscription', {
          subscription: '/subscriptions/' + this.selectedPlanId
        })
        .then((res) => {
          this.sessionId = res.id
          this.$refs.checkoutRef.redirectToCheckout()
        })
        .catch((err) => {
          displayError(err, this.$t('team.choicePlanModal.createStripeSessionError'))
          this.stripeSessionLoading = false
        })
    },
    openModal() {
      this.$refs.modal.open = true
    }
  }
}
</script>

<style lang="scss" scoped></style>
