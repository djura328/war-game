<template>
  <v-app id="inspire">
    <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
            :width="400"
    >
      <v-list dense>

        <v-list-item-group>

          <v-row>
            <v-col cols="12">
                <span style="font-size: 24px">
                  Game list
                </span>
            </v-col>
            <v-col cols="8" class="pl-4">
              <v-text-field
                      label="Name of game"
                      v-model="name"
                      filled
                      hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-btn style="height: 100%;" @click="createGame">
                ADD NEW
              </v-btn>
            </v-col>
          </v-row>
          <v-divider
                  dark
                  class="my-4"
          />
          <template v-for="(game, index) in games">
            <v-list-item >
              <template >
                <v-list-item-action>
                  <v-checkbox
                          v-model="settings"
                          color="primary"
                          :value="game.id"

                  ></v-checkbox>
                </v-list-item-action>

                <v-list-item-content>
                  <v-list-item-title>{{game.name}}</v-list-item-title>
                </v-list-item-content>

                <v-list-item-action>
                  <v-btn text @click="$router.push({name: 'show', params: {id: game.id}})">
                    Edit
                  </v-btn>
                </v-list-item-action>
              </template>
            </v-list-item>

          </template>
        </v-list-item-group>


      </v-list>
      <v-btn block color="primary" @click="$router.push({name: 'war', params: {id: settings.join(''), games: selectedGame}})">Start</v-btn>
    </v-navigation-drawer>

    <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            color="blue darken-3"
            dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title
              style="width: 300px"
              class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down">War Game</span>
      </v-toolbar-title>
    </v-app-bar>
    <v-content>
      <router-view></router-view>
    </v-content>

    <v-snackbar
            v-model="snackbar"
            :timeout="6000"
    >
      Restart all game
      <v-btn
              color="pink"
              text
              @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>
  </v-app>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            source: String,
        },
        data: () => ({
            dialog: false,
            drawer: null,
            games: null,
            name: null,
            settings: [],
            snackbar: true
        }),
        created(){
            this.$router.push({name: 'home'})

            axios.get('http://localhost:8000/api/games/restart-all').then(res => {
                this.snackbar = true
            })

            axios.get('http://localhost:8000/api/games').then(res => {
                console.log(res.data)
                this.games = res.data
            })
        },
        methods:{
            createGame(){
                axios.post('http://localhost:8000/api/games', {name: this.name}).then(res => {
                    this.games.push(res.data)
                })
            }
        },
        computed:{
            selectedGame(){
                return this.games.filter(game => this.settings.includes(game.id))
            }
        }
    }
</script>