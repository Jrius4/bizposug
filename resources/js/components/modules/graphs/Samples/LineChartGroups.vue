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
    name: "LineChartGroups",
    components: { EchartMain },
    data: vm => {
        let data = [];
        const options = qs.parse(location.search, { ignoreQueryPrefix: true });
        return {
            gdp: [
                { group: 'amy', amount: 90000, year: 2014 },
                { group: 'police', amount: 150000, year: 2016 },
                { group: 'amy', amount: 68000, year: 2022 },
                { group: 'amy', amount: 3000, year: 2020 },
                { group: 'police', amount: 145000, year: 2022 }
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
                a.year > b.year ? 1 : -1
            );
            itemsGdp.forEach(item => {
                if(!xAxisData.includes(item.year)){
                    xAxisData.push(item.year);
                }

                if(item.group === 'amy'){
                    data1.push(item.amount);
                    data2.push(null);
                }
                else if(item.group === 'police'){
                    data2.push(item.amount);
                    data1.push(null);
                }

            });
            var posList = [
                "left",
                "right",
                "top",
                "bottom",
                "inside",
                "insideTop",
                "insideLeft",
                "insideRight",
                "insideBottom",
                "insideTopLeft",
                "insideTopRight",
                "insideBottomLeft",
                "insideBottomRight"
            ];

            app.configParameters = {
                rotate: {
                    min: -90,
                    max: 90
                },
                align: {
                    options: {
                        left: "left",
                        center: "center",
                        right: "right"
                    }
                },
                verticalAlign: {
                    options: {
                        top: "top",
                        middle: "middle",
                        bottom: "bottom"
                    }
                },
                position: {
                    options: echarts.util.reduce(
                        posList,
                        function(map, pos) {
                            map[pos] = pos;
                            return map;
                        },
                        {}
                    )
                },
                distance: {
                    min: 0,
                    max: 100
                }
            };

            app.config = {
                rotate: 90,
                align: "left",
                verticalAlign: "middle",
                position: "insideBottom",
                distance: 15,
                onChange: function() {
                    var labelOption = {
                        normal: {
                            rotate: app.config.rotate,
                            align: app.config.align,
                            verticalAlign: app.config.verticalAlign,
                            position: app.config.position,
                            distance: app.config.distance
                        }
                    };
                    myChart.setOption({
                        series: [
                            {
                                label: labelOption
                            },
                            {
                                label: labelOption
                            },
                            {
                                label: labelOption
                            },
                            {
                                label: labelOption
                            }
                        ]
                    });
                }
            };

            var labelOption = {
                show: true,
                position: app.config.position,
                distance: app.config.distance,
                align: app.config.align,
                verticalAlign: app.config.verticalAlign,
                rotate: app.config.rotate,
                formatter: "{c}  {name|{a}}",
                fontSize: 16,
                rich: {
                    name: {
                        textBorderColor: "#fff"
                    }
                }
            };

            Object.assign(dataChart, {
                color: ["#0097A7", "#F4511E", "#FBC02D", "#424242"],
                grid: {
                    left: "2%",
                    right: "3%",
                    bottom: "3%",
                    containLabel: true
                },
                tooltip: {
                    trigger: "axis",
                    axisPointer: {
                        type: "shadow"
                    }
                },
                title: {
                    text: "Transaction Types (UGX)",
                    left: "right",
                    top: 20
                },
                legend: {
                    data: ["amy", "police"]
                },
                toolbox: {
                    show: true,
                    orient: "vertical",
                    left: "right",
                    top: "center",
                    feature: {
                        mark: { show: true },
                        dataView: {
                            show: true,
                            readOnly: true,
                            lang: ["table view", "turn off", "refresh"],
                            title: "Data View"
                        },
                        magicType: {
                            show: true,
                            title: {
                                stack: "stack",
                                tiled: "tiled",
                                bar: "bar",
                                line: "line"
                            },
                            type: ["line", "bar", "stack", "tiled"]
                        },
                        restore: {
                            show: true,
                            title: "restore"
                        },
                        saveAsImage: {
                            show: true,
                            title: "save image",
                            pixelRatio: 2
                        }
                    }
                },
                xAxis: {
                    type: "category",
                    axisTick: { show: false },
                    data: xAxisData
                },
                yAxis: [
                    {
                        type: "value"
                    }
                ],
                series: [
                    {
                        name: "amy",
                        type: "bar",
                        barGap: 0,
                        label: labelOption,
                        data: data1
                    },
                    {
                        name: "police",
                        type: "bar",
                        label: labelOption,
                        data: data2
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
