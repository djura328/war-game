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
                            color="error"
                            dark
                            flat
                    >
                        <v-toolbar-title>Game list</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text style="height: 400px;">
                        <v-row style="height: 100%;">
                            <v-col cols="6">
                                <v-row no-gutters style="height: 90%;">
                                    <v-col cols="12" >
                                        <v-list-item-group>
                                            <template v-for="game in games">
                                                <v-list-item :key="game.name">
                                                    <template v-slot:default="{ active, toggle }">
                                                        <v-list-item-content>
                                                            <v-list-item-title v-text="game.name"></v-list-item-title>
                                                        </v-list-item-content>

                                                        <v-list-item-action>
                                                            <v-list-item-action-text></v-list-item-action-text>
                                                            <v-icon color="yellow">
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
                                <v-btn color="error" class="mx-2" @click="startWar">Start War in {{$route.params.games.length}} Games</v-btn>
                                <v-btn color="success" class="mx-2" @click="oneAttack">One Attack in {{$route.params.games.length}} Games</v-btn>
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
            Restart selected games
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
                games: null,
                loader: false,
                logs: [],
                snackbar: false

            }
        },
        created(){
            this.getGame()
        },
        methods:{
            getGame(){
               this.games = this.$route.params.games
            },
            startWar(){
                this.logs = []
                let ids = this.$route.params.games.map(game => game.id)
                this.loader = true
                axios.post(`http://localhost:8000/api/games/attack`,
                    {
                        games: ids
                    }
                ).then(res => {
                    if(this.logs.length) this.logs = [...res.data, ...this.logs]
                    else this.logs = res.data
                    this.loader = false
                })
            },
            oneAttack(){
                let ids = this.$route.params.games.map(game => game.id)
                this.loader = true
                axios.post(`http://localhost:8000/api/games/step`,
                    {
                        games: ids
                    }
                ).then(res => {
                    if(this.logs.length) this.logs = [...this.logs, ...res.data]
                    else this.logs = res.data
                    this.loader = false
                })
            },
            restart(){
                let ids = this.$route.params.games.map(game => game.id)
                axios.post(`http://localhost:8000/api/games/restart`,
                    {
                        games: ids
                    }
                ).then(res => {
                    this.snackbar = true
                })
            }
        },
        watch:{
            $route(){
                this.getGame()
            }
        }
    }

</script>