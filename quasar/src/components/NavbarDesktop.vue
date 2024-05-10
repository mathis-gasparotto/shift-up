<template>
  <q-drawer v-model="visible" show-if-above bordered>

    <q-tabs vertical class="nav-bar nav-bar--desktop q-pa-lg q-mx-auto bg-main" align="justify"
      indicator-color="transparent" active-color="primary">
      <q-item-label header class="h-15">
        <a href="/" @click.prevent="$router.push({ name: 'index' })">
          <q-img src="~assets/shift-up-logo.png" class="logo"></q-img>
        </a>
      </q-item-label>

      <q-list class=" h-70">
        <q-expansion-item v-for="(navItem_0, index_0) in navItems" :key="index_0" v-model="navItem_0.open"
          switch-toggle-side dense :to="navItem_0.route" :hide-expand-icon="!!navItem_0.route"
          expand-icon-class="expand-icon q-pr-xs">
          <template v-slot:header>
            <div class="row no-wrap items-center">
              <q-icon v-if="navItem_0.icon" size="20px" color="primary" :name="navItem_0.icon" class="q-mr-sm" />
              {{ navItem_0.title }}
            </div>
          </template>

          <q-expansion-item v-for="(navItem_1, index_1) in navItem_0.childs" :key="index_1" v-model="navItem_1.open"
            :header-inset-level="navItem_1.route ? 0 : 0.5" :content-inset-level="1" switch-toggle-side dense
            :to="navItem_1.route" :hide-expand-icon="!!navItem_1.route" expand-icon-class="expand-icon q-pr-xs">
            <template v-slot:header>
              <div class="row no-wrap items-center">
                <q-icon v-if="navItem_1.icon" size="20px" color="primary" :name="navItem_1.icon" class="q-mr-sm" />
                {{ navItem_1.title }}
              </div>
            </template>

            <q-expansion-item v-for="(navItem_2, index_2) in navItem_1.childs" :key="index_2" v-model="navItem_2.open"
              switch-toggle-side dense :header-inset-level="navItem_2.route ? 0 : 1" :content-inset-level="1.5"
              :to="navItem_2.route" :hide-expand-icon="!!navItem_2.route" expand-icon-class="expand-icon q-pr-xs">
              <template v-slot:header>
                <div class="row no-wrap items-center">
                  <q-icon v-if="navItem_2.icon" size="20px" color="primary" :name="navItem_2.icon" class="q-mr-sm" />
                  {{ navItem_2.title }}
                </div>
              </template>

              <q-expansion-item v-for="(navItem_3, index_3) in navItem_2.childs" :key="index_3" v-model="navItem_3.open"
                switch-toggle-side dense :header-inset-level="navItem_3.route ? 0 : 1.5" :content-inset-level="2"
                :to="navItem_3.route" :hide-expand-icon="!!navItem_3.route" expand-icon-class="expand-icon q-pr-xs">
                <template v-slot:header>
                  <div class="row no-wrap items-center">
                    <q-icon v-if="navItem_3.icon" size="20px" color="primary" :name="navItem_3.icon" class="q-mr-sm" />
                    {{ navItem_3.title }}
                  </div>
                </template>

                <q-expansion-item v-for="(navItem_4, index_4) in navItem_3.childs" :key="index_4"
                  v-model="navItem_4.open" switch-toggle-side dense :header-inset-level="navItem_4.route ? 0 : 2"
                  :content-inset-level="2.5" :to="navItem_4.route" :hide-expand-icon="!!navItem_4.route"
                  expand-icon-class="expand-icon q-pr-xs">
                  <template v-slot:header>
                    <div class="row no-wrap items-center">
                      <q-icon v-if="navItem_4.icon" size="20px" color="primary" :name="navItem_4.icon"
                        class="q-mr-sm" />
                      {{ navItem_4.title }}
                    </div>
                  </template>
                </q-expansion-item>

              </q-expansion-item>

            </q-expansion-item>

          </q-expansion-item>

        </q-expansion-item>
      </q-list>

      <div class="h-15">
        <SUbtn label="Upgrade Plan" rounded color="gradient" class="w-100 q-mb-md" />
        <q-btn label="Réduire" no-caps flat icon="sym_o_keyboard_tab_rtl" color="grey" class="w-100" align="left"
          rounded @click="() => {
    $emit('hideNav')
    toggleNav()
  }" />
      </div>
    </q-tabs>

  </q-drawer>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'NavbarDesktop',
  emits: ['hideNav'],
  components: {
    SUbtn
  },
  data() {
    return {
      visible: true,
      navItems: [
        {
          title: 'Teams',
          icon: 'sym_o_group',
          open: false,
          childs: [
            {
              title: 'Team 1',
              icon: 'sym_o_emoji_events',
              open: false,
              childs: [
                {
                  title: 'Project 1',
                  icon: 'sym_o_folder',
                  open: false,
                  route: { name: 'index' }
                }
              ]
            },
            {
              title: 'Team 2',
              icon: 'sym_o_palette',
              open: false,
              childs: [
                {
                  title: 'Project 2',
                  icon: 'sym_o_folder',
                  open: false,
                  route: { name: 'test' }
                }
              ]
            },
            {
              title: 'Team 3',
              icon: 'sym_o_bolt',
              open: false,
              childs: [
                {
                  title: 'Project 3',
                  icon: 'sym_o_folder',
                  open: false,
                  route: { name: 'index' }
                }
              ]
            }
          ]
        },
        {
          title: 'Shared with me',
          icon: 'sym_o_group',
          open: false,
          childs: [
            {
              title: 'School',
              open: false,
              icon: 'sym_o_book_5',
              childs: [
                {
                  title: 'Oui',
                  open: false,
                  route: { name: 'test' }
                }
              ]
            }
          ]
        }
      ]
    }
  },
  created() {
    this.opendCurrentRouteTabs()
  },
  methods: {
    opendCurrentRouteTabs() {
      this.navItems.forEach(navItem_0 => {
        if (navItem_0.childs) {
          navItem_0.childs.forEach(navItem_1 => {
            if (navItem_1.route && navItem_1.route.name === this.$route.name) {
              navItem_0.open = true
            } else if (navItem_1.childs) {
              navItem_1.childs.forEach(navItem_2 => {
                if (navItem_2.route && navItem_2.route.name === this.$route.name) {
                  navItem_0.open = true
                  navItem_1.open = true
                } else if (navItem_2.childs) {
                  navItem_2.childs.forEach(navItem_3 => {
                    if (navItem_3.route && navItem_3.route.name === this.$route.name) {
                      navItem_0.open = true
                      navItem_1.open = true
                      navItem_2.open = true
                    } else if (navItem_3.childs) {
                      navItem_3.childs.forEach(navItem_4 => {
                        if (navItem_4.route && navItem_4.route.name === this.$route.name) {
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
