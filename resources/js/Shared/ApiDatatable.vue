<template>
  <div class="w-full">
    <v-overlay :value="pageLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <div class="flex justify-between items-center flex-wrap flex-sm-nowrap mb-2">
      <div class="text-lg font-weight-bold">{{ heading }}</div>
      <div v-if="showAdd && createRoute" xs3>
        <v-btn
          width="40"
          height="30"
          min-width="30"
          color="indigo"
          :title="lang.get('form.button.add_new')"
          @click="getCreateLink"
        >
          <v-icon size="20" color="#fff" dark>
            mdi-plus
          </v-icon>
        </v-btn>
      </div>
        <div v-if="showAddModel" xs3>
            <v-btn
                width="40"
                height="30"
                min-width="30"
                color="indigo"
                :title="lang.get('form.button.add_new')"
                @click="itemActionClicked('handleGoogleFenceAdd', null)"
                @click.native.stop
            >
                <v-icon size="20" color="#fff" dark>
                    mdi-plus
                </v-icon>
            </v-btn>
        </div>
        <div v-if="mapAdjust && datas.length >= 0" xs3>
            <v-btn
                width="40"
                height="30"
                min-width="30"
                color="indigo"
                :title="lang.get('form.button.adjust_fence')"
                @click="itemActionClicked('adjustFarmFence', null)"
                @click.native.stop
            >
                <v-icon size="20" color="#fff" dark>
                    mdi-map
                </v-icon>
            </v-btn>
        </div>
    </div>

    <div v-if="extra_result && extra_result.po_number">
        <strong>{{ 'form.heading.purchase_order_number' | trans }}: {{ extra_result.po_number }}</strong>
    </div>

    <div class="order-1 order-sm-0 w-full sm:flex-1">
      <v-item-group class="flex flex-wrap mb-4 px-3">
        <v-row :class="filterFlex" class="align-end">
          <v-col v-if="datesFilter" cols="12" :lg="filterLg" md="4" sm="6">
            <div class="d-inline">
              <v-menu
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <div class="form-group mb-0 filter-form-group">
                    <label for="">{{ 'form.date' | trans }}</label>
                    <v-text-field
                      :value="dateRangeText"
                      persistent-hint
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      hide-details
                      clearable
                      v-on="on"
                      @click:clear="dateRange = null"
                    />
                  </div>
                </template>
                <v-date-picker
                  v-model="dateRange"
                  range
                  no-title
                  :locale="lang.locale"
                />
              </v-menu>
            </div>
          </v-col>

          <v-col v-if="usersFilter" cols="12" :lg="filterLg" md="4" sm="6">
            <MultipleSelectInput
              class="mb-0 filter-form-group"
              v-model="form.users"
              :items="users || []"
              :label="lang.get('form.filter.users')"
            />
          </v-col>

          <slot name="filter" />

          <v-col v-if="showFilter" cols="12" :lg="filterLg" md="4" sm="6">
            <SelectInput
              class="mb-0 filter-form-group"
              v-model="form.trashed"
              :items="[
                {value:null, text:'Active'},
                {value:'with',text:'With trashed'},
                {text:'Only trashed',value:'only'}
              ]"
              :label="lang.get('form.button.filter')"
              :clearable="true"
            />
          </v-col>
          <v-col v-if="showStatus" cols="12" :lg="filterLg" md="4" sm="6">
            <SelectInput
              class="mb-0 filter-form-group"
              v-model="form.status"
              :items="filteredStatus || []"
              item-text="text"
              item-value="value"
              :label="lang.get('form.heading.status')"
              :clearable="true"
            />
          </v-col>
          <v-col v-if="showStatuses" cols="12" :lg="filterLg" md="4" sm="6">
            <MultipleSelectInput
              class="mb-0 filter-form-group"
              v-model="form.statuses"
              :items="filteredStatus || []"
              item-text="text"
              item-value="value"
              :label="lang.get('form.heading.status')"
            />
          </v-col>
          <v-col v-if="showAction" cols="12" :lg="filterLg" md="4" sm="6">
            <SelectInput
              class="mb-0 filter-form-group"
              v-model="form.action"
              :items="actions || []"
              :label="lang.get('form.heading.action')"
              :clearable="true"
            />
          </v-col>
          <v-col v-if="showSearch" cols="12" :lg="filterLg" md="4" sm="6">
            <div class="form-group mb-0 filter-form-group">
              <label for="">{{ 'common.search' | trans }}</label>
              <v-text-field data-vv-delay="1000" :value="form.search" clearable append-icon="mdi-magnify" single-line @change="v=>form.search=v" @click:clear="form.search = ''" />
            </div>
          </v-col>
        </v-row>
      </v-item-group>
    </div>

    <v-flex xs12>
      <v-data-table
        v-model="selected"
        :headers="headers"
        :items="datas"
        item-key="id"
        class="w-full row-pointer custom-table"
        :loading="loading"
        :options.sync="options"
        :footer-props="footerProps"
        :server-items-length="totalData"
        :show-select="showSelect"
        :loading-text="lang.get('common.data_table.loading')"
        :no-data-text="lang.get('common.data_table.no_data')"
        @click:row="gotoEdit"
        :hide-default-footer="hideFooter"
      >
        <template slot="headers" slot-scope="props">
          <tr>
            <th v-for="header in props.headers" :key="header.value">
              {{ header.text }}
            </th>
          </tr>
        </template>

          <!-- Start Farm google centers -->
          <template #item.center_point="{item}">
              <template v-if="item.center_point && item.center_point.lat && item.center_point.lng">
                  <div>
                      (<span>{{ item.center_point.lat }}</span>, <span>{{ item.center_point.lng }}</span>)
                  </div>
              </template>
          </template>
          <!-- end Farm google centers -->

          <!-- Start Farm google fence coordinate color -->
          <template #item.fence_color="{item}">
              <template v-if="item.fence_color">
                  <div>
                      <v-badge v-if="item.fence_color" :color="item.fence_color"
                               @click.native="itemActionClicked('openAddColorModal', item)"
                      />
                  </div>
              </template>
          </template>
          <!-- end Farm google fence coordinate color -->

          <!-- Start Farm google fence coordinates -->
          <template #item.fence_coordinates="{item}">
              <template v-if="item.fence_coordinates && item.center_point.lat && item.center_point.lng">
                  <v-chip
                      v-for="(fence, index) in item.fence_coordinates"
                      :key="index"
                      class="mb-1"
                      color="secondary"
                      text-color="white"
                  >
                      (<span>{{ fence.lat }}</span>, <span>{{ fence.lng }}</span>)
                  </v-chip>
              </template>
          </template>
          <!-- end Farm google fence coordinates -->

          <!-- Farm Google link actions Start -->
          <template v-slot:item.google_map="{ item }">
              <template v-if="item.google_map != ''">
                  <v-btn
                      @click="itemActionClicked('showGoogle', item)"
                      @click.native.stop>
                      <v-icon size="24" color="blue" dark>mdi-google-maps</v-icon>
                  </v-btn>

              </template>
          </template>
          <!-- Farm Google link actions End -->

          <!-- Farm actions -->
          <template #item.farm_actions="{item}">
              <v-btn
                  :title="lang.get('form.button.link_google')"
                  class="success font-weight-bold text-capitalize mr-3 mb-3"
                  @click="itemActionClicked('linkGoogle', item)"
                  @click.native.stop
              >
                  <v-icon size="24" dark>mdi-google-maps</v-icon>
              </v-btn>
              <v-btn
                  :title="lang.get('menu.farm_fence')"
                  class="success font-weight-bold text-capitalize mr-3 mb-3"
                  @click="itemActionClicked('linkGoogle', item)"
                  @click.native.stop
              >
                  <v-icon size="24" dark>mdi-shape-polygon-plus</v-icon>
              </v-btn>
          </template>
          <!-- end Farm actions -->

          <!-- Farm Google actions -->
          <template #item.farm_google_actions="{item}">
              <v-btn
                  :title="lang.get('form.button.auth')"
                  class="success font-weight-bold text-capitalize mr-3 mb-3"
                  @click="itemActionClicked('handleGoogleAuth', item)"
                  @click.native.stop
              >
                  <span v-if="item.access_token == null || item.access_token == ''">
                      {{ 'form.button.auth' | trans }}
                  </span>
                  <span v-else>{{ 'form.button.reauth' | trans }}</span>

              </v-btn>
          </template>
          <!-- end Farm Google actions -->

          <!-- Farm Fence actions -->
          <template #item.farm_fence_coords_actions="{item}">
              <v-btn
                  v-if="item.latitude != '' && item.longitude != ''"
                  width="40" height="30" min-width="30"
                  @click="itemActionClicked('showGoogle', item)"
                  @click.native.stop
                  class="font-weight-bold text-capitalize mr-3 mb-3"
              >
                  <v-icon size="20" color="blue" dark>mdi-map</v-icon>
              </v-btn>
