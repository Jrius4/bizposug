<template>
    <v-app style="height: 100px">
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="center">
                    <v-col cols="12" lg="12" md="12">
                        <base-v-component
                            heading="Items"
                            class="teal--text text--darken-4"
                        />
                        <base-material-card
                            icon="mdi-account-group"
                            title="brands"
                            class="px-5 py-3 elevation-4"
                            color="teal darken-4"
                            titleColor="teal--text text--darken-4"
                        >
                            <v-window v-model="brandstep">
                                <v-window-item :value="1">
                                    <v-data-table
                                        dense
                                        show-select
                                        v-model="selectedbrands"
                                        :search="search"
                                        item-key="name"
                                        :headers="headers"
                                        :items="brands"
                                        :sort-by="brandsortRowsBy"
                                        class="mr-2"
                                        :loading="loading"
                                        :options.sync="options"
                                        :items-per-page="totalrowsPerPagebrands"
                                        :server-items-length="totalbrands"
                                    >
                                        <template v-slot:top>
                                            <v-row
                                                flat
                                                align="baseline"
                                                justify="space-around"
                                            >
                                                <v-col cols="12" md="4" sm="8">
                                                    <v-text-field
                                                        v-model="search"
                                                        autofocus
                                                        append-icon="mdi-database-search"
                                                        label="Quick Search"
                                                        clearable
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="12" md="3" sm="4">
                                                    <v-btn
                                                        small
                                                        dark
                                                        color="teal darken-4"
                                                        @click="editItem(null)"
                                                    >
                                                        <v-icon
                                                            >mdi-plus</v-icon
                                                        >
                                                        Add New Item
                                                    </v-btn>
                                                    <v-btn
                                                        small
                                                        dark
                                                        color="grey-blue darken-4"
                                                    >
                                                        <v-icon
                                                            >mdi-download-multiple</v-icon
                                                        >
                                                        Export CSV
                                                    </v-btn>
                                                </v-col>
                                            </v-row>
                                        </template>
                                        <template v-slot:item.products="{item}">
                                            {{item.products.length}}
                                        </template>

                                        <template v-slot:item.action="{ item }">
                                            <div class="row d-flex">
                                                <v-btn icon color="green" @click="viewItem(item)" small>
                          <v-icon small> mdi-eye </v-icon>
                        </v-btn>
                                                <v-btn
                                                    icon
                                                    color="blue"
                                                    @click="editItem(item)"
                                                    small
                                                >
                                                    <v-icon small>
                                                        mdi-square-edit-outline
                                                    </v-icon>
                                                </v-btn>
                                            </div>
                                        </template>
                                    </v-data-table>
                                </v-window-item>
                                <v-window-item :value="2">
                                    <template>
                                        <v-form ref="form">
                                            <v-row
                                                align="baseline"
                                                justify="center"
                                            >
                                                <v-btn
                                                    text
                                                    large
                                                    @click="tableView"
                                                    color="teal darken-3"
                                                    dark
                                                >
                                                    <v-icon
                                                        >mdi-table-arrow-left</v-icon
                                                    >
                                                    Back
                                                </v-btn>
                                            </v-row>
                                            <template
                                                v-if="selectedbrand !== null"
                                            >
                                                <editor-brand />
                                            </template>
                                        </v-form>
                                    </template>
                                </v-window-item>
                                <v-window-item :value="3">
                                    <v-row
                                                align="baseline"
                                                justify="center"
                                            >
                                                <v-btn
                                                    text
                                                    large
                                                    @click="tableView"
                                                    color="teal darken-3"
                                                    dark
                                                >
                                                    <v-icon
                                                        >mdi-table-arrow-left</v-icon
                                                    >
                                                    Back
                                                </v-btn>
                                            </v-row>
                                    <template
                                                v-if="brand !== null"
                                            >
                                                <view-brand />
                                            </template>
                                </v-window-item>
                            </v-window>
                        </base-material-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
