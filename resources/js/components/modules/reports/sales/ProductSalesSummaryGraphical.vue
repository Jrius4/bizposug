<template>
    <v-app style="height: 200px; background-color: transparent">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <product-sales-bar-chart-groups/>
                    <sales-pie-chart/>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import ProductSalesBarChartGroups from "./ProductSalesBarChartGroups";
import SalesPieChart from "./SalesPieChart"
export default {
name:'ProductSalesSummaryGraphical',
components:{ProductSalesBarChartGroups,SalesPieChart},
}
</script>

<style lang="scss" scoped>

</style>
