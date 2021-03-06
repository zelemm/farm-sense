<template>
  <div>
    <template v-if="isImage">
      <h4 class="mb-4">{{ label }}:</h4>
      <div class="flex-start flex-wrap images-list">
        <div
          role="button"
          class="relative images-item"
        >
          <template v-if="path">
            <v-img
              :lazy-src="path"
              height="150"
              :src="path"
              @click="showImage(path)"
            ></v-img>

            <v-icon
              v-if="remove"
              class="remove-image-icon"
              color="white"
              @click.stop.prevent="removeImage"
              :title="lang.get('form.button.delete')"
            >mdi-delete</v-icon>
          </template>
        </div>
      </div>

      <v-dialog
        v-model="imageModal"
        max-width="1000"
        width="initial"
      >
        <div class="card">
          <v-btn
            class="close-dialog-icon"
            icon
            @click.native="imageModal = false"
            :title="lang.get('form.button.close')"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <div class="card-body">
            <h5 class="card-title mt-0 mb-4 header-title">
              {{ 'form.heading.image' | trans }}
            </h5>

            <v-img
              v-if="imageUrl"
              :lazy-src="imageUrl"
              :src="imageUrl"
              contain
            ></v-img>
          </div>
        </div>
      </v-dialog>
    </template>
    <template v-else-if="name && !removed">
      <div class="relative">
        <p>
          <strong>{{ 'form.heading.file_name' | trans }}:</strong> {{ name }}
          <v-icon
            :title="lang.get('form.button.download')"
            class="ml-3"
            color="success"
            @click="downloadFile"
          >mdi-cloud-download</v-icon>
        </p>
        <v-icon
          v-if="remove && this.path"
          class="remove-image-icon"
          color="white"
          @click.stop.prevent="removeImage"
          :title="lang.get('form.button.delete')"
        >mdi-delete</v-icon>
      </div>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
import FileDownload from 'js-file-download'

export default {
  props: {
    path: String,
    name: String,
    remove: Boolean,
    removed: Boolean,
    label: {
      type: String,
      default: window.lang.get('form.heading.attach')
    }
  },

  data() {
    return {
      lang: window.lang,
      imageUrl: null,
      imageModal: false,
      loadingDownloadFile: false,
    }
  },

  computed: {
    isImage() {
      if(!this.path || typeof this.path !== 'string') return false;


      return this.path.match(/^http[^\?]*.(jpg|jpeg|gif|png|tiff)(\?(.*))?$/g) !== null
    },
  },

  methods: {
    showImage(url) {
      this.imageUrl = url
      this.imageModal = true
    },

    downloadFile() {
      if (!this.name || this.loadingDownloadFile) return
      this.loadingDownloadFile = true

      let names = this.name.split('/')

      axios.get(`/files/download?path=${this.name}`, {
          responseType: 'blob',
        })
        .then(res => {
          this.loadingDownloadFile = false
          FileDownload(res.data, names[names.length - 1])

          this.$notify({
            title: lang.get('form.success'),
            type: 'success',
            text: this.lang.get('form.files_lang.downloaded'),
          })
        })
        .catch(() => {
          this.loadingDownloadFile = false
          this.$notify({
            title: lang.get('form.error'),
            type: 'error',
            text: this.lang.get('form.files_lang.not_found'),
          })
        })
    },

    removeImage() {
      this.$emit('removeImage')
    },
  }
}
</script>
<style lang="scss" scoped>
  .close-dialog-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
  }

  .remove-image-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: .5;
    z-index: 1;
    width: 18px;
    height: 18px;
    border-radius: 5px;
    font-size: 14px;
    background-color: red;
    &:hover {
      opacity: 1;
    }
  }

  .images-list {
    .images-item {
      width: 250px;
      @media (max-width: 767px) {
        width: calc(50% - 8px);
        .v-image {
          height: 120px !important;
        }
      }
    }
  }
</style>