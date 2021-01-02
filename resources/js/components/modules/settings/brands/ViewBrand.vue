<template>
    <v-row align="center" justify="center">
        <v-col cols="12" md="8">
            <table class="table table-sm">

                <tbody>
                    <tr>
                        <th colspan="2">{{brand.name}}</th>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="2">Products</th>
                    </tr>
                    <tr v-for="(p,i) in brand.products" :key="`${i}-prod`">
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
        name:'ViewBrand',
        computed:{
            ...mapState({
                openWindow: state => state.brandsModule.openWindow,
                brand: state => state.brandsModule.brand,
                action_done: state => state.brandsModule.action_done,
                message: state => state.brandsModule.message,
            })
        },
        methods:{
            ...mapMutations({
                GET_OPEN_WINDOW:"brandsModule/GET_OPEN_WINDOW",
                HANDLE_EDITOR_STATE:"brandsModule/HANDLE_EDITOR_STATE",
                GET_SELECTED_BRAND: "brandsModule/GET_SELECTED_BRAND",
            })
        },
        beforeDestroy(){
            this.GET_SELECTED_BRAND({ action_done: null });
        },
        destroyed(){
            this.GET_SELECTED_BRAND({ action_done: null });
        }

    }
</script>

<style lang="scss" scoped>

</style>
