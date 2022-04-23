<template>
  <div>
    <v-app v-if="authUser" id="sense-your-farm">
      <v-overlay :value="pageLoading">
        <v-progress-circular
          indeterminate
          size="64"
        />
      </v-overlay>

      <!-- Sidebar menu -->
      <div class="left-side-menu" :class="{ show: isShowSubMenu }">
        <div class="sidebar-content">
          <!--- Sidemenu -->
          <div id="sidebar-menu" class="slimscroll-menu">
            <div class="sidebar-logo py-5">
              <router-link
                :to="{
                  name: `${authUser.role}.dashboard`,
                }"
                class="logo-box flex-center"
                @click.native="goToPage(`${authUser.role}.dashboard`)"
              >
                <img src="/img/maintainme-icon.png" class="text-white">
              </router-link>
            </div>
            <ul id="menu-bar" class="metismenu flex-column">
              <li
                v-for="(item, index) in menuItems"
                :key="`sidebar-item-${authUser.role}-${index}`"
                class="item"
                :class="{
                  active:
                    (item.to && currentRoute && currentRoute == item.to) ||
                    (item.menu && subMenu && subMenu.title == item.menu.title),
                }"
              >
                <template v-if="!item.hide">
                  <v-tooltip right>
                    <template v-slot:activator="{ on, attrs }">
                      <a
                        v-if="item.multiple"
                        v-bind="attrs"
                        class="icon-item"
                        @click="showSubMenu(item.menu)"
                        v-on="on"
                      >
                        <span v-if="item.user">
                          <icon :name="item.icon" />
                        </span>
                        <span v-else><v-icon>{{ item.icon }}</v-icon></span>
                        <p>{{ item.title }}</p>
                      </a>

                      <span v-else v-bind="attrs" class="icon-item" v-on="on">
                        <router-link
                          :to="{ name: item.to }"
                          @click.native="goToPage(item.to)"
                        >
                          <span v-if="item.user">
                            <icon :name="item.icon" />
                          </span>
                          <span v-else><v-icon>{{ item.icon }}</v-icon></span>

                          <p>{{ item.title }}</p>
                        </router-link>
                      </span>
                    </template>

                    <span>{{ item.title }}</span>
                  </v-tooltip>
                  <span v-if="item.badge" class="menu-bar-badge">{{ item.badge }}</span>
                </template>
              </li>
            </ul>

          </div>
        </div>
      </div>

      <!-- Sidebar main menu -->
      <div
        class="sidebar-main-menu scrollable"
        :class="{ show: isShowSubMenu && subMenu }"
      >
        <div v-if="subMenu" class="simplebar-content">
          <div class="simplebar-menu">
            <div class="simplebar-item">
              <h5 class="menu-title">{{ subMenu.title }}</h5>
              <ul class="item-menu-list">
                <li
                  v-for="(item, index) in subMenu.items"
                  :key="`submenu-item-${authUser.role}-${index}`"
                  class="menu-item"
                  :class="{
                    active: currentRoute == item.to,
                    'sub-title': item.isSubTitle,
                  }"
                >
                  <template v-if="!item.hide">
                    <h5 v-if="item.isSubTitle" class="menu-title">{{ item.title }}</h5>
                    <router-link
                      v-else
                      :to="{ name: item.to }"
                      @click.native="goToPage(item.to, true)"
                    >
                      <span>{{ item.title }}</span>
                    </router-link>
                  </template>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Header -->
      <div class="header-box">
        <div class="header-inner">
          <div class="left-box flex-start">
            <ul class="flex-row pl-0 pr-4">
              <li class="">
                <button
                  class="button-menu-mobile open-left disable-btn"
                  @click="mobileShowSubMenu"
                >
                  <v-icon v-if="isShowSubMenu && subMenu" class="close-icon">
                    mdi-close
                  </v-icon>
                  <v-icon v-else class="menu-icon">mdi-menu</v-icon>
                </button>
              </li>
            </ul>

            <router-link
              :to="{
                name: `${authUser.role}.dashboard`,
              }"
              class="navbar-brand mr-0 mr-md-2 logo-box flex-between"
              @click.native="goToPage(`${authUser.role}.dashboard`)"
            >
              <span class="logo">
                <logo class="fill-white" width="40" height="30" />
              </span>
                <span>
                    Sense Your Farm
                </span>
            </router-link>
          </div>
          <div class="mt-1 d-none d-sm-block">{{ authUser.account_name }}</div>
          <div class="right-box flex-start">

            <div class="menu-item item-fullscreen">
              <a class="icon-item" @click="toggleFullScreen">
                <v-icon>mdi-fullscreen</v-icon>
              </a>
            </div>

            <div class="user-profile menu-item flex-center">
              <div class="custom-dropdown profile-dropdown-menu flex-center">
                <a
                  class="custom-dropdown-toggle mr-0 flex-start"
                  @click="openProfileMenu"
                >
                  <UserAvatar
                    size="32"
                    :src="authUser.name_url"
                    :name="authUser.name"
                  />
                  <div class="user-detail d-none d-sm-block">
                    <h6 class="pro-user-name mt-0 mb-0 text-truncate">
                      {{ authUser.name }}
                    </h6>
                  </div>
                  <icon class="d-none d-sm-block" name="cheveron-down" />
                </a>
                <div
                  class="custom-dropdown-menu profile-dropdown"
                  :class="{ show: showProfileMenu }"
                >
                  <router-link
                    class="dropdown-item"
                    :to="{ name: `${authUser.role}.myProfile` }"
                    @click.native="handleCloseDropdown"
                  >
                    <v-icon class="mr-2">mdi-account</v-icon>
                    <span>{{ 'menu.profile' | trans }}</span>
                  </router-link>

                  <div class="dropdown-divider" />

                  <a class="dropdown-item" @click="logout">
                    <v-icon class="mr-2">mdi-logout</v-icon>
                    <span>{{ 'menu.logout' | trans }}</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        class="main-content"
        :class="{
          'show-sidebar': isShowSubMenu && subMenu,
          'mobile-show': isShowSubMenu,
        }"
      >
        <div class="content-box">
          <router-view />
        </div>
      </div>
    </v-app>

    <notifications :options="{}" />
  </div>
