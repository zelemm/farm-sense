import Vue from 'vue'
import Vuex from 'vuex'

import authModule from './modules/auth-module'
import userModule from './modules/user-module'
import superAdminModule from './modules/super_admin-module'
import commonModule from './modules/common-module'
import farmModule from './modules/farms-module'
import cattleModule from "./modules/cattle-module";
import farmGoogleModule from "./modules/farm-google-module";
import farmFenceModule from "./modules/farm-fence-module";

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: false,
    modules: {
        auth: authModule,
        super_admin: superAdminModule,
        user: userModule,
        common: commonModule,
        farms: farmModule,
        cattle: cattleModule,
        farmGoogle: farmGoogleModule,
        farmFence: farmFenceModule,
    },
})
export default store
