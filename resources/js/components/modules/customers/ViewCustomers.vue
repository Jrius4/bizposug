<template>
  <v-app style="height: 100px">
    <v-main>
      <v-container fluid>
        <v-row align="baseline" justify="center">
          <v-col cols="12" lg="12" md="12">
            <base-v-component
              heading="Customers"
              class="teal--text text--darken-4"
            />
            <base-material-card
              icon="mdi-account-group"
              title="customers"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-4"
            >
              <v-window v-model="customerStep">
                <v-window-item :value="1">
                  <v-data-table
                    dense
                    show-select
                    v-model="selectedcustomers"
                    :search="search"
                    item-key="contact"
                    :headers="headers"
                    :items="customers"
                    :sort-by="customerSortRowsBy"
                    class="mr-2"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="totalrowsPerPagecustomers"
                    :server-items-length="totalcustomers"
                  >
                    <template v-slot:top>
                      <v-row flat align="baseline" justify="space-around">
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
                            <v-icon>mdi-plus</v-icon>
                            Add New Item
                          </v-btn>
                          <v-btn small dark color="grey-blue darken-4">
                            <v-icon>mdi-download-multiple</v-icon>
                            Export CSV
                          </v-btn>
                        </v-col>
                      </v-row>
                    </template>
                    <template v-slot:item.avatar="{ item }">
                      <v-avatar tile size="50" class="elevation-4 shadow-md">
                        <img :src="`${item.avatar}`" alt="" />
                      </v-avatar>
                    </template>

                    <template v-slot:item.balance="{ item }">
                      <p>{{ item.balance | currency }}</p>
                    </template>
                    <template v-slot:item.action="{ item }">
                      <div class="row d-flex">
                       <v-btn icon color="green" @click="viewItem(item)" small>
                            <v-icon small> mdi-eye </v-icon>
                        </v-btn>
                        <v-btn icon color="blue" @click="editItem(item)" small>
                          <v-icon small> mdi-square-edit-outline </v-icon>
                        </v-btn>

                      </div>
                    </template>
                  </v-data-table>
                </v-window-item>
                <v-window-item :value="2">
                  <template>
                    <v-form ref="form">
                      <v-row align="baseline" justify="center">
                        <v-btn
                          text
                          large
                          @click="tableView"
                          color="teal darken-3"
                          dark
                        >
                          <v-icon>mdi-table-arrow-left</v-icon> Back
                        </v-btn>
                      </v-row>
                      <template v-if="selectedcustomer !== null">
                        <editor-customer />
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
                                                v-if="customer !== null"
                                            >
                                                <view-customer />
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
import editorCustomer from "./editorCustomer";
import ViewCustomer from "./ViewCustomer";
export default {
  name: "ViewCustomers",
  components: { editorCustomer,ViewCustomer },
  data: () => {
    return {
      selectedcustomers: [],
      selectedcustomer: null,
      customerStep: 1,
      loading: false,
      search: "",
      options: {},
      headers: [

        { text: "NAME", align: "left", value: "name" },
        { text: "Contact", align: "left", value: "contact" },
        { text: "Address", align: "left", value: "address" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getcustomers();
  },
  methods: {
      ...mapActions({
          GET_CUSTOMER_ACTION: "customersModule/GET_CUSTOMER_ACTION",
      }),
    ...mapMutations({
      GET_SELECTED_CUSTOMER: "customersModule/GET_SELECTED_CUSTOMER",
      GET_OPEN_WINDOW:"customersModule/GET_OPEN_WINDOW",
      HANDLE_EDITOR_STATE:"customersModule/HANDLE_EDITOR_STATE",
    }),
    viewItem(item){
        if (item !== null) {
            this.GET_CUSTOMER_ACTION({ id: item.id });

            this.customerStep = 3;
        }
    },
    editItem(item) {
      if(item!==null) {
        this.GET_CUSTOMER_ACTION({id:item.id});
        this.customerStep = 2;
        this.selectedcustomer = "update";
        this.GET_SELECTED_CUSTOMER({ action_done: this.selectedcustomer });
    }
    else{
        this.customerStep = 2;
        this.selectedcustomer = "create";
        this.GET_SELECTED_CUSTOMER({ action_done: this.selectedcustomer });
    }

    },
    tableView() {
      this.customerStep = 1;
    },
    async getcustomers() {
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
            sortDesc: sortDesc[0],
          };

          this.$store
            .dispatch("customersModule/GET_CUSTOMERS_ACTION", pagination)
            .finally(() => {
              this.loading = false;
            });
        } else if (search.length > 0) {
          if (pageNew > 1) {
            pageNew = this.customers.length === 0 ? 1 : pageNew;
            this.loading = true;
            pagination = {
              val: search,
              page: pageNew,
              sortRowsBy: sortBy[0],
              rowsPerPage: itemsPerPage,
              sortDesc: sortDesc[0],
            };
            this.$store
              .dispatch("customersModule/GET_CUSTOMERS_ACTION", pagination)
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
              sortDesc: sortDesc[0],
            };

            this.$store
              .dispatch("customersModule/GET_CUSTOMERS_ACTION", pagination)
              .finally(() => {
                this.loading = false;
              });
          }
        }
      });
    },
  },
  computed: {
    ...mapState({
        openWindow: state => state.customersModule.openWindow,
        customer: state => state.customersModule.customer,
        message: state => state.customersModule.message,
      customers: (state) => state.customersModule.customers,
      totalrowsPerPagecustomers: (state) =>
        state.customersModule.customerPagination.rowsPerPage,
      totalcustomers: (state) => state.customersModule.totalcustomers,
      customerSortRowsBy: (state) => state.customersModule.customerSortRowsBy,
    }),
  },
  watch: {
      customerStep(val){
          this.getcustomers();
          this.GET_OPEN_WINDOW({openWindow:val});

      },
      openWindow(val){
          if(val === 1){
              this.customerStep = 1;
              this.HANDLE_EDITOR_STATE({action_done:'clear'});
              };
      },
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getcustomers();
      }
      this.getcustomers();
    },
    options: {
      handler() {
        this.getcustomers();
      },
      deep: true,
    },
  },
  filters: {
    currency(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "UGX",
      }).format(value);
    },
  },
};
</script>

<style></style>
