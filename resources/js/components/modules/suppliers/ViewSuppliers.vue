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
              icon="mdi-truck-delivery-outline"
              title="Suppliers"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-4"
            >
              <v-window v-model="supplierStep">
                <v-window-item :value="1">
                  <v-data-table
                    dense
                    show-select
                    v-model="selectedsuppliers"
                    :search="search"
                    item-key="barcode"
                    :headers="headers"
                    :items="suppliers"
                    :sort-by="supplierSortRowsBy"
                    class="mr-2"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="totalrowsPerPagesuppliers"
                    :server-items-length="totalsuppliers"
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
                    <template v-slot:item.cost_price="{ item }">
                      <p>{{ item.cost_price | currency }}</p>
                    </template>
                    <template v-slot:item.wholesale_price="{ item }">
                      <p>{{ item.wholesale_price | currency }}</p>
                    </template>
                    <template v-slot:item.retailsale_price="{ item }">
                      <p>{{ item.retailsale_price | currency }}</p>
                    </template>
                    <template v-slot:item.action="{ item }">
                      <div class="row d-flex">
                        <!-- <v-btn icon color="teal" small>
                          <v-icon small> mdi-eye </v-icon>
                        </v-btn> -->
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
                      <template v-if="selectedsupplier !== null">
                        <editorsupplier />
                      </template>
                    </v-form>
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
import editorsupplier from "./editorSupplier";
export default {
  name: "ViewSuppliers",
  components: { editorsupplier },
  data: () => {
    return {
      selectedsuppliers: [],
      selectedsupplier: null,
      supplierStep: 1,
      loading: false,
      search: "",
      options: {},
      headers: [

        { text: "NAME", align: "left", value: "name" },
        { text: "Company", align: "left", value: "company" },
        { text: "Contact", align: "left", value: "contact" },
        { text: "Address", align: "left", value: "address" },
        { text: "Paid", align: "left", value: "paid" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getsuppliers();
  },
  methods: {
      ...mapActions({
          GET_SUPPLIER_ACTION: "suppliersModule/GET_SUPPLIER_ACTION",
      }),
    ...mapMutations({
      GET_SELECTED_SUPPLIER: "suppliersModule/GET_SELECTED_SUPPLIER",
      GET_OPEN_WINDOW:"suppliersModule/GET_OPEN_WINDOW",
    }),
    editItem(item) {
      if(item!==null) {
        this.GET_SUPPLIER_ACTION({id:item.id});
        this.supplierStep = 2;
        this.selectedsupplier = "update";
        this.GET_SELECTED_SUPPLIER({ action_done: this.selectedsupplier });
    }
    else{
        this.supplierStep = 2;
        this.selectedsupplier = "create";
        this.GET_SELECTED_SUPPLIER({ action_done: this.selectedsupplier });
    }

    },
    tableView() {
      this.supplierStep = 1;
    },
    async getsuppliers() {
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
            .dispatch("suppliersModule/GET_SUPPLIERS_ACTION", pagination)
            .finally(() => {
              this.loading = false;
            });
        } else if (search.length > 0) {
          if (pageNew > 1) {
            pageNew = this.suppliers.length === 0 ? 1 : pageNew;
            this.loading = true;
            pagination = {
              val: search,
              page: pageNew,
              sortRowsBy: sortBy[0],
              rowsPerPage: itemsPerPage,
              sortDesc: sortDesc[0],
            };
            this.$store
              .dispatch("suppliersModule/GET_SUPPLIERS_ACTION", pagination)
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
              .dispatch("suppliersModule/GET_SUPPLIERS_ACTION", pagination)
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
        openWindow: state => state.suppliersModule.openWindow,
        supplier: state => state.suppliersModule.supplier,
        message: state => state.suppliersModule.message,
      suppliers: (state) => state.suppliersModule.suppliers,
      totalrowsPerPagesuppliers: (state) =>
        state.suppliersModule.supplierPagination.rowsPerPage,
      totalsuppliers: (state) => state.suppliersModule.totalsuppliers,
      supplierSortRowsBy: (state) => state.suppliersModule.supplierSortRowsBy,
    }),
  },
  watch: {
      supplierStep(val){
          this.getsuppliers();
          this.GET_OPEN_WINDOW({openWindow:val});

      },
      openWindow(val){
          if(val === 1)this.supplierStep = 1;
      },
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getsuppliers();
      }
      this.getsuppliers();
    },
    options: {
      handler() {
        this.getsuppliers();
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
