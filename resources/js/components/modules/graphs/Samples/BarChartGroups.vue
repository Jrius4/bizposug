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
    name: "BarChartGroups",
    components: { EchartMain },
    data: vm => {
        let data = [];
        const options = qs.parse(location.search, { ignoreQueryPrefix: true });
        return {
            gdp: [
                { country: "USA", value: 20.5, continent: "America" },
                { country: "China", value: 13.4, continent: "Asia" },
                { country: "Germany", value: 4.0, continent: "Europe" },
                { country: "Japan", value: 4.9, continent: "Asia" },
                { country: "France", value: 2.8, continent: "Europe" },
                { country: "Uganda", value: 16.5, continent: "Africa" },
                { country: "Canada", value: 3.4, continent: "America" },
                { country: "Swedan", value: 8.3, continent: "Europe" },
                { country: "Sudan", value: 2.8, continent: "Africa" }
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
            var data1 = [];
            var data2 = [];
            var data3 = [];
            var data4 = [];
            const itemsGdp = this.gdp.sort((a, b) =>
                a.value > b.value ? 1 : -1
            );
            itemsGdp.forEach(item => {
                xAxisData.push(item.country);
                data1.push(
                    `${item.continent}`.toLowerCase() === "africa"
                        ? item.value
                        : null
                );
                data2.push(
                    `${item.continent}`.toLowerCase() === "asia"
                        ? item.value
                        : null
                );
                data3.push(
                    `${item.continent}`.toLowerCase() === "europe"
                        ? item.value
                        : null
                );
                data4.push(
                    `${item.continent}`.toLowerCase() === "america"
                        ? item.value
                        : null
                );
            });

            Object.assign(dataChart, {
                color: ["#0097A7", "#F4511E", "#FBC02D", "#424242"],
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
                    data: ["africa", "asia", "europe", "america"]
                },
                tooltip: {},
                xAxis: {
                    type: "category",
                    data: xAxisData
                },
                yAxis: [
                    {
                        type: "value"
                    }
                ],
                series: [
                    {
                        name: "africa",
                        type: "bar",
                        data: data1,
                        animationDelay: function(idx) {
                            return idx * 10 + 100;
                        }
                    },
                    {
                        name: "asia",
                        type: "bar",
                        data: data2,
                        animationDelay: function(idx) {
                            return idx * 10 + 100;
                        }
                    },
                    {
                        name: "europe",
                        type: "bar",
                        data: data3,
                        animationDelay: function(idx) {
                            return idx * 10 + 100;
                        }
                    },
                    {
                        name: "america",
                        type: "bar",
                        data: data4,
                        animationDelay: function(idx) {
                            return idx * 10 + 100;
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