<!--              mdi-google-maps-->
<!--              <v-btn-->
<!--                  :title="lang.get('form.button.update')"-->
<!--                  width="40" height="30" min-width="30"-->
<!--                  class="success font-weight-bold text-capitalize mr-3 mb-3"-->
<!--                  @click="itemActionClicked('handleFenceEdit', item)"-->
<!--                  @click.native.stop-->
<!--              >-->
<!--                  <v-icon size="20" dark>-->
<!--                      mdi-pencil-->
<!--                  </v-icon>-->
<!--              </v-btn>-->
              <v-btn
                  v-if="!item.deleted_at"
                  width="40"
                  height="30" min-width="30" :title="lang.get('form.button.delete')"
                  class="error mr-3 mb-3"
                  @click="itemActionClicked('openDeleteFenceItem', item)"
                  @click.native.stop
              >
                  <v-icon size="20" dark>
                      mdi-delete
                  </v-icon>
              </v-btn>
              <v-btn
                  v-if="item.deleted_at"
                  width="40"
                  height="30" min-width="30" :title="lang.get('form.button.restore')"
                  class="error mr-3 mb-3"
                  @click="itemActionClicked('restoreFenceItem', item)"
                  @click.native.stop
              >
                  <v-icon size="20" dark>
                      mdi-restore
                  </v-icon>
              </v-btn>
          </template>
          <!-- end Farm Fence actions -->

        <template
          v-if="footerLink"
          v-slot:footer
        >
          <div class="footer-link">
            <router-link
              :to="{ name: footerLink }"
              class="underline footer-text-link"
            >
              {{ footerLinkText }}
            </router-link>
          </div>
        </template>
        <template #footer.page-text="props">
          {{ props.pageStart }}-{{ props.pageStop }} {{ 'common.data_table.of' | trans }} {{ props.itemsLength }}
        </template>

        <template #footer.page-text="props">
          <div class="flex-start">
            {{ props.pageStart }}-{{ props.pageStop }} {{ 'common.data_table.of' | trans }} {{ props.itemsLength }}
            <v-pagination
              class="ml-2"
              v-model="options.page"
              :length="lastPage"
              :total-visible="lastPage > 7 ? 7 : lastPage"
              circle
            ></v-pagination>
          </div>
        </template>
      </v-data-table>
    </v-flex>
  </div>
