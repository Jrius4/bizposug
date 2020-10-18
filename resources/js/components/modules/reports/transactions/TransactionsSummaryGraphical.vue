<template>
    <v-app style="height: 200px; background-color: transparent">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <transactions-bar-chart-groups/>
                    <transactions-line-chart-groups/>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import TransactionsBarChartGroups from "./TransactionsBarChartGroups";
import TransactionsLineChartGroups from "./TransactionsLineChartGroups";
export default {
name:'TransactionsSummaryGraphical',
components:{TransactionsBarChartGroups,TransactionsLineChartGroups},
}
</script>

<style lang="scss" scoped>

</style>
