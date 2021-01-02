<template>
    <v-app>
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <v-col cols="12" lg="12" md="12">
                        <base-v-component
                        :heading="`${selectedComponent} view`.toUpperCase()"
                        class="teal--text text--darken-3"
                        />
                        <base-material-card
                        icon="mdi-account-group-outline"
                        :title="`${selectedComponent} view`.toUpperCase()"
                        class="px-5 py-3 elevation-4"
                        color="teal darken-4"
                        titleColor="teal--text text--darken-2"
                        >
                        <v-container fluid>
                            <v-row align="baseline" justify="center">
                                <v-col cols="12" md="6">
                                <v-btn
                                    class="m-2"
                                    dark
                                    small
                                    color="grey darken-3"
                                    @click="selectedComponent = 'deposit form'"
                                    >Deposit Form</v-btn
                                >
                                <v-btn
                                class="m-2"
                                    dark
                                    small
                                    color="teal darken-3"
                                    @click="selectedComponent = 'withdraw form'"
                                    >Withdraw Form</v-btn
                                >
                                <v-btn
                                class="m-2"
                                    dark
                                    small
                                    color="green darken-3"
                                    @click="selectedComponent = 'Financial Table'"
                                    >Financial Table</v-btn
                                >
                                </v-col>
                            </v-row>
                            <v-row align="baseline" justify="center">
                                <v-col cols="12" md="12">
                                    <transition
                                        enter-active-class="animated fadeIn"
                                        leave-active-class="animated fadeOut"
                                        mode="out-in"
                                        key="hello"
                                    >
                                        <component v-bind:is="openComponent"></component>
                                    </transition>
                                </v-col>
                            </v-row>
                        </v-container>
                        </base-material-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import DepositForm from './forms/DepositForm';
import WithdrawForm from './forms/WithdrawForm';
import FinancialTable from "./table/FinancialTable";
    export default {
        name:'FinanceLinks',
        components: { DepositForm, WithdrawForm,FinancialTable },
        data: (vm) => {
            return {
            selectedComponent: "deposit form",
            };
        },
        computed: {
            openComponent() {
            switch (this.selectedComponent) {
                case "deposit form":
                    return DepositForm;
                case "withdraw form":
                    return WithdrawForm;
                case "Financial Table":
                    return FinancialTable;
                default:
                    DepositForm;
            }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>
