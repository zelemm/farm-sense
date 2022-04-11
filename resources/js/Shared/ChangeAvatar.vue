<template>
  <div class="change-avatar-container">
    <UserAvatar v-if="!iValue" size="96" :name="name" />
    <div v-else class="mt-2">
      <v-img class="v-avatar" :src="previewAvatar" style="height: 6rem !important; min-width: 6rem !important; width: 6rem !important;" />
    </div>

    <span v-if="deleteUrl && previewAvatar" class="delete-avatar" @click="removeAvatar">
      <i class="mdi mdi-delete" />
    </span>

    <div class="change-avatar-action">
      <v-icon color="white">mdi-camera</v-icon>
    </div>

    <input class="avatar-file" type="file" accept="image/*"  @change="changeAvatar">
  </div>
</template>

<script>
import UserAvatar from '@/Shared/UserAvatar'
import axios from 'axios'

export default {
  components: {
    UserAvatar,
  },

  props: {
    value: {
      type: [File, String, null]
    },

    src: {
      type: [String, null]
    },

    name: {
      type: [String, null]
    },

    deleteUrl: {
      type: [String, null]
    },
  },

  data() {
    return {
      previewAvatar: null,
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

  mounted() {
    this.previewAvatar = this.value
  },

  methods: {
    changeAvatar(event) {
      if (!event.target.files.length) return
      let avatarFile = event.target.files[0]
      this.iValue = avatarFile

      const reader = new FileReader()
      reader.onload = (e) => {
        this.previewAvatar = e.target.result
      }
      reader.readAsDataURL(avatarFile)
    },

    removeAvatar() {
      if (this.deleteUrl && this.previewAvatar && this.previewAvatar.match(/^http/)) {
        axios.delete(this.deleteUrl)
          .catch(() => {})
      }

      this.iValue = null
      this.previewAvatar = null
    },
  },
}
</script>

<style lang="scss" scoped>
.change-avatar-container {
  position: relative;
  display: inline-block;
  .change-avatar-action {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0,0,0,.5);
    color: #f1f1f1;
    font-size: 45px;
    transform: scale(0);
    border-radius: 0 0 200px 200px;
  }
  &:hover {
    .change-avatar-action {
        transform: scale(1);
    }
  }
}
.delete-avatar {
  color: #d40404;
  font-size: 26px;
  opacity: 0.7;
  position: absolute;
  top: -5px;
  right: -5px;
  cursor: pointer;
  z-index: 4;
  &:hover {
    opacity: 1;
  }
}

.avatar-file {
  position: absolute;
  z-index: 1;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  opacity: 0;
  cursor: pointer;
}
</style>