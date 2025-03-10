<template>
  <q-form
    class="w-100 gap-10 column items-center"
    @submit.prevent="submit"
  >
    <div class="w-100">
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
      :label="$t('deleteAccount.submit')"
      color="negative"
      class="w-100"
      rounded
      type="submit"
      :loading="loading"
      :disabled="!isValid"
    />
  </q-form>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import SUinput from 'src/components/SUinput.vue'
import { successNotify } from 'src/helpers/notifyHelper'
import { translateError } from 'src/helpers/translatting'

export default {
  name: 'DeleteAccountForm',
  components: {
    SUbtn,
    SUinput
  },
  data() {
    return {
      password: '',
      loading: false,
      error: null,
      successMessage: null
    }
  },
  computed: {
    isValid() {
      return this.password.trim().length > 0
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.password = this.password.trim()

      this.$resources.checkUsers
        .create({
          password: this.password
        })
        .then(() => {
          this.$resources.users
            .delete(this.$auth.getUser.id)
            .then(() => {
              this.$auth.logout()
              this.$router.push({ name: 'signin' })
              successNotify(this.$t('deleteAccount.success'))
            })
            .catch((error) => {
              this.error = translateError(error)
              this.successMessage = null
            })
            .finally(() => {
              this.loading = false
            })
        })
        .catch((error) => {
          this.error = translateError(error)
          this.successMessage = null
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
</style>