</template>

<script>
import moment from 'moment-timezone'
import numeral from 'numeral'
import throttle from 'lodash/throttle'
import axios from 'axios'
import FileDownload from 'js-file-download'

export default {
  name: 'ApiDatatable',
  components: {
    DownloadReportButton: () => import('@/Shared/DownloadReportButton'),
    Icon: () => import('@/Shared/Icon'),
    MultipleSelectInput: () => import('@/Common/Form/MultipleSelectInput'),
    SelectInput: () => import('@/Common/Form/SelectInput'),
    UserAvatar: () => import('@/Shared/UserAvatar'),
    VueBarcode: () => import('vue-barcode'),
  },
  props: {
    heading: String,
    headers: {
      type: Array,
      default: () => []
    },
    url: String,
    viewRoute: String,
    editRoute: String,
    createRoute: String,
    showAdd: {
      type: Boolean,
      default: true,
    },
    showAddModel: {
      type: Boolean,
      default: false,
    },
    showSelect: Boolean,
    showFilter: {
      type: Boolean,
      default: true,
    },
    mapAdjust: {
      type: Boolean,
      default: false,
    },
    showStatus: {
      type: Boolean,
      default: true,
    },
    showAction: Boolean,
    showSearch: {
      type: Boolean,
      default: true,
    },
    datesFilter: Boolean,
    usersFilter: Boolean,
    users: Array,
    requestParams: String,
    rememberParams: {
      type: Boolean,
      default: true,
    },
    avatar: Boolean,
    filterFlex: String,
    footerLink: String,
    footerLinkText: String,
    defaultDates: {
      type: Array,
      default: () => null
    },
    disableEdit: Boolean,
    hideFooter: Boolean,
    showHeadingAction: Boolean,
    showItemAction: Boolean,
    showStatuses: Boolean,
    filterLg: {
      type: [String, Number],
      default: 3,
    },
    editPermission: String,
    isSelectItem: Boolean,
    showItemServiceAction: Boolean,
  },
  data() {
    return {
      lang: lang,
      totalData: 0,
      datas: [],
      extra_result: [],
      loading: true,
      pageLoading: false,
      options: {},
      sortBy: 'name',
      sortDesc: false,
      selected: [],
      selectedItemIds: [],
      form: {
        search: null,
        trashed: null,
        status: null,
      },
      dateRange: this.datesFilter && this.defaultDates ? this.defaultDates : [
        moment().format('YYYY-MM-01'),
        moment().endOf('month').format('YYYY-MM-DD')
      ],
      footerProps: {
        'items-per-page-options': [10, 15, 25, 50, 100, -1],
        'items-per-page-text': lang.get('common.rows_per_page'),
        'items-per-page-all-text': lang.get('common.all'),
      },
      statusItems: [],
      loadingDeleteFile: null,
      actions: [],
      selectedData: [],
    }
  },

  computed: {
    authUser() {
      return this.$store.getters['auth/user']
    },

    dateRangeText() {
      return this.dateRange ? this.dateRange.join(' ~ ') : ''
    },

    lastPage() {
      return Math.ceil(this.totalData / this.options.itemsPerPage)
    },

    allParams() {
      let options = JSON.parse(JSON.stringify(this.options))

      options.sortDesc = options.sortDesc ? options.sortDesc.map(v => v ? 'desc' : 'asc') : []

      // all items per page
      if (options.itemsPerPage && options.itemsPerPage == '-1') {
        options.itemsPerPage = this.totalData
      }

      let param = ''+Object.keys(options).map(key => `${key}=${options[key]}`).join('&')

      for (let i in this.form) {
        if (this.form[i]) {
          param += `&${i}=${this.form[i]}`
        }
      }

      if(this.requestParams) {
        param += `&${this.requestParams}`
      }

      if (this.datesFilter && this.dateRange && this.dateRange.length == 2) {
        param += `&dates=${this.dateRange}`
      }

      // change url
      // if (this.rememberParams) {
      //   this.$router.push({path: `${window.location.pathname}?${param}`})
      //     .catch(()=>{});
      // }

      return param
    },

    filteredStatus() {
      let result = []

      this.statusItems.forEach((status, index) => {
        result[index] = {
          'text': status,
          'value': status,
        }
      })

      return result
    },

    salaryTotal() {
      return this.selectedData.reduce((total, id) => {
        let index = this.datas.findIndex(e => e.id === id)

        if (index !== -1 && this.datas[index].salary) {
          total += this.datas[index].salary
        }

        return total
      }, 0)
    },

    taxTotal() {
      return this.selectedData.reduce((total, id) => {
        let index = this.datas.findIndex(e => e.id === id)

        if (index !== -1 && this.datas[index] && this.datas[index].taxs && this.datas[index].taxs.length) {
          total += this.datas[index].taxs.reduce((total, tax) => {
            if (tax.amount) {
              total += parseInt(tax.amount)
            }

            return total
          }, 0)
        }
        return total
      }, 0)
    },
  },

  created() {
    // set default timezone
    moment.tz.setDefault("Australia/Sydney")

    // get query from local storage push to url
    if (this.rememberParams) {
      let url = `${window.location.pathname}?${window.location.search}`

      if (localStorage.getItem(this.url)) {
        url = `${window.location.pathname}?${localStorage.getItem(this.url)}`
        this.$router.push({path: url})
          .catch(()=>{});
        localStorage.removeItem(this.url)
      }

      // get query from url
      let params = new URL(`${window.location.origin}${url}`).searchParams

      this.form.search = this.showSearch ? params.get('search') : this.form.search
      this.form.trashed = this.showFilter ? params.get('trashed') : this.form.trashed
      this.form.status = this.showStatus ? params.get('status') : this.form.status
      this.dateRange = this.datesFilter && params.get('dates') ? params.get('dates').split(',') : this.dateRange

      if (this.datesFilter && !this.defaultDates && (!this.dateRange || !this.dateRange.length)) {
        this.dateRange = [
          moment().format('YYYY-MM-01'),
          moment().endOf('month').format('YYYY-MM-DD'),
        ]
      }

      this.options.itemsPerPage = params.get('itemsPerPage') ? parseInt(params.get('itemsPerPage')) : 10
      this.options.page = params.get('page') ? parseInt(params.get('page')) : 1
    }
  },

  watch: {
    options: {
      handler () {
        this.getDataFromApi()
      },
      deep: true,
    },
    form: {
      handler: throttle(function() {
        this.getDataFromApi()
      }, 150),
      deep: true,
    },
    selected: {
      handler: throttle(function() {
        var app = this
        app.selectedItemIds = []
        var selectedItems = this.selected

        if (!this.isSelectItem) {
          Object.keys(selectedItems).forEach(function(key) {
            app.selectedItemIds.push(selectedItems[key].id)
          })

          this.$emit('selectedItems', app.selectedItemIds)
        } else {
          this.$emit('selectedItems', selectedItems)
        }
      }, 150),
      deep: true,
    },

    requestParams: {
      handler () {
        this.getDataFromApi()
      },
      deep: true,
    },

    dateRange(val) {
      if (!val || val.length == 2) {
        this.getDataFromApi()
      }
    },
  },

  methods:{
    formatNumeral(num) {
      return numeral(num).format('$0,0.00')
    },
    formatDate(date, format = 'DD-MMM-YYYY') {
      return moment(date, 'YYYY-MM-DD').isValid() ? moment(date, 'YYYY-MM-DD').format(format) : date
    },
    getDataFromApi () {
      return new Promise((resolve, reject) => {
        this.loading = true
        this.selected = []
        let url = ''+this.url
        if(url.indexOf('?') > -1){
          url = `${this.url}&${this.allParams}`
        }
        else{
          url = `${this.url}?${this.allParams}`
        }

        axios.get(url)
          .then(res => {
            if (res && res.data) {
              this.datas =  res.data.data || []
              this.extra_result = res.data.extra_result

              if (res.data.meta) {
                this.totalData = res.data.meta.total || 0

                if (res.data.meta.status && !this.statusItems.length) {
                  this.statusItems = res.data.meta.status
                }

                if (res.data.meta.actions && !this.actions.length) {
                  this.actions = res.data.meta.actions
                }
              } else {
                this.totalData = this.datas.length
              }
            }
            this.loading = false
          })
          .catch(() => {
            this.loading = false
          })
      })
    },

    gotoEdit(value) {
      let id = value.id

      if(this.viewRoute) {
        this.pageLoading = true

        if (this.viewRoute == `${this.authUser.role}.appointments` && value.appointment_id) {
          id = value.appointment_id
        }

        localStorage.setItem(this.url, this.allParams)
        this.$router.push({name: `${this.viewRoute}.show`, params: {id: id}});
      }

      if (this.editRoute) {
        if (this.editPermission) {
          if (
            !this.canAccess(`${this.editPermission}-all`, this.authUser) &&
            (!this.canAccess(this.editPermission, this.authUser) ||
            !(this.authUser.origin_role == 'super_admin' || value.added_by == this.authUser.id))
          ) {
            return
          }
        }

        this.pageLoading = true

        localStorage.setItem(this.url, this.allParams)
        this.$router.push({name: this.editRoute, params: { id: id }});
      }
    },

    getCreateLink() {
      if (this.createRoute) {
        this.$router.push({ name: this.createRoute })
      }
    },

    downloadFile(path) {
      if (!path) return;

      let names = path.split('/')

      axios.get(`/files/download?path=${path}`, {
          responseType: 'blob',
        })
        .then(res => {
          FileDownload(res.data, names[names.length - 1])
        })

      this.$notify({
        title: lang.get('form.success'),
        type: 'success',
        text: this.lang.get('form.files_lang.downloaded'),
      })
    },

    deleteFile(item, type = 'report') {
      let _this = this
      _this.loadingDeleteFile = item.id

      axios.post('/files/delete', {
          job_report_id: item.id,
          type: type,
        })
        .then(() => {
          _this.loadingDeleteFile = null

          _this.getDataFromApi()
        })
        .catch(() => {
          _this.loadingDeleteFile = null
        })
    },

    // payslip
    selectAll() {
      let _this = this

      _this.$nextTick(() => {
        if (_this.selectedData.length) {
          _this.selectedData = []
        } else {
          _this.selectedData = _this.datas.map(function(item) {
            return item.id
          })
        }

        this.$emit('selectedData', {ids: this.selectedData, salaryTotal: this.salaryTotal, taxTotal: this.taxTotal})
      })
    },

    selectItem(id) {
      this.$emit('selectedData', {ids: this.selectedData, salaryTotal: this.salaryTotal, taxTotal: this.taxTotal})
    },

    headingActionClicked(action) {
      this.$emit(action, this.datas)
    },

    itemActionClicked(action, item) {
      this.$emit(action, item)
    },

    isAfterNow(date) {
      let now = moment().format('YYYY-MM-DD'),
          dateFormat = moment(date, 'DD/MM/YYYY').format('YYYY-MM-DD')

      return moment(dateFormat, 'YYYY-MM-DD').isAfter(now)
    },
  },
}
</script>

<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
    cursor: pointer;
}
.footer-link {
  margin: 25px 0px -49px 15px;
}
@media (max-width: 767px) {
  .footer-link {
    margin: 0;
    font-size: 13px;
    padding: 15px;
    border-top: thin solid rgba(0,0,0,.12);
  }
}
</style>
