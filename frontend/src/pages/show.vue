<template>
    <v-container
            fluid
            fill-height
    >
        <v-layout
                align-center
                justify-center
        >
            <v-flex
                    xs12
                    sm8
                    md8
            >
                <v-card class="elevation-12">
                    <v-toolbar
                            color="primary"
                            dark
                            flat
                    >
                        <v-toolbar-title>Armies list</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-row no-gutters>
                            <v-col cols="6">
                                <v-text-field
                                        label="Name of army"
                                        filled
                                        hide-details
                                        v-model="name"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select
                                        :items="itemsSrategy"
                                        filled
                                        label="Strategy"
                                        v-model="strategy"
                                        hide-details
                                        item-text="name"
                                        item-value="id"
                                        return-object
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                        icon
                                        large
                                        target="_blank"
                                        v-on="on"
                                        @click="addArmy"
                                >
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </template>
                            <span>Add army</span>
                        </v-tooltip>
                    </v-toolbar>
                    <v-card-text style="height: 400px;">
                        <v-row style="height: 100%;">
                            <v-col cols="6">
                                <v-row no-gutters style="height: 90%;">
                                    <v-col cols="12" >
                                        <v-list-item-group>
                                            <template v-for="army in armies">
                                                <v-list-item :key="army.name">
                                                    <template v-slot:default="{ active, toggle }">
                                                        <v-list-item-content>
                                                            <v-list-item-title v-text="army.name"></v-list-item-title>
                                                            <v-list-item-subtitle v-if="army.status === 0">
                                                                <span style="color:red">Defeated</span>
                                                            </v-list-item-subtitle>
                                                        </v-list-item-content>

                                                        <v-list-item-action>
                                                            <v-list-item-action-text></v-list-item-action-text>
                                                            <v-icon :color="army.status === 0 ? 'red' : 'yellow'">
                                                                mdi-star
                                                            </v-icon>
                                                        </v-list-item-action>
                                                    </template>
                                                </v-list-item>
                                            </template>
                                        </v-list-item-group>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="6" style="overflow-y: scroll; height: 100%;">
                                <v-row justify="center" align="center" class="fill-height" v-if="loader">
                                    <v-progress-circular
                                            :size="100"
                                            :width="4"
                                            color="purple"
                                            indeterminate
                                    ></v-progress-circular>
                                </v-row>
                                <template v-else v-for="log in logs">
                                    <span v-html="log.log"></span>
                                    <span v-if="Array.isArray(log.winner)">
                                        <br><span style="background-color: red; color: white; padding: 4px 8px">WIINER IS {{log.winner[0].name}}</span><br>
                                    </span>
                                </template>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-col cols="12" class="py-2">
                            <v-row no-gutters justify="center">
                                <v-btn color="error" class="mx-2" @click="startWar" :disabled="loader">Start War</v-btn>
                                <v-btn color="success" class="mx-2" @click="oneAttack" :disabled="loader">One Attack</v-btn>
                                <v-btn color="warning" class="mx-2" @click="restart" :disabled="loader">Restart</v-btn>
                            </v-row>
                        </v-col>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>

        <v-snackbar
                v-model="snackbar"
                :timeout="6000"
        >
            Restart game
            <v-btn
                    color="pink"
                    text
                    @click="snackbar = false"
            >
                Close
            </v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import axios from 'axios'

    export default {
        data(){
            return{
                itemsSrategy: [
                    {id: 1, name: 'random'},
                    {id: 2, name: 'stronger'},
                    {id: 3, name: 'weaker'},
                ],
                strategy: null,
                name: null,
                armies: null,
                loader: false,
                logs: [],
                snackbar: false
            }
        },
        created(){
            this.getArmy()
        },
        methods:{
            getArmy(){
              axios.get(`http://localhost:8000/api/army/${this.$route.params.id}`).then(res => {
                  this.armies = res.data
              })
            },
            addArmy(){
              axios.post(`http://localhost:8000/api/army`,
                  {
                      name: this.name,
                      strategy: this.strategy.id,
                      game_id: this.$route.params.id
                  }
              ).then(res => {
                  this.name = null
                  this.strategy = null
                  this.armies.push(res.data)
              })
            },
            startWar(){
                this.logs = []
                this.loader = true
                axios.post(`http://localhost:8000/api/games/attack`,
                    {
                        games: [this.$route.params.id]
                    }
                ).then(res => {
                    if(this.logs.length) this.logs = [...res.data, ...this.logs]
                    else this.logs = res.data
                    this.loader = false
                    this.getArmy()
                })
            },
            oneAttack(){
                this.loader = true
                axios.post(`http://localhost:8000/api/games/step`,
                    {
                        games: [this.$route.params.id]
                    }
                ).then(res => {
                    if(this.logs.length) this.logs = [...this.logs, ...res.data]
                    else this.logs = res.data
                    this.loader = false
                    this.getArmy()
                })
            },
            restart(){
                axios.post(`http://localhost:8000/api/games/restart`,
                    {
                        games: [this.$route.params.id]
                    }
                ).then(res => {
                    this.snackbar = true
                })
            }
        },
        watch:{
            $route(){
                this.getArmy()
            }
        }
    }

</script>