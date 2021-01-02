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
                            title="Finacial List"
                            class="px-5 py-3 elevation-4"
                            color="teal darken-4"
                            titleColor="teal--text text--darken-4"
                        >
                            <v-window v-model="listStep">
                                <v-window-item :value="1">
                                        <v-data-table
                                            dense
                                            show-select
                                            v-model="selectedItems"
                                            :search="search"
                                            item-key="created_at"
                                            :headers="headers"
                                            :items="items"
                                            :sort-by="itemSortRowsBy"
                                            class="mr-2"
                                            :loading="loading"
                                            :options.sync="options"
                                            :items-per-page="totalrowsPerPageitems"
                                            :server-items-length="totalitems"
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
                                            </v-row>
                                        </template>
                                        <template v-slot:item.created_at="{ item }">
                                        <p style="color:#00897B">{{ item.created_at  }}</p>
                                        </template>
                                        <template v-slot:item.type="{ item }">
                                        <p style="color:#1E88E5;font-weight:bolder">{{ item.type  }}</p>
                                        </template>
                                        <template v-slot:item.amount="{ item }">
                                        <p>{{ item.amount | currency }}</p>
                                        </template>
                                        <template v-slot:item.current_balance="{ item }">
                                        <p>{{ item.current_balance | currency }}</p>
                                        </template>
                                        <template v-slot:item.previous_balance="{ item }">
                                        <p>{{ item.previous_balance | currency }}</p>
                                        </template>
                                        <template v-slot:item.previous_main_balance="{ item }">
                                        <p>{{ item.previous_main_balance | currency }}</p>
                                        </template>
                                        <template v-slot:item.current_main_balance="{ item }">
                                        <p>{{ item.current_main_balance | currency }}</p>
                                        </template>
                                        <template v-slot:item.action="{ item }">
                                            <v-btn icon title="View Item" @click="viewItem(item)" small dark color="teal darken-3">
                                                <v-icon>mdi-eye</v-icon>
                                            </v-btn>
                                        </template>

                                    </v-data-table>
                                </v-window-item>
                                <v-window-item :value="2">
                                    <v-card dark color="teal darken-4">
                                        <v-card-title>
                                            <v-btn icon dark @click="viewTable()" title="view table">
                                                <v-icon>
                                                     mdi-table-arrow-left
                                                </v-icon>
                                            </v-btn>
                                        </v-card-title>
                                        <v-card-text>
                                            <table class="table table-sm text-white" v-if="selectedItem !== null">
                                                <tbody>
                                                    <tr>
                                                        <th>Type</th>
                                                        <td>{{selectedItem.type}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Amount</th>
                                                        <td>{{selectedItem.amount | currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Current Balance</th>
                                                        <td>{{selectedItem.current_balance | currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Previous Balance</th>
                                                        <td>{{selectedItem.previous_balance | currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Previous Main Balance</th>
                                                        <td>{{selectedItem.previous_main_balance | currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Current Main Balance</th>
                                                        <td>{{selectedItem.current_main_balance | currency}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <td>{{selectedItem.created_at}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </v-card-text>
                                    </v-card>
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
import { mapActions, mapMutations, mapState } from 'vuex'
export default {
    name:"FinancialTable",
    data:()=>{
        return {
            listStep:1,
            selectedItems: [],
            selectedItem: null,
            loading: false,
            search: "",
            options: {},
            headers: [
                { text: "Type", align: "left", value: "type" },
                { text: "Amount", align: "left", value: "amount" },
                { text: "Current Balance", align: "left", value: "current_balance" },
                { text: "Previous Balance", align: "left", value: "previous_balance" },
                { text: "previous main balance", align: "left", value: "previous_main_balance" },
                { text: "current main balance", align: "left", value: "current_main_balance" },
                { text: "Date", align: "left", value: "created_at" },
                {
                    text: "Action",
                    align: "left",
                    value: "action",
                    sortable: false
                }
            ]
        }
    },
    computed:{
        ...mapState({
            items: state => state.accountsModule.items,
            totalrowsPerPageitems: state =>
                state.accountsModule.itemPagination.rowsPerPage,
            totalitems: state => state.accountsModule.totalitems,
            itemSortRowsBy: state => state.accountsModule.itemSortRowsBy
        })
    },
    mounted(){
        this.getItems();
    },
    destroyed(){
        this.GET_ITEMS({});
    },
    methods:{
        ...mapActions({
            GET_ITEMS_ACTION:'accountsModule/GET_ITEMS_ACTION'
        }),
        ...mapMutations({
            GET_ITEMS:'accountsModule/GET_ITEMS'
        }),
        async viewItem(item){
            this.selectedItem = item;
            this.listStep =2;
        },
        async viewTable(item){
            this.selectedItem = null;
            this.listStep =1;
        },
       async getItems(){
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
                    this.GET_ITEMS_ACTION(pagination).finally(() => {
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
                        this.GET_ITEMS_ACTION(pagination).finally(() => {
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

                        this.GET_ITEMS_ACTION(pagination).finally(() => {
                                this.loading = false;
                            });
                    }
                }
            });
        }

    },
    watch:{
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getItems();
            }
            this.getItems();
        },
        options: {
            handler() {
                this.getItems();
            },
            deep: true
        }
    },
    filters: {
        currency(value) {
        return new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "UGX",
        }).format(value);
        },
    },
}
</script>

<style>

</style>
