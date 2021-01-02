<template>
    <v-row align="center" justify="center">
        <v-col cols="12" md="8">
            <table class="table table-sm">

                <tbody>
                    <tr>
                        <th colspan="4">{{customer.name}}</th>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="4">Transactions</th>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <th>Products

                            <tr>
                                <td>Name</td>
                                <td>Qty</td>
                                <td>Rate</td>
                                <td>Amount</td>
                            </tr>
                                </th>
                                <th>Discount</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                    <tr v-for="(p,i) in customer.transactions" :key="`${i}-prod`">
                        <td>
                            {{p.type_of_transaction}}
                        </td>
                        <td>
                            <table class="table table-sm">
                                <tbody>

                                    <tr v-for="(pt ,t) in p.products" :key="`${t}-pt`">
                                        <td>{{pt.name}}</td>
                                        <td>{{pt.qty}}</td>
                                        <td>{{p.type_of_transaction === 'Retail Sale'?outPt(pt.retailsale_price):outPt(pt.wholesale_price)}}</td>
                                        <td>{{p.type_of_transaction === 'Retail Sale'?outPt(pt.retailsale_price * pt.qty):outPt(pt.wholesale_price * pt.qty)}}</td>
                                    </tr>


                                </tbody>
                            </table>
                        </td>
                         <td>{{p.discount | currency}}</td>
                         <td>{{p.total | currency}}</td>
                        <td>{{p.created_at}}</td>
                    </tr>
                </tbody>

            </table>
        </v-col>
    </v-row>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
    export default {
        name:'ViewCustomer',
        computed:{
            ...mapState({
                openWindow: state => state.customersModule.openWindow,
                customer: state => state.customersModule.customer,
                action_done: state => state.customersModule.action_done,
                message: state => state.customersModule.message,
            })
        },
        methods:{
            ...mapMutations({
                GET_OPEN_WINDOW:"customersModule/GET_OPEN_WINDOW",
                HANDLE_EDITOR_STATE:"customersModule/HANDLE_EDITOR_STATE",
                GET_SELECTED_CUSTOMER: "customersModule/GET_SELECTED_CUSTOMER",
            }),
            outPt(value){
                return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX",
            }).format(value);
            }
        },
        beforeDestroy(){
            this.GET_SELECTED_CUSTOMER({ action_done: null });
            this.HANDLE_EDITOR_STATE({action_done:'clear'});
        },
        destroyed(){
            this.GET_SELECTED_CUSTOMER({ action_done: null });
            this.HANDLE_EDITOR_STATE({action_done:'clear'});
        },
        filters: {
            currency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX",
            }).format(value);
            },
        },

    }
</script>

<style lang="scss" scoped>

</style>
