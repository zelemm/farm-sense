<template>
    <v-app>
        <div class="auth-lang">
        </div>
        <div class="auth-page">
            <router-view></router-view>
        </div>

        <notifications :options="{}" />
    </v-app>
</template>

<script>
export default {
    components: {
    },

    data() {
        return {
            lang: window.lang,
            selectedLang: this.$cookies.get('lang') || 'en',
        }
    },
}
</script>
