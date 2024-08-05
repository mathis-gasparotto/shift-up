<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('signup.title') }}</h2>
      <p>{{ $t('signup.content') }}</p>
    </div>
    <q-form
      class="w-100 gap-10 column items-center"
      @submit.prevent="submit"
    >
      <div class="row w-100 gap-15 no-wrap">
        <div>
          <label
            for="first_name"
            class="text-weight-medium label-required"
            >{{ $t('form.user.label.fisrtName') }}</label
          >
          <q-input
            v-model="firstName"
            outlined
            for="first_name"
            :placeholder="$t('form.user.placeholder.fisrtName')"
            class="input"
            lazy-rules
            :rules="[(val) => (val && val.trim().length > 3) || $t('form.error.required')]"
          />
        </div>
        <div>
          <label
            for="last_name"
            class="text-weight-medium label-required"
            >{{ $t('form.user.label.lastName') }}</label
          >
          <q-input
            v-model="lastName"
            outlined
            for="last_name"
            :placeholder="$t('form.user.placeholder.lastName')"
            class="input"
            lazy-rules
            :rules="[(val) => (val && val.trim().length > 3) || $t('form.error.required')]"
          />
        </div>
      </div>
      <div class="w-100">
        <label
          for="phone"
          class="text-weight-medium label-required"
          >{{ $t('form.user.label.phoneNumber') }}</label
        >
        <Vue3QTelInput
          v-model="phone.value"
          outlined
          for="phone"
          :default-country="defaultPhoneCountry"
          type="tel"
          inputmode="tel"
          :placeholder="phoneNumberPlaceholder"
          @country="updatePhoneCountry"
          @error="(val) => (phone.error = val)"
          :rules="[
            (val) => !!val || $t('form.error.required'),
            (val) => !phone.error || $t('error.phoneNumberInvalid')
          ]"
        />
      </div>
      <div class="w-100">
        <label
          for="email"
          class="text-weight-medium label-required"
          >{{ $t('form.user.label.email') }}</label
        >
        <q-input
          v-model="email"
          outlined
          inputmode="email"
          for="email"
          :placeholder="$t('form.user.placeholder.email')"
          class="input"
          type="email"
          lazy-rules
          :rules="[(val, rules) => rules.email(val) || $t('error.emailInvalid')]"
        />
      </div>
      <div class="w-100">
        <label
          for="password"
          class="text-weight-medium label-required"
          >{{ $t('form.user.label.password') }}</label
        >
        <q-input
          v-model="password.value"
          outlined
          :type="password.visible ? 'text' : 'password'"
          for="password"
          :placeholder="$t('form.user.placeholder.password')"
          class="input"
          lazy-rules
          hint="8 caractères minimum, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial"
          hide-hint
          :rules="[
            (val) => (val && val.trim().length > 0) || $t('form.error.required'),
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
      <div class="w-100 q-mb-sm">
        <label
          for="confirm_password"
          class="text-weight-medium label-required"
          >{{ $t('form.user.label.confirmPassword') }}</label
        >
        <q-input
          v-model="confirmPassword.value"
          outlined
          :type="confirmPassword.visible ? 'text' : 'password'"
          for="confirm_password"
          :placeholder="$t('form.user.placeholder.confirmPassword')"
          class="input"
          reactive-rules
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
      <SUbtn
        :label="$t('signup.submit')"
        color="gradient"
        class="w-100"
        rounded
        type="submit"
        :loading="loading"
      />
      <InfoCard
        v-if="signedUp"
        class="w-100 q-mt-md"
        type="success"
        :title="$t('signup.successCard.title')"
        :action-btn-label="$t('signup.successCard.action')"
        :action-btn-route="{ name: 'signin' }"
      >
        <template #content>
          {{ $t('signup.successCard.content') }}
        </template>
      </InfoCard>
    </q-form>
    <p class="q-mt-lg">
      {{ $t('signup.alreadySigned') }}
      <router-link
        :to="{ name: 'signin' }"
        class="text-bold"
        >{{ $t('signup.login') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import InfoCard from 'src/components/InfoCard.vue'
import 'vue3-q-tel-input/dist/vue3-q-tel-input.esm.css'
import Vue3QTelInput from 'vue3-q-tel-input'
import { successNotify } from 'src/helpers/notifyHelper'
import { translateError } from 'src/helpers/translatting'
import { langsData } from 'src/helpers/langs'
import { phoneNumberPlaceholders } from 'src/helpers/phone'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

export default {
  components: {
    SUbtn,
    Vue3QTelInput,
    InfoCard
  },
  setup() {
    const $q = useQuasar()

    const defaultPhoneCountry = langsData[$q.lang.isoName].countryCode
    const phoneNumberPlaceholder = ref(phoneNumberPlaceholders[defaultPhoneCountry])

    return {
      defaultPhoneCountry,
      phoneNumberPlaceholder
    }
  },
  data() {
    return {
      firstName: '',
      lastName: '',
      phone: {
        value: '',
        error: false
      },
      email: '',
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
      signedUp: false
    }
  },
  methods: {
    updatePhoneCountry(country) {
      this.phoneNumberPlaceholder = phoneNumberPlaceholders[country.iso2]
    },
    submit() {
      this.loading = true

      this.firstName = this.firstName.trim()
      this.lastName = this.lastName.trim()
      this.phone.value = this.phone.value.trim()
      this.email = this.email.trim()
      this.password.value = this.password.value.trim()
      this.confirmPassword.value = this.confirmPassword.value.trim()

      const payload = {
        firstName: this.firstName,
        lastName: this.lastName,
        phone: this.phone.value.replace(/\s/g, ''),
        email: this.email,
        password: this.password.value,
        confirmPassword: this.confirmPassword.value
      }

      this.$auth
        .signup(payload)
        .then(() => {
          this.signedUp = true
          this.error = ''
          this.loading = false
          successNotify($t('signup.success'))
        })
        .catch((error) => {
          this.loading = false
          this.error = translateError(error)
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
