<template>
  <q-page class="flex-center column page q-mx-sm-auto q-mx-md q-py-xl">
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
          <SUinput
            v-model="firstName"
            :label="$t('form.user.label.firstName')"
            name="first_name"
            type="text"
            :placeholder="$t('form.user.placeholder.firstName')"
            required
            :minLength="3"
          />
        </div>
        <div>
          <SUinput
            v-model="lastName"
            :label="$t('form.user.label.lastName')"
            name="last_name"
            type="text"
            :placeholder="$t('form.user.placeholder.lastName')"
            required
            :minLength="3"
          />
        </div>
      </div>
      <div class="w-100">
        <SUinput
          v-model="phone"
          :label="$t('form.user.label.phoneNumber')"
          name="phone"
          type="tel"
          required
        />
      </div>
      <div class="w-100">
        <SUinput
          v-model="email"
          :label="$t('form.user.label.email')"
          name="email"
          type="email"
          :placeholder="$t('form.user.placeholder.email', { at: '@' })"
          required
        />
      </div>
      <div class="w-100">
        <SUinput
          v-model="password"
          :label="$t('form.user.label.password')"
          name="password"
          type="password"
          :placeholder="$t('form.user.placeholder.password')"
          :hint="$t('signup.passwordHint')"
          required
          :rules="[(val) => /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}/g.test(val) || $t('error.passwordComplexity')]"
        />
      </div>
      <div class="w-100 q-mb-sm">
        <SUinput
          v-model="confirmPassword"
          :label="$t('form.user.label.confirmPassword')"
          name="confirm_password"
          type="password"
          :placeholder="$t('form.user.placeholder.confirmPassword')"
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
import SUinput from 'src/components/SUinput.vue'
import InfoCard from 'src/components/InfoCard.vue'
import { successNotify } from 'src/helpers/notifyHelper'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn,
    SUinput,
    InfoCard
  },
  data() {
    return {
      firstName: '',
      lastName: '',
      phone: '',
      email: '',
      password: '',
      confirmPassword: '',
      loading: false,
      error: '',
      signedUp: false
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.firstName = this.firstName.trim()
      this.lastName = this.lastName.trim()
      this.phone = this.phone.trim()
      this.email = this.email.trim()
      this.password = this.password.trim()
      this.confirmPassword = this.confirmPassword.trim()

      const payload = {
        firstName: this.firstName,
        lastName: this.lastName,
        phone: this.phone.replace(/\s/g, ''),
        email: this.email,
        password: this.password,
        confirmPassword: this.confirmPassword
      }

      this.$auth
        .signup(payload)
        .then(() => {
          this.signedUp = true
          this.error = ''
          this.loading = false
          successNotify(this.$t('signup.success'))
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
  @media screen and (max-width: 1022px) {
    width: auto;
    min-height: unset !important;
  }
}
</style>
