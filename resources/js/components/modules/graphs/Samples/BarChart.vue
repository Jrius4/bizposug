<template>
    <v-col cols="12" md="6">
        <v-card elevation="6">
            <v-card-text>
                <EchartMain
                    :options="getData"
                    :init-options="initOptions"
                    ref="bar"
                    theme="ovilia-green"
                    autoresize
                    @zr:click="handleZrClick"
                    @click="handleClick"
                />
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
import EchartMain from "../Echarts";

import qs from "qs";
import echarts from "echarts/lib/echarts";
import EChartsStat from "echarts-stat";
import "echarts-gl";
import "echarts/lib/chart/bar";
import "echarts/lib/chart/line";
import "echarts/lib/component/tooltip";
import "echarts/lib/component/legend";
import "echarts/lib/component/title";
import "echarts/lib/component/dataset";
import "zrender/lib/svg/svg";
import "echarts-liquidfill";
import "echarts/theme/dark";
import "echarts/dist/extension/dataTool";
import { toolbox as features } from "echarts/lib/langEN";

import theme from "../themes/theme.json";
EchartMain.registerTheme("ovilia-green", theme);
export default {
    name: "BarChart",
    components: { EchartMain },
    data: vm => {
        let data = [];
        const options = qs.parse(location.search, { ignoreQueryPrefix: true });
        return {
            gdp: [
                { country: "USA", value: 20.5 },
                { country: "China", value: 13.4 },
                { country: "Germany", value: 4.0 },
                { country: "Japan", value: 4.9 },
                { country: "France", value: 2.8 }
            ],
            data,
            options,
            initOptions: {
                renderer: options.renderer || "canvas"
            }
        };
    },
    computed: {
        getData() {
            let dataChart = {};
            var xAxisData = [];
            var yAxisData = [];
            var data = [];

            this.gdp.forEach(item => {
                xAxisData.push(item.country);
                data.push(item.value);
            });

            Object.assign(dataChart, {
                color: ["teal"],
                grid: {
                    left: "2%",
                    right: "3%",
                    bottom: "3%",
                    containLabel: true
                },
                title: {
                    text: "Countries Population in (Trillions)",
                    left: "right",
                    top: 20
                },
                legend: {
                    data: ["population"]
                },
                tooltip: {},
                xAxis: {
                    data: xAxisData,
                    splitLine: {
                        show: true
                    }
                },
                yAxis: [
                    {
                        type: "value"
                    }
                ],
                series: [
                    {
                        name: "population",
                        type: "bar",
                        data: data,
                        animationDelay: function(idx) {
                            return idx * 10;
                        }
                    }
                ],
                animationEasing: "elasticOut",
                animationDelayUpdate: function(idx) {
                    return idx * 5;
                }
            });
            return dataChart;
        }
    },
    methods: {
        toggleRenderer() {
            if (this.initOptions.renderer === "canvas") {
                this.initOptions.renderer = "svg";
            } else {
                this.initOptions.renderer = "canvas";
            }
        },

        randomize() {
            return [0, 0, 0].map(v => {
                return Math.round(450 + Math.random() * 650) / 10;
            });
        },
        toggleRenderer() {
            if (this.initOptions.renderer === "canvas") {
                this.initOptions.renderer = "svg";
            } else {
                this.initOptions.renderer = "canvas";
            }
        },
        handleClick() {
            console.log("click from echarts");
        },
        handleZrClick() {
            console.log("click from zrender");
        }
    },
    watch: {
        connected: {
            handler(value) {
                EchartMain[value ? "connect" : "disconnect"]("radiance");
            },
            immediate: true
        },
        "initOptions.renderer"(value) {
            this.options.renderer = value === "svg" ? value : undefined;
            let query = qs.stringify(this.options);
            query = query ? "?" + query : "";
            history.pushState(
                {},
                document.title,
                `${location.origin}${location.pathname}${query}${location.hash}`
            );
        }
    }
};
</script>

<style></style>
