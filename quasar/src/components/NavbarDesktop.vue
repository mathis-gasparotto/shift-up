<template>
  <q-drawer
    v-model="visible"
    show-if-above
    bordered
  >
    <q-tabs
      vertical
      class="nav-bar nav-bar--desktop q-pa-lg q-mx-auto bg-nav"
      align="justify"
      indicator-color="transparent"
      active-color="primary"
    >
      <q-item-label
        header
        class="h-15"
      >
        <a
          href="/"
          @click.prevent="$router.push({ name: 'home' })"
        >
          <q-img
            src="~assets/shift-up-logo.png"
            class="logo"
          ></q-img>
        </a>
      </q-item-label>

      <q-list class="h-70">
        <q-skeleton v-if="navLoading" />
        <template v-else>
          <q-expansion-item
            v-for="(navItem_0, index_0) in navItems"
            :key="index_0"
            v-model="navItem_0.open"
            dense
            :to="navItem_0.route"
            :hide-expand-icon="!!navItem_0.route"
            expand-icon-class="expand-icon q-pr-xs"
          >
            <template v-slot:header>
              <div class="row no-wrap items-center">
                <q-icon
                  v-if="navItem_0.icon"
                  size="20px"
                  color="primary"
                  :name="navItem_0.icon"
                  class="q-mr-sm"
                />
                {{ navItem_0.name }}
              </div>
            </template>

            <q-expansion-item
              v-for="(navItem_1, index_1) in navItem_0.childs"
              :key="index_1"
              v-model="navItem_1.open"
              :header-inset-level="0.5"
              dense
              :to="navItem_1.route"
              :hide-expand-icon="!!navItem_1.route"
              expand-icon-class="expand-icon q-pr-xs"
            >
              <template v-slot:header>
                <div class="row no-wrap items-center">
                  <q-icon
                    v-if="navItem_1.icon"
                    size="20px"
                    color="primary"
                    :name="navItem_1.icon"
                    class="q-mr-sm"
                  />
                  {{ navItem_1.name }}
                </div>
              </template>

              <q-expansion-item
                v-for="(navItem_2, index_2) in navItem_1.childs"
                :key="index_2"
                v-model="navItem_2.open"
                dense
                :header-inset-level="1"
                :to="navItem_2.route"
                :hide-expand-icon="!!navItem_2.route"
                expand-icon-class="expand-icon q-pr-xs"
              >
                <template v-slot:header>
                  <div class="row no-wrap items-center">
                    <q-icon
                      v-if="navItem_2.icon"
                      size="20px"
                      color="primary"
                      :name="navItem_2.icon"
                      class="q-mr-sm"
                    />
                    {{ navItem_2.name }}
                  </div>
                </template>

                <q-expansion-item
                  v-for="(navItem_3, index_3) in navItem_2.childs"
                  :key="index_3"
                  v-model="navItem_3.open"
                  dense
                  :header-inset-level="1.5"
                  :to="navItem_3.route"
                  :hide-expand-icon="!!navItem_3.route"
                  expand-icon-class="expand-icon q-pr-xs"
                >
                  <template v-slot:header>
                    <div class="row no-wrap items-center">
                      <q-icon
                        v-if="navItem_3.icon"
                        size="20px"
                        color="primary"
                        :name="navItem_3.icon"
                        class="q-mr-sm"
                      />
                      {{ navItem_3.name }}
                    </div>
                  </template>

                  <q-expansion-item
                    v-for="(navItem_4, index_4) in navItem_3.childs"
                    :key="index_4"
                    v-model="navItem_4.open"
                    dense
                    :header-inset-level="2"
                    :to="navItem_4.route"
                    :hide-expand-icon="!!navItem_4.route"
                    expand-icon-class="expand-icon q-pr-xs"
                  >
                    <template v-slot:header>
                      <div class="row no-wrap items-center">
                        <q-icon
                          v-if="navItem_4.icon"
                          size="20px"
                          color="primary"
                          :name="navItem_4.icon"
                          class="q-mr-sm"
                        />
                        {{ navItem_4.name }}
                      </div>
                    </template>
                  </q-expansion-item>
                </q-expansion-item>
              </q-expansion-item>
            </q-expansion-item>
          </q-expansion-item>
        </template>
      </q-list>

      <div class="h-15">
        <SUbtn
          :label="$t('nav.changePlan')"
          rounded
          color="gradient"
          class="w-100 q-mb-md"
        />
        <q-btn
          :label="$t('nav.minimize')"
          no-caps
          flat
          icon="sym_o_keyboard_tab_rtl"
          color="grey"
          class="w-100"
          align="left"
          rounded
          @click="toggleNav()"
        />
      </div>
    </q-tabs>
  </q-drawer>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import { displayError } from 'src/helpers/translatting'

