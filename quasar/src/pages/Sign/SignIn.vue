<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">Connexion</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <q-form class="w-100 gap-10 column items-center" @submit.prevent="submit">
      <div class="w-100">
        <label for="email" class="text-weight-medium label-required">Email</label>
        <q-input v-model="email" outlined inputmode="email" for="email" placeholder="johndoe@gmail.com" class="input"
          type="email" lazy-rules :rules="[
      (val) =>
        val.trim().length > 0 || 'Email requise'
    ]" />
      </div>
      <div class="w-100 q-mb-sm">
        <label for="password" class="text-weight-medium label-required">Mot de passe</label>
        <q-input v-model="password.value" outlined :type="password.visible ? 'text' : 'password'" for="password"
          placeholder="Votre mot de passe" class="input" lazy-rules :rules="[
      (val) => val.trim().length > 0 || 'Mot de passe requis']">
          <template v-slot:append>
            <q-icon :name="password.visible ? 'visibility' : 'visibility_off'" class="cursor-pointer"
              @click="password.visible = !password.visible" />
          </template>
        </q-input>
      </div>
      <p v-if="error" class="text-negative q-mb-none">{{ error }}</p>
      <SUbtn label="Se connecter" color="gradient" class="w-100" rounded type="submit" :loading="loading" />
    </q-form>
    <p class="q-mt-lg">
      Pas encore de compte ?
      <router-link :to="{ name: 'signup' }" class="text-bold">S'inscrire</router-link>
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

      this.$auth.login(this.email, this.password.value)
        .then(() => {
          if (this.$route.query.redirect) {
            this.$router.push(this.$route.query.redirect)
          } else {
            this.$router.push({ name: 'index' })
          }
          successNotify('Vous avez bien été connecté !')
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
