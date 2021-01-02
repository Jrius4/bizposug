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
                            title="groups"
                            class="px-5 py-3 elevation-4"
                            color="teal darken-4"
                            titleColor="teal--text text--darken-4"
                        >
                            <v-window v-model="groupstep">
                                <v-window-item :value="1">
                                    <v-data-table
                                        dense
                                        show-select
                                        v-model="selectedgroups"
                                        :search="search"
                                        item-key="name"
                                        :headers="headers"
                                        :items="groups"
                                        :sort-by="groupsortRowsBy"
                                        class="mr-2"
                                        :loading="loading"
                                        :options.sync="options"
                                        :items-per-page="totalrowsPerPagegroups"
                                        :server-items-length="totalgroups"
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
                                                v-if="selectedgroup !== null"
                                            >
                                                <editor-group />
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
                                                v-if="group !== null"
                                            >
                                                <view-group />
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
import editorGroup from "./editorGroup";
import ViewGroup from "./ViewGroup";
export default {
    name: "ViewGroups",
    components: { editorGroup,ViewGroup },
    data: () => {
        return {
            selectedgroups: [],
            selectedgroup: null,
            groupstep: 1,
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
        this.getgroups();
    },
    methods: {
        ...mapActions({
            GET_GROUP_ACTION: "groupsModule/GET_GROUP_ACTION"
        }),
        ...mapMutations({
            GET_SELECTED_GROUP: "groupsModule/GET_SELECTED_GROUP",
            GET_OPEN_WINDOW: "groupsModule/GET_OPEN_WINDOW"
        }),
        editItem(item) {
            if (item !== null) {
                this.GET_GROUP_ACTION({ id: item.id });
                this.groupstep = 2;
                this.selectedgroup = "update";
                this.GET_SELECTED_GROUP({ action_done: this.selectedgroup });
            } else {
                this.groupstep = 2;
                this.selectedgroup = "create";
                this.GET_SELECTED_GROUP({ action_done: this.selectedgroup });
            }
        },
        viewItem(item){
            if (item !== null) {
                this.GET_GROUP_ACTION({ id: item.id });

                this.groupstep = 3;
            }
        },
        tableView() {
            this.groupstep = 1;
        },
        async getgroups() {
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
                        .dispatch("groupsModule/GET_GROUPS_ACTION", pagination)
                        .finally(() => {
                            this.loading = false;
                        });
                } else if (search.length > 0) {
                    if (pageNew > 1) {
                        pageNew = this.groups.length === 0 ? 1 : pageNew;
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
                                "groupsModule/GET_GROUPS_ACTION",
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
                                "groupsModule/GET_GROUPS_ACTION",
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
            openWindow: state => state.groupsModule.openWindow,
            group: state => state.groupsModule.group,
            message: state => state.groupsModule.message,
            groups: state => state.groupsModule.groups,
            totalrowsPerPagegroups: state =>
                state.groupsModule.groupPagination.rowsPerPage,
            totalgroups: state => state.groupsModule.totalgroups,
            groupsortRowsBy: state => state.groupsModule.groupsortRowsBy
        })
    },
    watch: {
        groupstep(val) {
            this.getgroups();
            this.GET_OPEN_WINDOW({ openWindow: val });
        },
        openWindow(val) {
            if (val === 1) this.groupstep = 1;
        },
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getgroups();
            }
            this.getgroups();
        },
        options: {
            handler() {
                this.getgroups();
            },
            deep: true
        }
    }
};
</script>

<style></style>
