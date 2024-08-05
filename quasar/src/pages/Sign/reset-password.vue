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
        <label
          for="password"
          class="text-weight-medium label-required"
        >
          {{ $t('form.label.newPassword') }}
        </label>
        <q-input
          v-model="password.value"
          outlined
          for="password"
          :placeholder="$t('form.placeholder.newPassword')"
          class="input"
          :type="password.visible ? 'text' : 'password'"
          lazy-rules
          :rules="[
            (val) => val.trim().length > 0 || $t('form.error.required'),
            (val) => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}/g.test(val) || $t('error.passwordComplexity')
          ]"
        >
          <template v-slot:append>
            <q-icon
              :name="password.visible ? 'visibility' : 'visibility_off'"
              class="cursor-pointer"
              @click="password.visible = !password.visible"
            />
          </template>
        </q-input>
      </div>
      <div class="w-100">
        <label
          for="confirmPassword"
          class="text-weight-medium label-required"
        >
          {{ $t('form.label.confirmNewPassword') }}
        </label>
        <q-input
          v-model="confirmPassword.value"
          outlined
          for="confirmPassword"
          :placeholder="$t('form.placeholder.confirmNewPassword')"
          class="input"
          :type="confirmPassword.visible ? 'text' : 'password'"
          lazy-rules
          :rules="[
            (val) => val.trim().length > 0 || $t('form.error.required'),
            (val) => val === password.value || $t('error.passwordsDoNotMatch')
          ]"
        >
          <template v-slot:append>
            <q-icon
              :name="confirmPassword.visible ? 'visibility' : 'visibility_off'"
              class="cursor-pointer"
              @click="confirmPassword.visible = !confirmPassword.visible"
            />
          </template>
        </q-input>
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
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn
  },
  data() {
    return {
      password: {
        value: '',
        visible: false
      },
      confirmPassword: {
        value: '',
        visible: false
      },
      loading: false,
      error: '',
      successSessage: ''
    }
  },
  computed: {
    isValid() {
      return this.password.value.trim().length > 0 && this.password.value === this.confirmPassword.value
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.password.value = this.password.value.trim()
      this.confirmPassword.value = this.confirmPassword.value.trim()

      this.$resources.resetPassword
        .postWithId(this.$route.params.token, {
          password: this.password.value,
          confirmPassword: this.confirmPassword.value
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
