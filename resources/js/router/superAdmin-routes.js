import lazyLoading from './lazyLoading'
import CommonLayout from '../components/Layouts/Common'
import SuperAdminGuard from './guards/super_admin-guard'

export default [
  {
    path: '/super_admin',
    component: CommonLayout,
    beforeEnter: SuperAdminGuard,
    children: [
      {
        name: 'super_admin.dashboard',
        path: 'dashboard',
        component: lazyLoading('SuperAdmin/Dashboard'),
      },
      {
        name: 'super_admin.myProfile',
        path: 'myProfile',
        component: lazyLoading('SuperAdmin/Profile/Index'),
      },
      {
        name: 'super_admin.farms',
        path: 'farms',
        component: lazyLoading('SuperAdmin/Farms/Index'),
      },
      {
        name: 'super_admin.farms.create',
        path: 'farms/create',
        component: lazyLoading('SuperAdmin/Farms/Create'),
      },
      {
        name: 'super_admin.farms.edit',
        path: 'farms/:id/edit',
        component: lazyLoading('SuperAdmin/Farms/Edit'),
      },
      {
        name: 'super_admin.cattle',
        path: 'cattle',
        component: lazyLoading('SuperAdmin/Cattle/Index'),
      },
      {
        name: 'super_admin.cattle.create',
        path: 'cattle/create',
        component: lazyLoading('SuperAdmin/Cattle/Create'),
      },
      {
        name: 'super_admin.cattle.edit',
        path: 'cattle/:id/edit',
        component: lazyLoading('SuperAdmin/Cattle/Edit'),
      },
      {
        name: 'super_admin.cattle_location',
        path: 'cattle_location',
        component: lazyLoading('SuperAdmin/CattleLocation/Index'),
      },
      {
        name: 'super_admin.farm_google',
        path: 'farm_google',
        component: lazyLoading('SuperAdmin/Google/Index'),
      },
      {
        name: 'super_admin.farm_google.create',
        path: 'farm_google/create',
        component: lazyLoading('SuperAdmin/Google/Create'),
      },
      {
        name: 'super_admin.farm_google.edit',
        path: 'farm_google/:id/edit',
        component: lazyLoading('SuperAdmin/Google/Edit'),
      },
      {
        name: 'super_admin.farm_fence',
        path: 'farm_fence',
        component: lazyLoading('SuperAdmin/Fence/Index'),
      },
      {
        name: 'super_admin.farm_fence.create',
        path: 'farm_fence/create',
        component: lazyLoading('SuperAdmin/Fence/Create'),
      },
      {
        name: 'super_admin.farm_fence.edit',
        path: 'farm_fence/:id/edit',
        component: lazyLoading('SuperAdmin/Fence/Edit'),
      },
    ],
  },
]
