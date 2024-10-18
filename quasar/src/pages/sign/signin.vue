<template>
  <q-page class="flex-center column page q-mx-sm-auto q-mx-md q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('signin.title') }}</h2>
      <p>{{ $t('signin.content') }}</p>
    </div>
    <q-form
      class="w-100 gap-10 column items-center"
      @submit.prevent="submit"
    >
      <div class="w-100">
        <SUinput
          v-model="email"
          :label="$t('form.user.label.email')"
          name="email"
          type="email"
          :placeholder="$t('form.user.placeholder.email')"
          required
        />
      </div>
      <div class="w-100 q-mb-sm">
        <SUinput
          v-model="password"
          :label="$t('form.user.label.password')"
          name="password"
          type="password"
          :placeholder="$t('form.user.placeholder.password')"
          required
        />
      </div>
      <p
        v-if="error"
        class="text-negative q-mb-none"
      >
        {{ error }}
      </p>
      <SUbtn
        :label="$t('signin.submit')"
        color="gradient"
        class="w-100"
        rounded
        type="submit"
        :loading="loading"
      />
    </q-form>
    <p class="q-mt-lg">
      {{ $t('signin.noAccount') }}
      <router-link
        :to="{ name: 'signup' }"
        class="text-bold"
        >{{ $t('signin.signup') }}</router-link
      >
    </p>
    <p class="q-mt-dm text-center">
      {{ $t('signin.forgotPassword') }}
      <router-link
        :to="{ name: 'forgotPassword' }"
        class="text-bold block d-sm-inline"
        >{{ $t('signin.resetPassword') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import SUinput from 'src/components/SUinput.vue'
import { successNotify } from 'src/helpers/notifyHelper'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn,
    SUinput
  },
  data() {
    return {
      email: '',
      password: '',
      loading: false,
      error: ''
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.email = this.email.trim()
      this.password = this.password.trim()

      this.$auth
        .login(this.email, this.password)
        .then(() => {
          if (this.$route.query.redirect) {
            this.$router.push(this.$route.query.redirect)
          } else {
            this.$router.push({ name: 'home' })
          }
          successNotify(this.$t('signin.success'))
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
