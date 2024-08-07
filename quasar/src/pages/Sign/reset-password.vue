<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('resetPassword.title') }}</h2>
      <p>
        {{ $t('resetPassword.content') }}
      </p>
    </div>
    <q-form
      class="w-100 gap-10 column items-center"
      @submit.prevent="submit"
    >
      <div class="w-100">
        <SUinput
          v-model="password"
          :label="$t('form.user.label.newPassword')"
          name="password"
          type="password"
          :placeholder="$t('form.user.placeholder.newPassword')"
          :hint="$t('signup.passwordHint')"
          required
          :rules="[
            (val) => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}/g.test(val) || $t('error.passwordComplexity')
          ]"
        />
      </div>
      <div class="w-100">
        <SUinput
          v-model="confirmPassword"
          :label="$t('form.user.label.confirmNewPassword')"
          name="confirm_password"
          type="password"
          :placeholder="$t('form.user.placeholder.confirmNewPassword')"
          required
          :rules="[(val) => val === password || $t('error.passwordsDoNotMatch')]"
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
        :label="$t('resetPassword.submit')"
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
        >{{ $t('resetPassword.back') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import SUinput from 'src/components/SUinput.vue'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn,
    SUinput
  },
  data() {
    return {
      password: '',
      confirmPassword: '',
      loading: false,
      error: '',
      successSessage: ''
    }
  },
  computed: {
    isValid() {
      return this.password.trim().length > 0 && this.password === this.confirmPassword
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.password = this.password.trim()
      this.confirmPassword = this.confirmPassword.trim()

      this.$resources.resetPassword
        .postWithId(this.$route.params.token, {
          password: this.password,
          confirmPassword: this.confirmPassword
        })
        .then(() => {
          this.successSessage = this.$t('resetPassword.success')
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
