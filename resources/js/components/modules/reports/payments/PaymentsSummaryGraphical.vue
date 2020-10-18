<template>
    <v-app style="height: 200px; background-color: transparent">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <payment-bar-chart-groups/>
                    <payment-line-chart-groups/>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import PaymentBarChartGroups from "./PaymentBarChartGroups";
import PaymentLineChartGroups from "./PaymentLineChartGroups";
export default {
name:'PaymentsSummaryGraphical',
components:{PaymentBarChartGroups,PaymentLineChartGroups},
}
</script>

<style lang="scss" scoped>

</style>
