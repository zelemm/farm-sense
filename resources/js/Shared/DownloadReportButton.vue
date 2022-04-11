<template>
  <div ref="downloadReportButton" class="d-inline-block">
    <v-btn
      @click.native="downloadReport"
      :loading="loadingDownloadReport"
      :title="lang.get('common.download_report')"
      class="mr-3 mb-3"
      :class="isIcon ? ['success', 'font-weight-bold', 'text-capitalize'] : []"
      :color="isIcon ? '' : 'primary'"
      :outlined="isIcon ? false : true"
      :width="isIcon ? 40 : ''"
      :height="isIcon ? 30 : ''"
      :min-width="isIcon ? 30 : ''"
      small
    >
      <v-icon v-if="isIcon" size="20" dark>
        mdi-cloud-download
      </v-icon>
      <template v-else>
        {{ 'common.download_report' | trans }}
      </template>
    </v-btn>
  </div>
</template>

<script>
import axios from 'axios'
import FileDownload from 'js-file-download'

export default {
  props: {
    appointmentId: {String, Number},
    examSampleId: {String, Number},
    isIcon: {
      type: Boolean,
      default: true,
    },
    scrollTo: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      lang: window.lang,
      loadingDownloadReport: false,
    }
  },

  computed: {
    isMobile() {
      return this.$vuetify.breakpoint.xsOnly
          ? true
          : false;
    }
  },

  mounted() {
    if (this.isMobile && this.scrollTo) {
      this.scroll()
    }
  },

  methods: {
    downloadReport() {
      let _this = this
      if (!_this.appointmentId && !_this.examSampleId) return
      _this.loadingDownloadReport = true

      let url = _this.examSampleId ? `/exams/exam-samples/${_this.examSampleId}/download-report` : `/appointments/${_this.appointmentId}/download-report`

      axios
        .get(url, {
          responseType: 'blob',
        })
        .then(res => {
          let disposition = res.headers['content-disposition'];

          if (disposition) {
            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/,
                matches = filenameRegex.exec(disposition),
                filename

            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '')

            FileDownload(res.data, filename)

            _this.loadingDownloadReport = false

            _this.$notify({
              title: lang.get('form.success'),
              type: 'success',
              text: _this.lang.get('form.files_lang.downloaded'),
            })
          }
        })
        .catch(() => {
          _this.loadingDownloadReport = false
        })
    },

    scroll() {
      if (this.$refs['downloadReportButton']) {
        this.$refs['downloadReportButton'].scrollIntoView({
          behavior: "smooth"
        })
      }
    },
  },
}
</script>