</template>

<script>
export default {
  components: {
    Icon: () => import('@/Shared/Icon'),
    Logo: () => import('@/Shared/Logo'),
    UserAvatar: () => import('@/Shared/UserAvatar'),
  },
  data() {
    return {
      lang: window.lang,
      showUserMenu: false,
      accounts: null,
      showSideBar: false,
      showProfileMenu: false,
      currentRoute: this.$route.name,
      subMenu: null,
      isShowSubMenu: false,
      pageLoading: false,
      enableChat: 0,
    }
  },
  computed: {

    menuItems() {
      let items = []
      window.lang.setLocale(this.authUser.lang)


      switch (this.authUser.role) {
      case 'super_admin':
        items = [
            {
                title: lang.get('menu.dashboard'),
                to: 'super_admin.dashboard',
                icon: 'painel',
                user: 'super_admin',
            },
            {
                title: lang.get('menu.farms'),
                to: 'super_admin.farms',
                icon: 'mdi-panorama',
            },
            {
                title: lang.get('menu.cattle'),
                to: 'super_admin.cattle',
                icon: 'mdi-cow',
            },
            {
                title: lang.get('menu.cattle_location'),
                to: 'super_admin.cattle_location',
                icon: 'mdi-map',
            },
            {
                title: lang.get('menu.farm_google'),
                to: 'super_admin.farm_google',
                icon: 'mdi-google',
            },
            {
                title: lang.get('menu.farm_fence'),
                to: 'super_admin.farm_fence',
                icon: 'mdi-border-outside',
            },
        ]
        break
      case 'admin':
        items = [
          {
            title: this.lang.get('menu.dashboard'),
            to: 'admin.dashboard',
            icon: 'painel',
            user: 'admin',
          },
        ]
        break
      }
      return items
    },
  },
  mounted() {
    this.showUserMenu = false
  },
  methods: {
    url() {
      return location.pathname.substr(1)
    },
    goToPage(route, multiple = false) {
      this.isShowSubMenu = false
      if (!multiple) {
        this.subMenu = null
      }
      this.currentRoute = route
    },
    showSubMenu(menu) {
      this.isShowSubMenu = true
      this.subMenu = menu
      document.addEventListener('click', this.closeSubMenu)
    },
    closeSubMenu(event) {
      if (!$(event.target).hasClass('sidebar-main-menu') && !$(event.target).parents('.sidebar-main-menu').length && !$(event.target).hasClass('left-side-menu') && !$(event.target).parents('.left-side-menu').length) {
        this.isShowSubMenu = false
        document.removeEventListener('click', this.closeSubMenu)
      }
    },
    mobileShowSubMenu() {
      this.isShowSubMenu = !this.isShowSubMenu
      document.addEventListener('click', this.mobileCloseSubMenu)
    },
    mobileCloseSubMenu(event) {
      if (!$(event.target).hasClass('left-side-menu') && !$(event.target).parents('.left-side-menu').length
        && !$(event.target).hasClass('button-menu-mobile') && !$(event.target).parents('.button-menu-mobile').length) {
        this.isShowSubMenu = false
        document.removeEventListener('click', this.mobileCloseSubMenu)
      }
    },
    toggleFullScreen() {
      let el = document.body
      let isInFullScreen =
        (document.fullScreenElement && document.fullScreenElement !== null) ||
        document.mozFullScreen ||
        document.webkitIsFullScreen
      if (isInFullScreen) {
        this.cancelFullScreen()
      } else {
        this.requestFullScreen(el)
      }
      return false
    },
    cancelFullScreen() {
      let el = document.body
      var requestMethod =
        el.cancelFullScreen ||
        el.webkitCancelFullScreen ||
        el.mozCancelFullScreen ||
        el.exitFullscreen ||
        el.webkitExitFullscreen ||
        el.msExitFullScreen
      if (requestMethod) {
        requestMethod.call(el)
      } else if (typeof window.ActiveXObject !== 'undefined') {
        var wscript = new ActiveXObject('WScript.Shell')
        if (wscript !== null) {
          wscript.SendKeys('{F11}')
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen()
        } else if (document.exitFullScreen) {
          document.exitFullScreen()
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen()
        } else if (document.msExitFullScreen) {
          document.msExitFullScreen()
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen()
        }
      }
    },
    requestFullScreen(el) {
      var requestMethod =
        el.requestFullScreen ||
        el.webkitRequestFullScreen ||
        el.mozRequestFullScreen ||
        el.msRequestFullscreen
      if (requestMethod) {
        requestMethod.call(el)
      } else if (typeof window.ActiveXObject !== 'undefined') {
        var wscript = new ActiveXObject('WScript.Shell')
        if (wscript !== null) {
          wscript.SendKeys('{F11}')
        }
      }
      return false
    },
    logout() {
      let _this = this
      _this.showProfileMenu = false
      _this.pageLoading = true

      _this.$store
        .dispatch('auth/logout')
        .then(() => {
          _this.pageLoading = false
          _this.$router.push({ name: 'login' })
        })
        .catch(() => {})
    },
    openProfileMenu() {
      this.showProfileMenu = !this.showProfileMenu
      document.addEventListener('click', this.closeProfileMenu)
    },
    closeProfileMenu(event) {
      if (!$(event.target).hasClass('custom-dropdown-toggle') && !$(event.target).parents('.custom-dropdown-toggle').length) {
        this.showProfileMenu = false
        document.removeEventListener('click', this.closeProfileMenu)
      }
    },
    handleCloseDropdown() {
      this.showProfileMenu = false
    },
  },
}
</script>
<style lang="scss" scoped>
#sidebar-menu {
  display: flex;
  flex-direction: column;
  #menu-bar {
    flex-grow: 1;
  }
}
#menu-bar svg {
  margin: 0 auto;
}
.left-side-menu {
  .sidebar-content {
  }
  #menu-bar {
    padding: 0;
    li {
      text-align: center;
      svg {
        fill: #fff;
        height: 30px;
        width: auto;
      }
      p {
        font-size: 14px;
        color: #fff;
        margin-top: 15px;
        margin-bottom: 10px;
      }
      .router-link-active {
        svg {
          fill: #79e5ff;
        }
        p {
          color: #79e5ff;
        }
      }
    }
  }
}
.left-side-menu .sidebar-content {
  background: linear-gradient(8.95deg, #4baed8 1.3%, #2854e6 100.28%);
}

.left-side-menu .sidebar-content #sidebar-menu .metismenu .item p {
  line-height: 15px;
}
.left-side-menu .sidebar-content #sidebar-menu .metismenu .item.active {
  background-color: transparent;
}
.v-icon.v-icon {
  font-size: 32px;
}
.left-side-menu .sidebar-content #sidebar-menu .metismenu .item {
  // width: 150px;
}
.main-content.show-sidebar {
  // margin: 72px 0px 0px 380px;
}
.sidebar-main-menu {
  // left: 160px;
}
.sidebar-bottom {
  margin: 15px 8px;
  position: relative;
  span.detail {
    background-color: #558ae0;
    border-radius: 25px;
    display: block;
    padding: 9px;
    h5 {
      text-align: center;
      font-size: 16px;
      color: #fffbcc;
      margin-bottom: 0;
    }
    p {
      text-align: center;
      color: #fff;
      margin-top: 5px;
    }
  }
  span.icon {
    position: absolute;
    left: 34%;
    background: #fff;
    padding: 5px;
    border-radius: 50%;
    height: 26px;
    bottom: -11px;
  }
}
.menu-bar-badge {
  background-color: #ff5c75;
  display: inline-block;
  padding: 0.25em .4em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
  position: absolute;
  top: 0;
  left: auto;
  right: calc(50% - 25px);
}
</style>
