<template>
    <v-app style="height: 200px; background-color: transparent">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <bar-chart />
                    <line-chart />
                    <bar-chart-sorted />
                    <bar-chart-groups />
                    <line-chart-groups />
                    <prime-bar-chart-groups />
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import BarChart from "./BarChart";
import LineChart from "./LineChart";
import BarChartSorted from "./BarChartSorted";
import BarChartGroups from "./BarChartGroups";
import LineChartGroups from "./LineChartGroups";
import PrimeBarChartGroups from "./PrimeBarChartGroups";
export default {
    components: {
        BarChart,
        LineChart,
        BarChartSorted,
        BarChartGroups,
        LineChartGroups,
        PrimeBarChartGroups
    }
};
</script>

<style lang="scss" scoped></style>
