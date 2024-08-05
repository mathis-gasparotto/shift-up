<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('signin.title') }}</h2>
      <p>{{ $t('signin.content') }}</p>
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
          :rules="[(val) => val.trim().length > 0 || $t('form.error.required')]"
        />
      </div>
      <div class="w-100 q-mb-sm">
        <label
          for="password"
          class="text-weight-medium label-required"
          >{{ $t('form.label.password') }}</label
        >
        <q-input
          v-model="password.value"
          outlined
          :type="password.visible ? 'text' : 'password'"
          for="password"
          :placeholder="$t('form.placeholder.password')"
          class="input"
          lazy-rules
          :rules="[(val) => val.trim().length > 0 || $t('form.error.required')]"
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
    <p class="q-mt-dm">
      {{ $t('signin.forgotPassword') }}
      <router-link
        :to="{ name: 'forgotPassword' }"
        class="text-bold"
        >{{ $t('signin.resetPassword') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import { successNotify } from 'src/helpers/notifyHelper'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    SUbtn
  },
  data() {
    return {
      email: '',
      password: {
        value: '',
        visible: false
      },
      loading: false,
      error: ''
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.email = this.email.trim()
      this.password.value = this.password.value.trim()

      this.$auth
        .login(this.email, this.password.value)
        .then(() => {
          if (this.$route.query.redirect) {
            this.$router.push(this.$route.query.redirect)
          } else {
            this.$router.push({ name: 'index' })
          }
          successNotify($t('signin.success'))
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
