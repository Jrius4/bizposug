<template>
    <v-col cols="12" md="8">
        <v-card elevation="6">
            <v-card-title class="teal text--blue-gray">
                <h3 class="text-center">Products Categories Sales</h3>
            </v-card-title>
            <v-card-actions>
                <v-row align="baseline" justify="center">
                    <v-col cols="12" md="4">
                        <v-menu bottom right>
                            <template v-slot:activator="{ on: { click } }">
                                <v-btn outlined v-on:click="click" small>
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>mdi-menu-down</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="typeSelected('daily')">
                                    <v-list-item-title>Daily</v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="typeSelected('weekly')">
                                    <v-list-item-title
                                        >Weekly</v-list-item-title
                                    >
                                </v-list-item>

                                <v-list-item @click="typeSelected('monthly')">
                                    <v-list-item-title
                                        >Monthly</v-list-item-title
                                    >
                                </v-list-item>

                                <v-list-item @click="typeSelected('yearly')">
                                    <v-list-item-title
                                        >Yearly</v-list-item-title
                                    >
                                </v-list-item>

                                <v-list-item @click="typeSelected('interval')">
                                    <v-list-item-title
                                        >By Interval</v-list-item-title
                                    >
                                </v-list-item>

                            </v-list>
                        </v-menu>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-menu bottom right>
                            <template v-slot:activator="{ on }">
                                <v-btn outlined v-on="on" small>
                                    <span>SortBy {{ sortByToLabel[saleSortRowsBy] }}</span>
                                    <v-icon right>mdi-menu-down</v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="sortBySelected('created_at')">
                                    <v-list-item-title>SALE DATE</v-list-item-title>
                                </v-list-item>
                                <v-list-item
                                    @click="sortBySelected('no_products')"
                                >
                                    <v-list-item-title
                                        >NUMBER PRODUCTS</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="sortBySelected('amount')">
                                    <v-list-item-title
                                        >AMOUNT</v-list-item-title
                                    >
                                </v-list-item>

                            </v-list>
                        </v-menu>
                        <div>
                            <v-btn dark :color="`${querySortDesc === 'false'?'#546E7A':'teal'}`" small @click="querySortDesc = 'false'">Asc <v-icon>mdi-sort-descending</v-icon> </v-btn>
                            <v-btn dark :color="`${querySortDesc === 'true'?'#546E7A':'teal'}`" small @click="querySortDesc = 'true'">Desc <v-icon>mdi-sort-ascending</v-icon></v-btn>
                        </div>
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
                                                                    dateFormatted1 = parseDate(
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
                                                                    dateFormatted2 = parseDate(
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

                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            color="red darken-3"
                                            text
                                            @click="cancelInterval()"
                                            ><v-icon>mdi-close-circle</v-icon>
                                            cancel</v-btn
                                        >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="green darken-2"
                                            @click="submitInterval()"
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
import EchartMain from "../main/Echarts";

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

import theme from "../main/themes/theme.json";
import { mapState } from 'vuex';
EchartMain.registerTheme("ovilia-green", theme);
export default {
    name: "SalesCategoriesPieChart",
    components: { EchartMain },
    data: vm => {
        let data = [];
        const options = qs.parse(location.search, { ignoreQueryPrefix: true });
        return {
            querySortDesc:'true',
            search: "",
            loading: false,
            page: 1,
            itemsPerPage: 10,
            querySortBy:"created_at",
            sortByToLabel:{
                no_products:"no_products",
                created_at:"created_at",
                amount:"amount"
            },
            queryType:"daily",
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
                { name: 10, value: 10 },
                { name: 15, value: 15 },
                { name: 25, value: 25 },
            ],
            totalitems: 15,
            itemsPerPage: 10,
            page: 1
        };
    },
    mounted(){
        this.getSales();
    },
    computed: {
         ...mapState({
             type: (state) => state.salesModule.type,
            sales: (state) => state.salesModule.sales,
             currentPagesales: (state) =>
                state.salesModule.salePagination.page,
            totalrowsPerPagesales: (state) =>
                state.salesModule.salePagination.rowsPerPage,
            totalsales: (state) => state.salesModule.totalsales,
            saleSortDesc: (state) => state.salesModule.saleSortDesc,
            saleSortRowsBy: (state) => state.salesModule.saleSortRowsBy,
        }),
        numberOfPages() {

            return Math.ceil(this.totalsales / this.itemsPerPage);
        },
        getData() {
            let dataChart = {};
            var xAxisData = [];
            var yAxisData = [];
            var legendData = [];
            var data1 = [];
            var data2 = [];
            var data3 = [];
            var data4 = [];
            var legendSeries = [];
            var legendColors = [];
            var selected = [];
            var seriesData = [];


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
            if(this.type === 'daily'){



                this.sales.forEach((item,k)=>{

                    legendData.push(item.prodgroup);
                    seriesData.push({
                        name:`${item.prodgroup} ${this.querySortBy === `no_products`?', '+(this.formatCurrency(item.amount))+',':''} ${this.formatCurrency(item.amount)}, ${item.sale_day}`,
                        value:this.querySortBy === `no_products`? item.no_products:item.amount
                    });

                })
                 legendColors = [
                    "#00838F",
                    "#03A9F4",
                    "#C62828",
                    "#F4511E",
                    "#757575",
                    "#D81B60",
                    "#43A047",
                    "#3949AB",
                    "#F57F17",
                    "#795548",
                    "#0097A7",
                    "#512DA8",
                    "#B71C1C",
                    "#90A4AE",
                    "#66BB6A",
                    "#5E35B1",
                    "#26C6DA",
                    "#D32F2F",
                    "#004D40",
                    "#FF9800",
                    "#0277BD",
                    "#8D6E63",
                    "#00BCD4",
                    "#303F9F",
                    "#2E7D32",
                    "#FF5722",
                    "#616161",
                    "#A1887F",
                    "#3F51B5",
                    "#9E9D24",
                    "#546E7A",
                    "#C62828",
                    "#4DB6AC",
                    ];



                Object.assign(dataChart, {

                    color:legendColors,
                    grid: {
                        left: "2%",
                        right: "3%",
                        bottom: "3%",
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    title: {
                        text: "Sales Daily (UGX)",
                        left: "right",
                        top: 20
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: legendData,
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

                    series:[
                        {
                            name:"Sales",
                            type:'pie',
                            data:seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ],
                    animationEasing: "elasticOut",
                    animationDelayUpdate: function(idx) {
                        return idx * 5;
                    }
                });

            }


            else if(this.type === 'weekly'){

                this.sales.forEach((item,k)=>{

                    legendData.push(`${item.prodgroup}`);
                    seriesData.push({
                        name:`${item.prodgroup},${this.querySortBy === `no_products`?item.amount+'(UGX),':''} ${this.formatCurrency(item.amount)}, ${item.week_of_the_month}Wk/${item.month_of_the_year}/${item.year}`,
                        value:this.querySortBy === `no_products`? item.no_products:item.amount
                    });

                })
                 legendColors = [
                    "#00838F",
                    "#03A9F4",
                    "#C62828",
                    "#F4511E",
                    "#757575",
                    "#D81B60",
                    "#43A047",
                    "#3949AB",
                    "#F57F17",
                    "#795548",
                    "#0097A7",
                    "#512DA8",
                    "#B71C1C",
                    "#90A4AE",
                    "#66BB6A",
                    "#5E35B1",
                    "#26C6DA",
                    "#D32F2F",
                    "#004D40",
                    "#FF9800",
                    "#0277BD",
                    "#8D6E63",
                    "#00BCD4",
                    "#303F9F",
                    "#2E7D32",
                    "#FF5722",
                    "#616161",
                    "#A1887F",
                    "#3F51B5",
                    "#9E9D24",
                    "#546E7A",
                    "#C62828",
                    "#4DB6AC",
                    ];



                Object.assign(dataChart, {

                    color:legendColors,
                    grid: {
                        left: "2%",
                        right: "3%",
                        bottom: "3%",
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    title: {
                        text: "Sales Weekly (UGX)",
                        left: "right",
                        top: 20
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: legendData,
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

                    series:[
                        {
                            name:"Sales",
                            type:'pie',
                            data:seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ],
                    animationEasing: "elasticOut",
                    animationDelayUpdate: function(idx) {
                        return idx * 5;
                    }
                });

            }



            else if(this.type === 'monthly'){

                this.sales.forEach((item,k)=>{

                    legendData.push(`${item.prodgroup}`);
                    seriesData.push({
                        name:`${item.prodgroup},${this.querySortBy === `no_products`?', '+(this.formatCurrency(item.amount))+',':''} ${this.formatCurrency(item.amount)}, ${item.mnth}/${item.yr}`,
                        value:this.querySortBy === `no_products`? item.no_products:item.amount
                    });

                })
                 legendColors = [
                    "#00838F",
                    "#03A9F4",
                    "#C62828",
                    "#F4511E",
                    "#757575",
                    "#D81B60",
                    "#43A047",
                    "#3949AB",
                    "#F57F17",
                    "#795548",
                    "#0097A7",
                    "#512DA8",
                    "#B71C1C",
                    "#90A4AE",
                    "#66BB6A",
                    "#5E35B1",
                    "#26C6DA",
                    "#D32F2F",
                    "#004D40",
                    "#FF9800",
                    "#0277BD",
                    "#8D6E63",
                    "#00BCD4",
                    "#303F9F",
                    "#2E7D32",
                    "#FF5722",
                    "#616161",
                    "#A1887F",
                    "#3F51B5",
                    "#9E9D24",
                    "#546E7A",
                    "#C62828",
                    "#4DB6AC",
                    ];



                Object.assign(dataChart, {

                    color:legendColors,
                    grid: {
                        left: "2%",
                        right: "3%",
                        bottom: "3%",
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    title: {
                        text: "Sales Monthly (UGX)",
                        left: "right",
                        top: 20
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: legendData,
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

                    series:[
                        {
                            name:"Sales",
                            type:'pie',
                            data:seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ],
                    animationEasing: "elasticOut",
                    animationDelayUpdate: function(idx) {
                        return idx * 5;
                    }
                });

            }




            else if(this.type === 'yearly'){

                this.sales.forEach((item,k)=>{

                    legendData.push(`${item.prodgroup}`);
                    seriesData.push({
                        name:`${item.prodgroup},${this.querySortBy === `no_products`?', '+(this.formatCurrency(item.amount))+',':''} ${this.formatCurrency(item.amount)}, ${item.yr}`,
                        value:this.querySortBy === `no_products`? item.no_products:item.amount
                    });

                })
                 legendColors = [
                    "#00838F",
                    "#03A9F4",
                    "#C62828",
                    "#F4511E",
                    "#757575",
                    "#D81B60",
                    "#43A047",
                    "#3949AB",
                    "#F57F17",
                    "#795548",
                    "#0097A7",
                    "#512DA8",
                    "#B71C1C",
                    "#90A4AE",
                    "#66BB6A",
                    "#5E35B1",
                    "#26C6DA",
                    "#D32F2F",
                    "#004D40",
                    "#FF9800",
                    "#0277BD",
                    "#8D6E63",
                    "#00BCD4",
                    "#303F9F",
                    "#2E7D32",
                    "#FF5722",
                    "#616161",
                    "#A1887F",
                    "#3F51B5",
                    "#9E9D24",
                    "#546E7A",
                    "#C62828",
                    "#4DB6AC",
                    ];



                Object.assign(dataChart, {

                    color:legendColors,
                    grid: {
                        left: "2%",
                        right: "3%",
                        bottom: "3%",
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    title: {
                        text: "Sales Yearly (UGX)",
                        left: "right",
                        top: 20
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: legendData,
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

                    series:[
                        {
                            name:"Sales",
                            type:'pie',
                            data:seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ],
                    animationEasing: "elasticOut",
                    animationDelayUpdate: function(idx) {
                        return idx * 5;
                    }
                });

            }



            else if(this.type === 'interval'){

                this.sales.forEach((item,k)=>{

                    legendData.push(`${item.prodgroup}`);
                    seriesData.push({
                        name:`${item.prodgroup},${this.querySortBy === `no_products`?', '+(this.formatCurrency(item.amount))+',':''} ${this.formatCurrency(item.amount)}, ${item.p_period}`,
                        value:this.querySortBy === `no_products`? item.no_products:item.amount
                    });

                })
                 legendColors = [
                    "#00838F",
                    "#03A9F4",
                    "#C62828",
                    "#F4511E",
                    "#757575",
                    "#D81B60",
                    "#43A047",
                    "#3949AB",
                    "#F57F17",
                    "#795548",
                    "#0097A7",
                    "#512DA8",
                    "#B71C1C",
                    "#90A4AE",
                    "#66BB6A",
                    "#5E35B1",
                    "#26C6DA",
                    "#D32F2F",
                    "#004D40",
                    "#FF9800",
                    "#0277BD",
                    "#8D6E63",
                    "#00BCD4",
                    "#303F9F",
                    "#2E7D32",
                    "#FF5722",
                    "#616161",
                    "#A1887F",
                    "#3F51B5",
                    "#9E9D24",
                    "#546E7A",
                    "#C62828",
                    "#4DB6AC",
                    ];



                Object.assign(dataChart, {

                    color:legendColors,
                    grid: {
                        left: "2%",
                        right: "3%",
                        bottom: "3%",
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    title: {
                        text: `Sales from ${this.start} to ${this.end} (UGX)`,
                        left: "right",
                        top: 20
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: legendData,
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

                    series:[
                        {
                            name:"Sales",
                            type:'pie',
                            data:seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ],
                    animationEasing: "elasticOut",
                    animationDelayUpdate: function(idx) {
                        return idx * 5;
                    }
                });

            }


            return dataChart;
        }
    },
    methods: {
        async getSales() {
            return new Promise((resolve, reject) => {
                this.loading = true;
                let pageNew = this.page;
                let pagination = {};
                let search = this.search;

                pagination = {
                    data_type:true,
                    val: search,
                    queryType:this.queryType,
                    page: pageNew,
                    sortRowsBy: this.querySortBy,
                    rowsPerPage: this.itemsPerPage,
                    sortDesc: this.querySortDesc
                };
                if(`${this.type}`.includes('interval')){
                    Object.assign(pagination,{
                        start:this.start,
                        end:this.end,
                    })
                }

                this.$store
                .dispatch("salesModule/GET_SALES_CATEGORIES_ACTION", pagination)
                .finally(() => {
                    this.loading = false;
                });

            });
        },
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

            const [month, day, year] = date.split("-");
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
            this.queryType = val;
            if (val === "interval") {
                this.inputStartEnd = true;
            } else {
            }
        },
        sortBySelected(val){
            this.querySortBy =val;
        },
        cancelInterval(){
            this.queryType = "daily";
            this.start = new Date().toISOString().substr(0, 10);
            this.end = new Date().toISOString().substr(0, 10);
            this.inputStartEnd = false;
        },
        submitInterval(){

            this.getSales();
                this.inputStartEnd = false;


        },
        formatCurrency(value){
             return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX"
            }).format(value);
        }
    },
    filters: {
        currency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX"
            }).format(value);
        }
    },
    watch: {
        type(val){
            this.queryType = val;
            this.page = 1;
        },
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
        },
        queryType(){
            this.getSales();
        },
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getSales();
            }
            this.getSales();
        },
        itemsPerPage() {
            this.getSales();
        },
        currentPagesales(val){
            this.page = val;
        },
        totalrowsPerPagesales(val){
            this.page = 1;
            this.itemsPerPage = val;
        },
        page() {
            this.getSales();
        },
        sortDesc() {
            this.getSales();
        },
        querySortBy(){
            this.page = 1;
            this.getSales();
        },
        querySortDesc(){
            this.page = 1;
            this.getSales();
        }

    }
};
</script>

<style></style>
