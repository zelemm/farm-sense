
<template>
  <SelectInput
    v-model="iValue"
    :items="options"
    item-text="name"
    item-value="value"
    :errors="errors"
    :label="lang.get('form.sample_type')"
  />
</template>

<script>
import SelectInput from '@/Common/Form/SelectInput'

export default {
  components: {
    SelectInput,
  },

  props: {
    value: String,
    errors: {String, Object},
  },

  data() {
    return {
      lang: window.lang,
      options:[
        { value: 'Escovilhão nasal', name: window.lang.get('form.sample_types.escovilho_nasal') },
        { value: 'Sangue', name: window.lang.get('form.sample_types.sangue') },
        { value: 'Saliva', name: window.lang.get('form.sample_types.saliva') },
        { value: 'escarro', name: window.lang.get('form.sample_types.escarro') },
        { value: 'aspirado', name: window.lang.get('form.sample_types.aspirado') },
        { value: 'lavado', name: window.lang.get('form.sample_types.lavado') },
        { value: 'soro', name: window.lang.get('form.sample_types.soro') },
        { value: 'plasma', name: window.lang.get('form.sample_types.plasma') },
        { value: 'liquor', name: window.lang.get('form.sample_types.liquor') },
        { value: 'Sangue Total', name: window.lang.get('form.sample_types.sangue_total') },
        { value: 'urina', name: window.lang.get('form.sample_types.urina') },

      ],
    }
  },

  computed: {
    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
}
</script>
