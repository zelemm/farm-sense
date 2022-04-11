<template>
  <div class="relative">
    <v-avatar
      v-if="!src"
      :color="background"
      :size="size"
      :style="{ fontSize: `${size / 2}px`}"
      :rounded="rounded"
      class="image-avatar"
    >
      <span :style="{ color: color }">{{ avatarName }}</span>
    </v-avatar>
    <img v-else class="image-avatar v-avatar" :src="src" :style="{ width: `${size}px`, height: `${size}px` }">

    <span
      v-if="showStatus"
      :class="`user-status ${statusClass}`"
    ></span>
  </div>
</template>

<script>

export default {
  props: {
    size: {
      type: [String, Number, null],
      default: 32,
    },

    name: {
      type: [String, null]
    },

    src: {
      type: [String, null]
    },

    rounded: {
      type: Boolean,
      default: false,
    },

    background: {
      type: String,
      default: '#EBF4FF'
    },

    color: {
      type: String,
      default: '#7F9CF5'
    },
    showStatus: Boolean,
    statusClass: String,
  },

  computed: {
    avatarName() {
      let avatarName = 'AV'

      if (this.name) {
        let words = this.name.split(/\s/)
        avatarName = words[0][0].toLocaleUpperCase()

        if (words.length > 1) {
          avatarName += words[words.length - 1][0].toLocaleUpperCase()
        }
      }

      return avatarName
    },
  },
}
</script>

<style lang="scss" scoped>
  .image-avatar {
    object-fit: cover;
  }
  .user-status {
    display: inline-block;
    position: absolute;
    height: 12px;
    width: 12px;
    background-color: #ced4da;
    border: 2.5px solid #fff;
    border-radius: 50%;
    bottom: 0;
    left: 35px;
    &.online {
      background: #43d39e;
    }
    &.away {
      background-color: #ffbe0b;
    }
  }
</style>