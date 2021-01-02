<template>
    <v-row align="center" justify="center">
        <v-col cols="12" md="8">
            <table class="table table-sm">

                <tbody>
                    <tr>
                        <th colspan="2">{{group.name}}</th>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="2">Products</th>
                    </tr>
                    <tr v-for="(p,i) in group.products" :key="`${i}-prod`">
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
        name:'ViewGroup',
        computed:{
            ...mapState({
                openWindow: state => state.groupsModule.openWindow,
                group: state => state.groupsModule.group,
                action_done: state => state.groupsModule.action_done,
                message: state => state.groupsModule.message,
            })
        },
        methods:{
            ...mapMutations({
                GET_OPEN_WINDOW:"groupsModule/GET_OPEN_WINDOW",
                HANDLE_EDITOR_STATE:"groupsModule/HANDLE_EDITOR_STATE",
                GET_SELECTED_GROUP: "groupsModule/GET_SELECTED_GROUP",
            })
        },
        beforeDestroy(){
            this.GET_SELECTED_GROUP({ action_done: null });
        },
        destroyed(){
            this.GET_SELECTED_GROUP({ action_done: null });
        }

    }
</script>

<style lang="scss" scoped>

</style>
