<template>
  <div>
    <label
      :for="name"
      class="text-weight-medium"
      :class="{ 'label-required': required }"
      >{{ label }}</label
    >
    <Vue3QTelInput
      v-if="type === 'tel'"
      v-model="value"
      outlined
      for="phone"
      :default-country="defaultPhoneCountry"
      type="tel"
      inputmode="tel"
      :placeholder="phoneNumberPlaceholder"
      @country="updatePhoneCountry"
      @error="(val) => (phoneError = val)"
      :rules="rulesFormated"
    />
    <q-input
      v-else-if="type === 'password'"
      v-model="value"
      outlined
      :for="name"
      :placeholder="placeholder"
      class="input"
      lazy-rules
      :inputmode="inputmode"
      :type="showPassword ? 'text' : 'password'"
      :rules="rulesFormated"
      :hint="hint"
      hide-hint
    >
      <template v-slot:append>
        <q-icon
          :name="showPassword ? 'visibility' : 'visibility_off'"
          class="cursor-pointer"
          @click="showPassword = !showPassword"
        />
      </template>
    </q-input>
    <q-input
      v-else
      v-model="value"
      outlined
      :for="name"
      :placeholder="placeholder"
      class="input"
      lazy-rules
      :inputmode="inputmode"
      :type="type"
      :rules="rulesFormated"
      :hint="hint"
      hide-hint
    />
  </div>
</template>

<script>
import 'vue3-q-tel-input/dist/vue3-q-tel-input.esm.css'
import Vue3QTelInput from 'vue3-q-tel-input'
import { langsData } from 'src/helpers/langs'
import { phoneNumberPlaceholders } from 'src/helpers/phone'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

export default {
  name: 'SUinput',
  components: {
    Vue3QTelInput
  },
  props: {
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    placeholder: {
      type: String,
      default: undefined
    },
    required: {
      type: Boolean,
      default: false
    },
    rules: {
      type: Array,
      default: () => []
    },
    type: {
      type: String,
      default: 'text'
    },
    modelValue: {
      type: [String, Number, Boolean, Object, Array, null],
      required: true
    },
    minLength: {
      type: Number,
      default: 0
    },
    hint: {
      type: String,
      default: undefined
    }
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
      phoneError: null,
      showPassword: false
    }
  },
  computed: {
    inputmode() {
      switch (this.type) {
        case 'tel':
          return 'tel'
        case 'email':
          return 'email'
        case 'number':
          return 'numeric'
        default:
          return 'text'
      }
    },
    value: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    },
    rulesFormated() {
      switch (this.type) {
        case 'tel':
          return this.required
            ? [
                (val) =>
                  (val && val.trim().length >= this.minLength) ||
                  (this.minLength > 0
                    ? this.$t('form.error.min', { min: this.minLength })
                    : this.$t('form.error.required')),
                (val) => !this.phoneError || this.$t('error.phoneNumberInvalid'),
                ...this.rules
              ]
            : [(val) => !this.phoneError || this.$t('error.phoneNumberInvalid'), ...this.rules]
        case 'email':
          return this.required
            ? [
                (val) =>
                  (val && val.trim().length >= this.minLength) ||
                  (this.minLength > 0
                    ? this.$t('form.error.min', { min: this.minLength })
                    : this.$t('form.error.required')),
                (val, rules) => rules.email(val) || this.$t('error.emailInvalid'),
                ...this.rules
              ]
            : [(val, rules) => rules.email(val) || this.$t('error.emailInvalid'), ...this.rules]
        default:
          return this.required
            ? [
                (val) =>
                  (val && val.trim().length >= this.minLength) ||
                  (this.minLength > 0
                    ? this.$t('form.error.min', { min: this.minLength })
                    : this.$t('form.error.required')),
                ...this.rules
              ]
            : this.rules
      }
    }
  },
  methods: {
    updatePhoneCountry(country) {
      this.phoneNumberPlaceholder = phoneNumberPlaceholders[country.iso2]
    }
  }
}
</script>

<style lang="scss" scoped></style>
