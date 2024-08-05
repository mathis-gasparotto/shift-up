<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('forgotPassword.title') }}</h2>
      <p>
        {{ $t('forgotPassword.content') }}
      </p>
    </div>
    <q-form
      class="w-100 gap-10 column items-center"
      @submit.prevent="submit"
    >
      <div class="w-100">
        <label
          for="email"
          class="text-weight-medium label-required"
          >{{ $t('form.label.email') }}</label
        >
        <q-input
          v-model="email"
          outlined
          inputmode="email"
          for="email"
          :placeholder="$t('form.placeholder.email')"
          class="input"
          type="email"
          lazy-rules
          :rules="[
            (val) => val.trim().length > 0 || $t('form.error.required'),
            (val, rules) => rules.email(val) || $t('form.error.email')
          ]"
        />
      </div>
      <p
        v-if="error"
        class="text-negative q-mb-none"
      >
        {{ error }}
      </p>
      <p
        v-if="successSessage"
        class="text-positive q-mb-none"
      >
        {{ successSessage }}
      </p>
      <SUbtn
        :label="$t('forgotPassword.submit')"
        color="gradient"
        class="w-100"
        rounded
        type="submit"
        :loading="loading"
        :disabled="!isValid"
      />
    </q-form>
    <p class="q-mt-lg">
      <router-link
        :to="{ name: 'signin' }"
        class="text-bold"
        >{{ $t('forgotPassword.back') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn
  },
  data() {
    return {
      email: '',
      loading: false,
      error: '',
      successSessage: ''
    }
  },
  computed: {
    isValid() {
      return this.email.trim().length > 0
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.email = this.email.trim()

      this.$resources.forgotPassword
        .create({ email: this.email })
        .then(() => {
          this.successSessage = this.$t('forgotPassword.success')
        })
        .catch((error) => {
          this.error = translateError(error)
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.input {
  margin-top: 5px;
}

.page {
  width: 410px;
}
</style>