export default {
  name: 'NavbarDesktop',
  emits: ['hideNav'],
  components: {
    SUbtn
  },
  data() {
    return {
      visible: true,
      navLoading: true,
      navItems: [
        // {
        //   name: 'Teams',
        //   icon: 'sym_o_group',
        //   open: false,
        //   childs: [
        //     {
        //       name: 'Team 1',
        //       icon: 'sym_o_emoji_events',
        //       open: false,
        //       childs: [
        //         {
        //           name: 'Project 1',
        //           icon: 'sym_o_folder',
        //           open: false,
        //           route: { name: 'home' }
        //         }
        //       ]
        //     },
        //     {
        //       name: 'Team 2',
        //       icon: 'sym_o_palette',
        //       open: false,
        //       childs: [
        //         {
        //           name: 'Project 2',
        //           icon: 'sym_o_folder',
        //           open: false,
        //           route: { name: 'test' }
        //         }
        //       ]
        //     },
        //     {
        //       name: 'Team 3',
        //       icon: 'sym_o_bolt',
        //       open: false,
        //       childs: [
        //         {
        //           name: 'Project 3',
        //           icon: 'sym_o_folder',
        //           open: false,
        //           route: { name: 'home' }
        //         }
        //       ]
        //     }
        //   ]
        // },
        // {
        //   name: 'Shared with me',
        //   icon: 'sym_o_group',
        //   open: false,
        //   childs: [
        //     {
        //       name: 'School',
        //       open: false,
        //       icon: 'sym_o_book_5',
        //       childs: [
        //         {
        //           name: 'Oui',
        //           open: false,
        //           route: { name: 'test' }
        //         }
        //       ]
        //     }
        //   ]
        // }
      ]
    }
  },
  created() {
    this.reloadData()
  },
  methods: {
    reloadData() {
      this.navLoading = true
      this.$resources.teams
        .list()
        .then((res) => {
          this.formatTeamsForNav(res.data)
          this.opendCurrentRouteTabs()
        })
        .catch((err) => {
          displayError(err)
        })
        .finally(() => {
          this.navLoading = false
        })
    },
    formatTeamsForNav(teamList) {
      this.navItems = []
      teamList.forEach((team) => {
        this.navItems.push({
          id: team.id,
          name: team.name,
          icon: 'sym_o_group',
          open: false,
          childs: team.projects.map((project) => {
            return {
              name: project.name,
              icon: 'sym_o_folder',
              open: false,
              route: { name: 'project', params: { projectId: project.id, teamId: team.id } }
            }
          })
        })
      })
      this.navItems[0].open = true
    },
    opendCurrentRouteTabs() {
      this.navItems.forEach((navItem_0) => {
        if (navItem_0.childs) {
          navItem_0.childs.forEach((navItem_1) => {
            if (navItem_1.route && navItem_1.route.path === this.$route.path) {
              navItem_0.open = true
            } else if (navItem_1.childs) {
              navItem_1.childs.forEach((navItem_2) => {
                if (navItem_2.route && navItem_2.route.path === this.$route.path) {
                  navItem_0.open = true
                  navItem_1.open = true
                } else if (navItem_2.childs) {
                  navItem_2.childs.forEach((navItem_3) => {
                    if (navItem_3.route && navItem_3.route.path === this.$route.path) {
                      navItem_0.open = true
                      navItem_1.open = true
                      navItem_2.open = true
                    } else if (navItem_3.childs) {
                      navItem_3.childs.forEach((navItem_4) => {
                        if (navItem_4.route && navItem_4.route.path === this.$route.path) {
                          navItem_0.open = true
                          navItem_1.open = true
                          navItem_2.open = true
                          navItem_3.open = true
                        }
                      })
                    }
                  })
                }
              })
            }
          })
        }
      })
      if (this.$route.name === 'team') {
        const currentTeam = this.navItems.find((t) => t.id === this.$route.params.teamId)
        if (currentTeam) currentTeam.open = true
      }
    },
    toggleNav() {
      this.visible = !this.visible
    },
    isCurrentRoute(route) {
      return route.name === this.$route.name
    }
  }
}
</script>

<style lang="scss" scoped>
.logo {
  width: 128px;
}
</style>

<style lang="scss">
.expand-icon {
  min-width: fit-content;
}
</style>
