<template>
    <v-app class="super_admin-dashboard-page">
        <div v-if="authUser">
            <div class="p-2 mt-2 panel-head">
                <v-row class="mb-2" no-gutters>
                    <v-col cols="12" lg="4" md="4" sm="6">
                        <h1>Dashboard</h1>
                    </v-col>

                </v-row>

            </div>
        </div>
    </v-app>
</template>

<script>
import moment from 'moment'

export default {
    metaInfo: { title: lang.get('menu.dashboard') },

    components: {
        Icon: () => import('@/Shared/Icon'),
    },

    data() {
        return {
            lang: lang,
            feelFlag: false,
            model: 0,
        }
    },

    computed: {
        authUser() {
            return this.$store.getters['auth/user']
        },
    },

    mounted() {
    },

    methods: {
    },
}
</script>

<style lang="scss">
.event-box {
    border: 1px solid #e6e6e6;
    padding: 20px;
    border-radius: 16px;
    .msg-count-num {
        display: block;
        position: relative;
        p {
            margin: 0;
            width: fit-content;
            line-height: 10px;
            font-size: 10px;
            background: red;
            color: #fff;
            padding: 2px 4px;
            font-weight: 700;
            border-radius: 50%;
            position: absolute;
            top: -6px;
            right: -5px;
            border: 2px solid #fff;
        }
    }
    .event-desc {
        flex-grow: 1;
        .event-item {
            span.title {
                font-size: 17px !important;
                color: #989898;
                flex: 0 0 115px;
                display: inline-block;
            }
            span.data-value {
                font-size: 16px !important;
                color: #4d4f5c;
            }
            .color-blue {
                color: #3696cd !important;
                font-weight: 700;
            }
        }
    }
    .goto-info {
        background: #fff;
        height: 34px;
        width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 15px;
        cursor: pointer;
        svg {
            fill: #ccc;
        }
    }
}
.msg-count-display {
    cursor: pointer;
    height: 34px;
    width: 34px;
    display: flex;
    background-color: #74d58e;
    border-radius: 50%;
    box-shadow: 0px 50px 77px #0000003e;
    justify-content: center;
    align-items: center;
    img {
        height: 18px;
        width: 18px;
    }
}
.compromissios-table,
.exams-table {
    tbody tr td:nth-child(2) {
        color: #3696cd !important;
    }
}
.super_admin-dashboard-page {
    .upcoming-box {
        .item-label {
            flex: 0 1 130px;
        }
    }
    .banner-box {
        .banner-text {
            white-space: unset;
        }
        .banner-action {
            max-width: 350px;
            margin: -110px auto 0;
        }
    }
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
.v-window__prev,
.v-window__next {
    display: none;
}
// .v-carousel__controls {
//   position: relative;
// }
.text-h2 {
    margin: 0 15px;
}
.v-carousel,
.v-responsive {
    height: auto !important;
}
.v-carousel__controls {
    background: #f1f2f5;
    height: 30px;
    button {
        margin: 0;
    }
}
.v-carousel__controls {
    .v-item--active {
        .v-btn__content {
            i {
                color: #8080808f;
            }
        }
    }
    .v-btn--icon {
        .v-btn__content {
            i {
                color: #d2d3d4;
            }
        }
    }
}
.panel-head {
    > .col-3 {
        flex: 0 0 30%;
        max-width: 30%;
    }
    > .col-9 {
        flex: 0 0 70%;
        max-width: 70%;
    }
    h1 {
        font-size: 25px;
        font-weight: 700;
        color: #000;
        padding-bottom: 12px;
    }
}

.panel-box {
    background: linear-gradient(57.32deg, #6ec1e4 5.99%, #1f52e6 107.36%);
    min-height: 280px;
    background-color: transparent;
    border-radius: 25px;
    display: flex;
    @media (max-width: 530px) {
        display: block;
        position: relative;
    }
    .panel-content {
        padding: 24px 0px 24px 24px;
        @media (max-width: 530px) {
            max-width: 64%;
        }
    }
    h5 {
        font-size: 25px;
        color: #fff;
        font-weight: 700;
        @media (max-width: 530px) {
            font-size: 20px;
        }
        @media (max-width: 380px) {
            font-size: 16px;
        }
        span {
            color: #79e5ff;
        }
    }
    p {
        font-size: 17px;
        font-weight: 300;
        color: #fff;
        line-height: 12px;
        @media (max-width: 530px) {
            font-size: 16px;
        }
        @media (max-width: 380px) {
            line-height: 18px;
            margin-bottom: 10px;
        }
    }
    a {
        @media (max-width: 530px) {
            height: 45px !important;
            min-width: 65px !important;
        }
        span {
            font-size: 12px;
        }
    }
    button {
        background-color: var(--white);
        border: 1px solid #efecec;
        border-radius: 32px;
        box-shadow: -4px 11px 30px #00000015;
        font-size: 17px;
        min-height: 50px;
        span {
            color: #4d4f5c;
            text-transform: initial;
            svg {
                fill: #ddd;
                margin-left: 30px;
            }
        }
    }
    .panel-image {
        img {
            max-height: 280px;
            height: 100%;
            width: fit-content;
        }
        @media (max-width: 530px) {
            position: absolute;
            top: 0;
            right: -27px;
            width: 166px;
            opacity: 0.8;
        }
    }
}
.calendar-module {
    .v-picker.v-card {
        margin: 60px 15px 0;
        border-radius: 16px;
    }
    .v-picker__title {
        display: none;
    }
    .v-picker__body {
        width: fit-content !important;
    }
    .v-date-picker-table__current {
        color: #ffffff !important;
        caret-color: #4485e5 !important;
        background: #4a8ee5;
        border-radius: 10px;
    }
    .v-picker__body {
        border-radius: 16px;
    }
    .theme--light.v-date-picker-table th {
        color: rgb(0 0 0);
        font-weight: 700;
    }
    .v-date-picker-table tr {
        height: 40px;
    }
    .v-date-picker-table {
        height: auto;
    }
}
.v-window__container {
    .footer-link {
        a {
            background: linear-gradient(72.89deg, #4baed8 7.39%, #2854e6 108.57%);
            color: #fff !important;
            text-decoration: none;
            padding: 11px 22px;
            border-radius: 32px;
        }
    }
}
.banner-img {
    span {
        position: relative;
        display: block;
        img {
            width: 100%;
            border-radius: 20px;
        }
        &.desktop-screen a {
            position: absolute;
            bottom: 13%;
            height: 21% !important;
            left: 3%;
            width: 18%;
            border-radius: 31px;
            background: transparent !important;
            box-shadow: none;
            cursor: pointer;
            display: block;
        }
        &.mobile-screen a {
            position: absolute;
            bottom: 52.5%;
            height: 8.5% !important;
            left: 6%;
            width: 51%;
            border-radius: 31px;
            background: transparent !important;
            box-shadow: none;
            cursor: pointer;
            display: block;
        }
    }
}
span.mobile-screen {
    display: none;
}

.v-carousel__controls {
    position: absolute;
    background: transparent;
    bottom: 8px;
    @media (max-width: 350px) {
        bottom: 0px;
    }
}
.profile-box {
    border-radius: 16px;
    border: 1px solid #2854e6;
    min-height: 280px;
    .profile-description {
        margin: 0 auto;
        max-width: 300px;
        .profile-item {
            margin-bottom: 15px;
            .item-title {
                display: inline-block;
                text-align: left !important;
            }
            .item-value {
                font-size: 16px !important;
                color: #4d4f5c;
            }
        }
    }
}

@media (max-width: 1300px) {
    .super_admin-dashboard-page {
        .banner-box {
            .schedule-exam-bg {
                display: flex;
                flex-wrap: wrap;
                .banner-action,
                .banner-content {
                    max-width: 50%;
                    margin: 0;
                }
            }
        }
    }
}
@media (max-width: 767px) {
    span.mobile-screen {
        display: block;
    }
    span.desktop-screen {
        display: none;
    }
    .super_admin-dashboard-page {
        .banner-box {
            .schedule-exam-bg {
                display: flex;
                flex-wrap: wrap;
                .banner-action,
                .banner-content {
                    text-align: center;
                    max-width: 100%;
                    width: 100%;
                }
            }
        }
    }
}
</style>
