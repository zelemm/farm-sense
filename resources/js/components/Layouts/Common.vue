<template>
    <v-app>
        <Layout />
    </v-app>
</template>

<script>
export default {
    components: {
        Layout: () => import('@/Shared/Layout'),
    },
}
</script>
