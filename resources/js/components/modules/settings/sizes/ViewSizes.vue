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
                            title="sizes"
                            class="px-5 py-3 elevation-4"
                            color="teal darken-4"
                            titleColor="teal--text text--darken-4"
                        >
                            <v-window v-model="sizestep">
                                <v-window-item :value="1">
                                    <v-data-table
                                        dense
                                        show-select
                                        v-model="selectedsizes"
                                        :search="search"
                                        item-key="name"
                                        :headers="headers"
                                        :items="sizes"
                                        :sort-by="sizesortRowsBy"
                                        class="mr-2"
                                        :loading="loading"
                                        :options.sync="options"
                                        :items-per-page="totalrowsPerPagesizes"
                                        :server-items-length="totalsizes"
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
                                                v-if="selectedsize !== null"
                                            >
                                                <editor-size />
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
                                                v-if="size !== null"
                                            >
                                                <view-size />
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
import editorSize from "./editorSize";
import ViewSize from "./ViewSize";
export default {
    name: "ViewSizes",
    components: { editorSize,ViewSize },
    data: () => {
        return {
            selectedsizes: [],
            selectedsize: null,
            sizestep: 1,
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
        this.getsizes();
    },
    methods: {
        ...mapActions({
            GET_SIZE_ACTION: "sizesModule/GET_SIZE_ACTION"
        }),
        ...mapMutations({
            GET_SELECTED_SIZE: "sizesModule/GET_SELECTED_SIZE",
            GET_OPEN_WINDOW: "sizesModule/GET_OPEN_WINDOW"
        }),
        editItem(item) {
            if (item !== null) {
                this.GET_SIZE_ACTION({ id: item.id });
                this.sizestep = 2;
                this.selectedsize = "update";
                this.GET_SELECTED_SIZE({ action_done: this.selectedsize });
            } else {
                this.sizestep = 2;
                this.selectedsize = "create";
                this.GET_SELECTED_SIZE({ action_done: this.selectedsize });
            }
        },
        viewItem(item){
            if (item !== null) {
                this.GET_SIZE_ACTION({ id: item.id });

                this.sizestep = 3;
            }
        },
        tableView() {
            this.sizestep = 1;
        },
        async getsizes() {
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
                        .dispatch("sizesModule/GET_SIZES_ACTION", pagination)
                        .finally(() => {
                            this.loading = false;
                        });
                } else if (search.length > 0) {
                    if (pageNew > 1) {
                        pageNew = this.sizes.length === 0 ? 1 : pageNew;
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
                                "sizesModule/GET_SIZES_ACTION",
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
                                "sizesModule/GET_SIZES_ACTION",
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
            openWindow: state => state.sizesModule.openWindow,
            size: state => state.sizesModule.size,
            message: state => state.sizesModule.message,
            sizes: state => state.sizesModule.sizes,
            totalrowsPerPagesizes: state =>
                state.sizesModule.sizePagination.rowsPerPage,
            totalsizes: state => state.sizesModule.totalsizes,
            sizesortRowsBy: state => state.sizesModule.sizesortRowsBy
        })
    },
    watch: {
        sizestep(val) {
            this.getsizes();
            this.GET_OPEN_WINDOW({ openWindow: val });
        },
        openWindow(val) {
            if (val === 1) this.sizestep = 1;
        },
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getsizes();
            }
            this.getsizes();
        },
        options: {
            handler() {
                this.getsizes();
            },
            deep: true
        }
    }
};
</script>

<style></style>
