<template>
  <div :class="{ 'has-logo': showLogo }">
    <logo v-if="showLogo" :collapse="isCollapse" />
    <el-scrollbar wrap-class="scrollbar-wrapper">
      <el-menu
        :default-active="activeMenu"
        :collapse="isCollapse"
        :background-color="variables.menuBg"
        :text-color="variables.menuText"
        :unique-opened="false"
        :active-text-color="variables.menuActiveText"
        :collapse-transition="false"
        mode="vertical"
      >
        <el-menu-item-group title="Report">
          <el-menu-item>
            <a
              v-loading="loadingInvestorReport"
              element-loading-text="Downloading..."
              element-loading-spinner="el-icon-loading"
              element-loading-background="rgba(48, 65, 86, 0.8)"
              :href="apiUrl + '/investor-report'"
              @click.prevent="downloadReport('investor')"
            >
              <span class="btn btn-primary">
                Investor Report Download
              </span>
            </a>
          </el-menu-item>
          <el-menu-item>
            <a
              v-loading="loadingDetailReport"
              element-loading-text="Downloading..."
              element-loading-spinner="el-icon-loading"
              element-loading-background="rgba(48, 65, 86, 0.8)"
              :href="apiUrl + '/detail-report'"
              @click.prevent="downloadReport('detail')"
            >
              <span class="btn btn-primary">
                Detail Report Download
              </span>
            </a>
          </el-menu-item>
        </el-menu-item-group>
        <el-menu-item-group title="Application">
          <sidebar-item
            v-for="route in routes.user"
            :key="route.path"
            :item="route"
            :base-path="route.path"
          />
        </el-menu-item-group>

        <el-menu-item-group title="Admin" v-if="routes.admin.length">
          <sidebar-item
            v-for="route in routes.admin"
            :key="route.path"
            :item="route"
            :base-path="route.path"
          />
        </el-menu-item-group>
      </el-menu>
    </el-scrollbar>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Logo from "./Logo";
import SidebarItem from "./SidebarItem";
import variables from "@/assets/styles/variables.scss";

export default {
  components: { SidebarItem, Logo },
  data() {
    return {
      apiUrl: process.env.apiEndpoint
    };
  },
  methods: {
    downloadReport(category) {
      this.$store.dispatch("user/downloadReport", category);
    }
  },
  computed: {
    ...mapGetters(["sidebar", "loadingInvestorReport", "loadingDetailReport"]),
    routes() {
      //return this.$router.options.routes
      return this.$store.state.user.routes;
    },
    activeMenu() {
      const route = this.$route;
      const { meta, path } = route;
      // if set path, the sidebar will highlight the path you set
      if (meta.activeMenu) {
        return meta.activeMenu;
      }
      return path;
    },
    showLogo() {
      return this.$store.state.settings.sidebarLogo;
    },
    variables() {
      return variables;
    },
    isCollapse() {
      return !this.sidebar.opened;
    }
  }
};
</script>
