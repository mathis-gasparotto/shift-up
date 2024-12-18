<template>
  <Modal
    v-if="team"
    :title="$t('subscription.choicePlanModal.title', { name: team.name })"
    ref="modal"
    buttonsAlign="full"
    width="1000px"
  >
    <StripeCheckout
      ref="checkoutRef"
      :pk="publicKeyStripe"
      :session-id="sessionId"
    />
    <div
      v-if="team.hasStripeSubscriptionSchedule"
      class="text-center"
    >
      <p>{{ $t('subscription.choicePlanModal.changePlanAlreadyScheduledWarning') }}</p>
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
        @click="
          () => {
            if (!team.hasStripeSubscriptionSchedule) selectedPlanId = plan.id
          }
        "
        :selected="selectedPlanId === plan.id"
        :subscription="plan"
        :current="team.subscriptionPrice?.id === plan.price.id"
      />
    </div>
    <template
      #buttons
      v-if="!team.hasStripeSubscriptionSchedule"
    >
      <SUbtn
        :label="$t('subscription.choicePlanModal.confirm')"
        color="gradient"
        rounded
        class="w-100"
        :loading="stripeSessionLoading"
        @click="submit"
        :disabled="!selectedPlanId || selectedPlanPrice?.id === team.subscriptionPrice?.id"
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
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'TeamSettingsModal',
  emits: ['submit'],
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
      sessionId: null,
      recurrence: 'MONTH'
    }
  },
  created() {
    this.selectedPlanId = this.team.subscriptionPrice?.subscription?.id
    this.loadPlans()
  },
  computed: {
    selectedPlans() {
      return this.plans.map((plan) => ({
        ...plan,
        price: plan.prices?.find((p) => p.recurrence === this.recurrence)
      }))
    },
    selectedPlanPrice() {
      return this.selectedPlans.find((p) => p.id === this.selectedPlanId)?.price
    }
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
            this.sessionId = res.id
            this.$refs.checkoutRef.redirectToCheckout()
          } else if (res.object === 'subscription' || res.object === 'subscription_schedule') {
            setTimeout(() => {
              successNotify(this.$t('subscription.choicePlanModal.updateSuccess'))
              this.selectedPlanId = this.team.subscriptionPrice?.subscription?.id
              this.$refs.modal.open = false
              this.stripeSessionLoading = false
              this.$emit('submit')
            }, 1000)
          }
        })
        .catch((err) => {
          displayError(err, this.$t('subscription.choicePlanModal.createStripeSessionError'))
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
