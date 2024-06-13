<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">S'inscrire</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <q-form class="w-100 gap-10 column items-center" @submit.prevent="submit">
      <div class="row w-100 gap-15 no-wrap">
        <div>
          <label for="first_name" class="text-weight-medium label-required">Prénom</label>
          <q-input v-model="firstName" outlined for="first_name" placeholder="John" class="input" lazy-rules :rules="[
      (val) => val && val.trim().length > 3 || 'Prénom requis',
    ]" />
        </div>
        <div>
          <label for="last_name" class="text-weight-medium label-required">Nom</label>
          <q-input v-model="lastName" outlined for="last_name" placeholder="Doe" class="input" lazy-rules :rules="[
      (val) => val && val.trim().length > 3 || 'Nom requis',
    ]" />
        </div>
      </div>
      <div class="w-100">
        <label for="phone" class="text-weight-medium label-required">Numéro de téléphone</label>
        <Vue3QTelInput v-model="phone.value" outlined for="phone" :default-country="defaultPhoneCountry" type="tel"
          inputmode="tel" :placeholder="phoneNumberPlaceholder" @country="updatePhoneCountry"
          @error="(val) => phone.error = val" :rules="[
      (val) => !!val || 'Numéro de téléphone requis',
      (val) => !phone.error || 'Numéro de téléphone invalide',
    ]" />
      </div>
      <div class="w-100">
        <label for="email" class="text-weight-medium label-required">Email</label>
        <q-input v-model="email" outlined inputmode="email" for="email" placeholder="johndoe@gmail.com" class="input"
          type="email" lazy-rules :rules="[
      (val, rules) =>
        rules.email(val) || 'Email invalide',
    ]" />
      </div>
      <div class="w-100">
        <label for="password" class="text-weight-medium label-required">Mot de passe</label>
        <q-input v-model="password.value" outlined :type="password.visible ? 'text' : 'password'" for="password"
          placeholder="Votre mot de passe" class="input" lazy-rules
          hint="8 caractères minimum, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial"
          hide-hint :rules="[
      (val) => val && val.trim().length > 0 || 'You must enter a password',
      (val) =>
        /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}/g.test(val) ||
        'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial'
    ]">
          <template v-slot:append>
            <q-icon :name="password.visible ? 'visibility' : 'visibility_off'" class="cursor-pointer"
              @click="password.visible = !password.visible" />
          </template>
        </q-input>
      </div>
      <div class="w-100 q-mb-sm">
        <label for="confirm_password" class="text-weight-medium label-required">Confirmation du mot de passe</label>
        <q-input v-model="confirmPassword.value" outlined :type="confirmPassword.visible ? 'text' : 'password'"
          for="confirm_password" placeholder="Encore votre mot de passe..." class="input" reactive-rules :rules="[
      (val) => val.trim().length > 0 || 'Vous devez entrer un mot de passe',
      (val) => val === password.value || 'Les mots de passe ne correspondent pas',
    ]">
          <template v-slot:append>
            <q-icon :name="confirmPassword.visible ? 'visibility' : 'visibility_off'" class="cursor-pointer"
              @click="confirmPassword.visible = !confirmPassword.visible" />
          </template>
        </q-input>
      </div>
      <p v-if="error" class="text-negative q-mb-none">{{ error }}</p>
      <SUbtn label="S'inscrire" color="gradient" class="w-100" rounded type="submit" :loading="loading" />
      <InfoCard v-if="signedUp" class="w-100 q-mt-md" type="success" title="Vous avez bien été inscrit !"
        action-btn-label="Go for sign in" :action-btn-route="{ name: 'signin' }">
        <template #content>
          Un mail de confirmation a été envoyé à votre adresse email. Veuillez vérifier votre boîte de réception ainsi
          que vos spams.
        </template>
      </InfoCard>
    </q-form>
    <p class="q-mt-lg">
      Déjà inscrit ?
      <router-link :to="{ name: 'signin' }" class="text-bold">Se connecter</router-link>
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
import { phoneNumberPlaceholders } from 'src/helpers/phone'

export default {
  components: {
    SUbtn,
    Vue3QTelInput,
    InfoCard
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
      defaultPhoneCountry: 'FR',
      phoneNumberPlaceholder: '6 00 00 00 00',
      error: '',
      signedUp: false
    }
  },
  created() {
    if (this.$lang.getCurrentLang.countryCode) {
      this.defaultPhoneCountry = this.$lang.getCurrentLang.countryCode
    }
    // this.phoneNumberPlaceholder = phoneNumberPlaceholders[this.defaultPhoneCountry]
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

      this.$auth.signup(payload)
        .then(() => {
          this.signedUp = true
          this.error = ''
          this.loading = false
          successNotify('Inscription réussie')
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
