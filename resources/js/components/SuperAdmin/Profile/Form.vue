<template>
  <div>
    <v-overlay :value="sending"
      ><v-progress-circular indeterminate size="64"
    /></v-overlay>

    <v-row class="page-title align-center">
      <v-col cols="12" class="flex-between flex-wrap">
        <h4 class="mb-1 mt-0 ">
          {{ user.id ? 'form.button.edit' : 'form.button.create' | trans }}
          {{ 'form.button_module.profile' | trans }}
        </h4>

        <nav aria-label="breadcrumb" class="mt-1">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <router-link :to="{ name: 'super_admin.dashboard' }">
                {{ 'form.heading.dashboard' | trans }}
              </router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ user.id ? form.name : 'form.heading.create' | trans }}
            </li>
          </ol>
        </nav>
      </v-col>
    </v-row>

    <v-row class="mt-0">
      <v-col cols="12" md="9">
        <div class="card">
          <div class="card-body">
                <v-row class="mt-3">

                  <v-col cols="12" md="6">
                    <TextInput
                      v-model="form.name"
                      :label="lang.get('form.name')"
                      :errors="errors.name"
                    />
                  </v-col>
                </v-row>

                <div class=" flex-wrap ">
                  <v-btn
                    :loading="sending"
                    color="primary"
                    class="btn-round"
                    @click="submit()"
                  >
                    <span
                      >{{ 'form.button.update' | trans }}
                      <span>{{ 'form.heading.profile' | trans }} </span> </span
                    ><icon name="rightArrow" class="ml-4" fill="#fff" />
                  </v-btn>
                </div>
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="3">
        <ChangePassword :user-id="user.id" />
      </v-col>
    </v-row>
  </div>
</template>

<script>

export default {
  components: {
    Icon: () => import('@/Shared/Icon'),
    ChangePassword: () => import('@/Shared/ChangePassword'),
    TextInput: () => import('@/Common/Form/TextInput'),
  },

  props: {
    user: Object,
  },

  data() {
    return {
      errors: {},
      lang: window.lang,
      sending: false,
      form: {
        id: this.user.id,
        name: this.user.name,
        password: null,
        password_confirmation: null,
      },
    }
  },

    detailsError() {
      if (this.errors) {
        let detailFields = [
          'name',
        ]

        for (let field of detailFields) {
          if (this.errors[field]) {
            return true
          }
        }
      }

      return false
    },

  methods: {
    submit() {
      let _this = this,
        formData = new FormData()
      for (const i in _this.form) {
        if (_this.form[i] !== '' && _this.form[i] != null && _this.form[i] != 'undefined' && _this.form[i] != undefined) {
          formData.append(i, _this.form[i])
        }
      }

      _this.sending = true

      _this
        .$store
        .dispatch('super_admin/updateProfile', formData)
        .then(() => {
          _this.sending = false
          _this.errors = {}
        })
        .catch(err => {
          _this.sending = false
          _this.errors = err.response.data.errors
        })
    },

  },
}
</script>
<style lang="scss" scoped>
::v-deep .form-group {
  border: 1px solid #dddddd87;
  border-radius: 10px;
  padding: 0 15px 10px !important;
  /* box-shadow: -15px 27px 71px #00000010; */
  .inside-switch {
    margin-top: 0 !important;
  }
}
::v-deep .form-group input {
  border: none;
  color: #000;
  padding: 0;
  height: 20px;
  font-size: 16px;
}
::v-deep .form-group select {
  border: none;
  padding: 0;
  height: 20px;
}
::v-deep .form-group label {
  border: none;
  padding: 0;
  height: 20px;
  margin: 0;
  padding-top: 10px;
  font-size: 12px;
}
::v-deep .inner-form-group {
  .form-group {
    border: navajowhite;
    background: transparent;
    margin: 0 !important;
    padding: 0 !important;
    select {
      background: transparent;
      color: #000;
    }
  }
}
::v-deep .v-input__control {
  fieldset {
    border: navajowhite !important;
  }
  .v-input__slot {
    padding: 0 !important;
  }
}
::v-deep .form-group .v-input.c-form-control .v-input__slot {
  height: 20px !important;
  min-height: 20px !important;
}
::v-deep .form-group .v-input .v-input__slot .v-input__append-inner {
  z-index: 1 !important;
}
.v-tab--active {
  svg {
    fill: #1976d2;
  }
}
.tab-item {
  margin-right: 20px;
  font-size: 15px;
  font-weight: 400 !important;
}
.btn-round {
  border-radius: 25px;
}
::v-deep .form-group label {
  border: none;
  padding: 0;
  height: 20px;
  margin: 0;
  padding-top: 10px;
  font-size: 13px;
  font-weight: 400;
}
::v-deep .form-group select,
.v-input.v-select.v-input--dense {
  color: #000;
  font-size: 16px;
}
</style>
