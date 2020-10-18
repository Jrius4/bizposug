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
            <v-card-actions>
                <v-row align="baseline" justify="center">
                    <v-col cols="12" md="4">
                        <v-menu bottom right>
                            <template v-slot:activator="{ on }">
                                <v-btn outlined v-on="on" small>
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>mdi-menu-down</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="typeSelected('daily')">
                                    <v-list-item-title>Daily</v-list-item-title>
                                </v-list-item>
                                <v-list-item
                                    @click="typeSelected('daily_by_group')"
                                >
                                    <v-list-item-title
                                        >Daily By Groups</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="typeSelected('weekly')">
                                    <v-list-item-title
                                        >Weekly</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item
                                    @click="typeSelected('weekly_by_group')"
                                >
                                    <v-list-item-title
                                        >Weekly By Groups</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="typeSelected('monthly')">
                                    <v-list-item-title
                                        >Monthly</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item
                                    @click="typeSelected('monthly_by_group')"
                                >
                                    <v-list-item-title
                                        >Monthly By Groups</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="typeSelected('yearly')">
                                    <v-list-item-title
                                        >Yearly</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item
                                    @click="typeSelected('yearly_by_group')"
                                >
                                    <v-list-item-title
                                        >Yearly By Groups</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="typeSelected('interval')">
                                    <v-list-item-title
                                        >By Interval</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item
                                    @click="typeSelected('interval_by_group')"
                                >
                                    <v-list-item-title
                                        >By Interval Groups</v-list-item-title
                                    >
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-col>
                </v-row>
            </v-card-actions>
            <v-card-text>
                <template>
                    <v-row class="mt-2" align="center" justify="center">
                        <span class="grey--text">Items per page</span>
                        <v-menu offset-y>
                            <template
                                v-slot:activator="{
                                    on,
                                    attrs
                                }"
                            >
                                <v-btn
                                    dark
                                    text
                                    color="primary"
                                    class="ml-2"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    {{ itemsPerPage }}
                                    <v-icon>mdi-chevron-down</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item
                                    v-for="(number, index) in itemsPerPageArray"
                                    :key="index"
                                    @click="updateItemsPerPage(number)"
                                >
                                    <v-list-item-title>
                                        {{ number.name }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <v-spacer></v-spacer>

                        <span class="mr-4 grey--text">
                            Page {{ page }} of
                            {{ numberOfPages }}
                        </span>
                        <v-btn
                            small
                            fab
                            dark
                            color="teal darken-3"
                            class="mr-1"
                            @click="formerPage"
                        >
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                        <v-btn
                            small
                            fab
                            dark
                            color="teal darken-3"
                            class="ml-1"
                            @click="nextPage"
                        >
                            <v-icon>mdi-chevron-right</v-icon>
                        </v-btn>
                    </v-row>
                </template>
            </v-card-text>
        </v-card>
        <template>
            <v-row justify="center">
                <v-dialog v-model="inputStartEnd" persistent max-width="420">
                    <v-card>
                        <v-form ref="formReport">
                            <v-card elevation="3">
                                <v-card-text>
                                    <h4 class="text-center teal--text">
                                        Report Input
                                    </h4>
                                </v-card-text>
                                <v-form>
                                    <v-card-text>
                                        <div class="row form-group">
                                            <label class="col-12"
                                                >Date Range</label
                                            >
                                            <div class="col-6">
                                                <template>
                                                    <v-menu
                                                        ref="menu1"
                                                        v-model="menu1"
                                                        :close-on-content-click="
                                                            false
                                                        "
                                                        transition="scale-transition"
                                                        offset-y
                                                        max-width="290px"
                                                        min-width="290px"
                                                    >
                                                        <template
                                                            v-slot:activator="{
                                                                on,
                                                                attrs
                                                            }"
                                                        >
                                                            <v-text-field
                                                                v-model="start"
                                                                label="Start Date"
                                                                hint="YYYY/MM/DD format"
                                                                persistent-hint
                                                                readonly
                                                                prepend-icon="mdi-calendar-text"
                                                                v-bind="attrs"
                                                                @blur="
                                                                    start = parseDate(
                                                                        start
                                                                    )
                                                                "
                                                                v-on="on"
                                                            ></v-text-field>
                                                        </template>
                                                        <v-date-picker
                                                            v-model="start"
                                                            no-title
                                                            @input="
                                                                menu1 = false
                                                            "
                                                        ></v-date-picker>
                                                    </v-menu>
                                                </template>
                                            </div>
                                            <div class="col-6">
                                                <template>
                                                    <v-menu
                                                        ref="menu1"
                                                        v-model="menu2"
                                                        :close-on-content-click="
                                                            false
                                                        "
                                                        transition="scale-transition"
                                                        offset-y
                                                        max-width="290px"
                                                        min-width="290px"
                                                    >
                                                        <template
                                                            v-slot:activator="{
                                                                on,
                                                                attrs
                                                            }"
                                                        >
                                                            <v-text-field
                                                                v-model="end"
                                                                label="End Date"
                                                                hint="YYYY/MM/DD format"
                                                                persistent-hint
                                                                readonly
                                                                prepend-icon="mdi-calendar-text"
                                                                v-bind="attrs"
                                                                @blur="
                                                                    end = parseDate(
                                                                        end
                                                                    )
                                                                "
                                                                v-on="on"
                                                            ></v-text-field>
                                                        </template>
                                                        <v-date-picker
                                                            v-model="end"
                                                            no-title
                                                            @input="
                                                                menu2 = false
                                                            "
                                                        ></v-date-picker>
                                                    </v-menu>
                                                </template>
                                            </div>
                                        </div>
                                        <v-select
                                            label="Transaction Type"
                                            :items="['One', 'Two']"
                                            :menu-props="{
                                                bottom: true,
                                                offsetY: true
                                            }"
                                        ></v-select>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            color="red darken-3"
                                            text
                                            @click="inputStartEnd = false"
                                            ><v-icon>mdi-close-circle</v-icon>
                                            cancel</v-btn
                                        >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="green darken-2"
                                            @click="inputStartEnd = false"
                                            text
                                        >
                                            <v-icon>mdi-chart-multiple</v-icon>
                                            Report</v-btn
                                        >
                                    </v-card-actions>
                                </v-form>
                            </v-card>
                        </v-form>
                    </v-card>
                </v-dialog>
            </v-row>
        </template>
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
    name: "PrimeBarChartGroups",
    components: { EchartMain },
    data: vm => {
        let data = [];
        const options = qs.parse(location.search, { ignoreQueryPrefix: true });
        return {
            type: "yearly",
            typeToLabel: {
                daily: "Daily",
                daily_by_group: "Daily By Group",
                weekly: "Weekly",
                weekly_by_group: "Weekly By Group",
                monthly: "Monthly",
                monthly_by_group: "Monthly By Group",
                yearly: "Yearly",
                yearly_by_group: "Yearly By Group",
                day: "Day",
                interval: "Interval",
                interval_by_group: "By Interval Group"
            },
            gdp: [
                { whole: 65000, retail: 90000, year: 2014 },
                { whole: 400, retail: 150000, year: 2016 },
                { whole: 90000, retail: 68000, year: 2018 },
                { whole: 868000, retail: 3000, year: 2020 },
                { whole: 85900, retail: 145000, year: 2022 }
            ],
            data,
            options,
            initOptions: {
                renderer: options.renderer || "canvas"
            },
            seconds: -1,
            inputStartEnd: false,
            startDate: null,
            endDate: null,
            loadingData: false,
            amountArray: [],
            menu1: false,
            start: new Date().toISOString().substr(0, 10),
            dateFormatted1: vm.formatDate(
                new Date().toISOString().substr(0, 10)
            ),
            menu2: false,
            end: new Date().toISOString().substr(0, 10),
            dateFormatted2: vm.formatDate(
                new Date().toISOString().substr(0, 10)
            ),
            itemsPerPageArray: [
                { name: 5, value: 5 },
                { name: 15, value: 15 },
                { name: 25, value: 25 },
                { name: "All", value: -1 }
            ],
            totalitems: 15,
            itemsPerPage: 3,
            page: 1
        };
    },
    computed: {
        numberOfPages() {
            //   return Math.ceil(this.items.length / this.itemsPerPage);
            return Math.ceil(this.totalitems / this.itemsPerPage);
        },
        getData() {
            let dataChart = {};
            var xAxisData = [];
            var yAxisData = [];
            var data1 = [];
            var data2 = [];
            var data3 = [];
            var data4 = [];

            this.gdp.forEach(item => {
                xAxisData.push(item.year);
                data1.push(item.whole);
                data2.push(item.retail);
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
                    data: ["whole sale", "retail sale"]
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
                        name: "whole sale",
                        type: "line",
                        barGap: 0,
                        label: labelOption,
                        data: data1
                    },
                    {
                        name: "retail sale",
                        type: "line",
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
        updateItemsPerPage(number) {
            this.itemsPerPage = number.value;
        },
        nextPage() {
            if (this.page + 1 <= this.numberOfPages) this.page += 1;
        },
        formerPage() {
            if (this.page - 1 >= 1) this.page -= 1;
        },
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split("-");
            return `${month}/${day}/${year}`;
        },
        parseDate(date) {
            if (!date) return null;

            const [month, day, year] = date.split("/");
            return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
        },
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
        },
        typeSelected(val) {
            this.type = val;
            if (val === "interval" || val === "interval_by_group") {
                this.inputStartEnd = true;
            } else {
            }
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
