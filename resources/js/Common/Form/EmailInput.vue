<template>
  <div class="form-group">
    <label v-if="emailLabel" class="flex-between">
      <span>{{ emailLabel }}</span>
      <span v-if="showSubscribe">{{ 'form.heading.notification' | trans }}?</span>
    </label>
    <v-text-field
      v-model="form.email"
      type="email"
      :class="{ 'is-invalid': errors && errors.email }"
      :readonly="readonly || readonlyEmail"
      :disabled="disabled || disabledEmail"
      :clearable="readonly || readonlyEmail || disabled || disabledEmail ? false : true"
      outlined hide-details
    >
      <template #append v-if="showSubscribe">
        <v-switch
          v-model="form.subscribe"
          class="mb-0 inside-switch"
          :true-value="1"
          :false-value="0"
          inset hide-details
          :readonly="readonly"
          :disabled="disabled"
        />
      </template>
    </v-text-field>

    <div v-if="errors && errors.email && errors.email['0']" class="invalid-feedback">
      {{ errors.email['0'] }}
    </div>
  </div>
</template>

<script>
export default {
  components: {
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  props: {
    form: Object,
    errors: Object,
    readonly: Boolean,
    disabled: Boolean,
    readonlyEmail: Boolean,
    disabledEmail: Boolean,
    showSubscribe: {
      type: Boolean,
      default: true,
    },
    emailLabel: {
      type: String,
      default: window.lang.get('form.email'),
    },
  },

  data() {
    return {
      lang: window.lang,
    }
  },
}
</script>
<style lang="scss" scoped>
.inside-switch {
  margin-top: -8px !important;
  margin-right: -14px !important;
  margin-left: 4px !important;
}
</style>
