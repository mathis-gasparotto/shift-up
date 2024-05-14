<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">Sign Up</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <q-form class="w-100 gap-15 column items-center" @submit.prevent="submit">
      <div class="row w-100 gap-15 no-wrap">
        <div>
          <label for="first_name" class="text-weight-medium label-required">First name</label>
          <q-input v-model="firstName" outlined for="first_name" placeholder="John" class="input" lazy-rules :rules="[
      (val) => val.trim().length > 3 || 'First name must be at least 3 characters long',
    ]" hide-bottom-space />
        </div>
        <div>
          <label for="last_name" class="text-weight-medium label-required">Last name</label>
          <q-input v-model="lastName" outlined for="last_name" placeholder="Doe" class="input" lazy-rules :rules="[
      (val) => val.trim().length > 3 || 'Last name must be at least 3 characters long',
    ]" hide-bottom-space />
        </div>
      </div>
      <div class="w-100">
        <label for="phone" class="text-weight-medium label-required">Phone</label>
        <Vue3QTelInput v-model:tel="phone" outlined for="phone" hide-bottom-space default-country="us" type="tel"
          inputmode="tel" placeholder="000-0000000" />
      </div>
      <div class="w-100">
        <label for="email" class="text-weight-medium label-required">Email</label>
        <q-input v-model="email" outlined inputmode="email" for="email" placeholder="johndoe@gmail.com" class="input"
          type="email" lazy-rules :rules="[
      (val, rules) =>
        rules.email(val) || 'You must enter a valid email address'
    ]" hide-bottom-space />
      </div>
      <div class="w-100">
        <label for="password" class="text-weight-medium label-required">Password</label>
        <q-input v-model="password.value" outlined :type="password.visible ? 'text' : 'password'" for="password"
          placeholder="Your password" class="input" lazy-rules
          hint="8 characters minimum, one uppercase letter, one lowercase letter, one number and one special character"
          hide-hint :rules="[
      (val) => val.trim().length > 0 || 'You must enter a password',
      (val) =>
        /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}/g.test(val) ||
        'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number and one special character'
    ]" hide-bottom-space>
          <template v-slot:append>
            <q-icon :name="password.visible ? 'visibility' : 'visibility_off'" class="cursor-pointer"
              @click="password.visible = !password.visible" />
          </template>
        </q-input>
      </div>
      <div class="w-100">
        <label for="confirm_password" class="text-weight-medium label-required">Password confirmation</label>
        <q-input v-model="confirmPassword.value" outlined :type="confirmPassword.visible ? 'text' : 'password'"
          for="confirm_password" placeholder="Your password again" class="input" lazy-rules
          hint="8 characters minimum, one uppercase letter, one lowercase letter, one number and one special character"
          hide-hint :rules="[
      (val) => val.trim().length > 0 || 'You must enter a password confirmation',
      (val) => val === password.value || 'Passwords do not match',
    ]">
          <template v-slot:append>
            <q-icon :name="confirmPassword.visible ? 'visibility' : 'visibility_off'" class="cursor-pointer"
              @click="confirmPassword.visible = !confirmPassword.visible" />
          </template>
        </q-input>
      </div>
      <SUbtn label="Sign Up" color="gradient" class="w-100 q-mt-md" rounded type="submit" :loading="loading" />
    </q-form>
  </q-page>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import 'vue3-q-tel-input/dist/vue3-q-tel-input.esm.css'
import Vue3QTelInput from 'vue3-q-tel-input'
import { successNotify, errorNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'SignUp',
  components: {
    SUbtn,
    Vue3QTelInput
  },
  data() {
    return {
      firstName: '',
      lastName: '',
      phone: '',
      email: '',
      password: {
        value: '',
        visible: false
      },
      confirmPassword: {
        value: '',
        visible: false
      },
      loading: false
    }
  },
  methods: {
    submit() {
      this.loading = true

      this.firstName = this.firstName.trim()
      this.lastName = this.lastName.trim()
      this.phone = this.phone.trim()
      this.email = this.email.trim()
      this.password.value = this.password.value.trim()
      this.confirmPassword.value = this.confirmPassword.value.trim()

      const payload = {
        firstName: this.firstName,
        lastName: this.lastName,
        phone: this.phone.replace(/\s/g, ''),
        email: this.email,
        password: this.password.value,
        confirmPassword: this.confirmPassword.value
      }

      this.$auth.signup(payload)
        .then(() => {
          this.$router.push({ name: 'signin' })
          successNotify('Account created successfully')
        })
        .catch((error) => {
          this.loading = false
          errorNotify('Something went wrong')
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