import editorBrand from "./editorBrand";
import ViewBrand from "./ViewBrand";
export default {
    name: "ViewBrands",
    components: { editorBrand,ViewBrand },
    data: () => {
        return {
            selectedbrands: [],
            selectedbrand: null,
            brandstep: 1,
            loading: false,
            search: "",
            options: {},
            headers: [
                { text: "NAME", align: "left", value: "name" },
                { text: "No Of Products", align: "left", value: "products",sortable: false },
                {
                    text: "Action",
                    align: "left",
                    value: "action",
                    sortable: false
                }
            ]
        };
    },
    mounted() {
        this.getbrands();
    },
    methods: {
        ...mapActions({
            GET_BRAND_ACTION: "brandsModule/GET_BRAND_ACTION"
        }),
        ...mapMutations({
            GET_SELECTED_BRAND: "brandsModule/GET_SELECTED_BRAND",
            GET_OPEN_WINDOW: "brandsModule/GET_OPEN_WINDOW"
        }),
        editItem(item) {
            if (item !== null) {
                this.GET_BRAND_ACTION({ id: item.id });
                this.brandstep = 2;
                this.selectedbrand = "update";
                this.GET_SELECTED_BRAND({ action_done: this.selectedbrand });
            } else {
                this.brandstep = 2;
                this.selectedbrand = "create";
                this.GET_SELECTED_BRAND({ action_done: this.selectedbrand });
            }
        },
        viewItem(item){
            if (item !== null) {
                this.GET_BRAND_ACTION({ id: item.id });

                this.brandstep = 3;
            }
        },
        tableView() {
            this.brandstep = 1;
        },
        async getbrands() {
            this.loading = true;
            return new Promise((resolve, reject) => {
                const { sortBy, sortDesc, page, itemsPerPage } = this.options;
                const search = this.search;
                let pageNew = page;
                let pagination = {};
                if (!search) {
                    pagination = {
                        val: search,
                        page: pageNew,
                        sortRowsBy: sortBy[0],
                        rowsPerPage: itemsPerPage,
                        sortDesc: sortDesc[0]
                    };

                    this.$store
                        .dispatch("brandsModule/GET_BRANDS_ACTION", pagination)
                        .finally(() => {
                            this.loading = false;
                        });
                } else if (search.length > 0) {
                    if (pageNew > 1) {
                        pageNew = this.brands.length === 0 ? 1 : pageNew;
                        this.loading = true;
                        pagination = {
                            val: search,
                            page: pageNew,
                            sortRowsBy: sortBy[0],
                            rowsPerPage: itemsPerPage,
                            sortDesc: sortDesc[0]
                        };
                        this.$store
                            .dispatch(
                                "brandsModule/GET_BRANDS_ACTION",
                                pagination
                            )
                            .finally(() => {
                                this.loading = false;
                            });
                    } else {
                        pageNew = 1;
                        this.loading = true;
                        pagination = {
                            val: search,
                            page: pageNew,
                            sortRowsBy: sortBy[0],
                            rowsPerPage: itemsPerPage,
                            sortDesc: sortDesc[0]
                        };

                        this.$store
                            .dispatch(
                                "brandsModule/GET_BRANDS_ACTION",
                                pagination
                            )
                            .finally(() => {
                                this.loading = false;
                            });
                    }
                }
            });
        }
    },
    computed: {
        ...mapState({
            openWindow: state => state.brandsModule.openWindow,
            brand: state => state.brandsModule.brand,
            message: state => state.brandsModule.message,
            brands: state => state.brandsModule.brands,
            totalrowsPerPagebrands: state =>
                state.brandsModule.brandPagination.rowsPerPage,
            totalbrands: state => state.brandsModule.totalbrands,
            brandsortRowsBy: state => state.brandsModule.brandsortRowsBy
        })
    },
    watch: {
        brandstep(val) {
            this.getbrands();
            this.GET_OPEN_WINDOW({ openWindow: val });
        },
        openWindow(val) {
            if (val === 1) this.brandstep = 1;
        },
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getbrands();
            }
            this.getbrands();
        },
        options: {
            handler() {
                this.getbrands();
            },
            deep: true
        }
    }
};
</script>

<style></style>
