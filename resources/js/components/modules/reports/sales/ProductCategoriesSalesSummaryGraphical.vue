<template>
    <v-app style="height: 200px; background-color: transparent">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">

                    <sales-categories-pie-chart/>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>

import SalesCategoriesPieChart from "./SalesCategoriesPieChart"
export default {
name:'ProductCategoriesSalesSummaryGraphical',
components:{SalesCategoriesPieChart},
}
</script>

<style lang="scss" scoped>

</style>
