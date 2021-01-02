<template>
    <v-row align="center" justify="center">
        <v-col cols="12" md="8">
            <table class="table table-sm">

                <tbody>
                    <tr>
                        <th colspan="2">{{size.name}}</th>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="2">Products</th>
                    </tr>
                    <tr v-for="(p,i) in size.products" :key="`${i}-prod`">
                        <td>
                            {{p.name}}
                        </td>
                        <td>{{p.size}}</td>
                    </tr>
                </tbody>
            </table>
        </v-col>
    </v-row>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
    export default {
        name:'Viewsize',
        computed:{
            ...mapState({
                openWindow: state => state.sizesModule.openWindow,
                size: state => state.sizesModule.size,
                action_done: state => state.sizesModule.action_done,
                message: state => state.sizesModule.message,
            })
        },
        methods:{
            ...mapMutations({
                GET_OPEN_WINDOW:"sizesModule/GET_OPEN_WINDOW",
                HANDLE_EDITOR_STATE:"sizesModule/HANDLE_EDITOR_STATE",
                GET_SELECTED_SIZE: "sizesModule/GET_SELECTED_SIZE",
            })
        },
        beforeDestroy(){
            this.GET_SELECTED_SIZE({ action_done: null });
        },
        destroyed(){
            this.GET_SELECTED_SIZE({ action_done: null });
        }

    }
</script>

<style lang="scss" scoped>

</style>
