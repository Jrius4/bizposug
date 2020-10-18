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
              icon="mdi-table-of-contents"
              title="Products"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-4"
            >
              <v-window v-model="productStep">
                <v-window-item :value="1">
                  <v-data-table
                    dense
                    show-select
                    v-model="selectedProducts"
                    :search="search"
                    item-key="barcode"
                    :headers="headers"
                    :items="products"
                    :sort-by="productSortRowsBy"
                    class="mr-2"
                    :loading="loading"
                    :options.sync="options"
                    :items-per-page="totalrowsPerPageProducts"
                    :server-items-length="totalproducts"
                  >
                    <template v-slot:top>
                      <v-row flat align="baseline" justify="space-around">
                        <v-col cols="12" md="4" sm="8">
                          <v-text-field
                            v-model="search"
                            autofocus
                            append-icon="mdi-database-search"
                            label="Quick Search/Barcode"
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
                        <v-btn icon color="teal" small>
                          <v-icon small> mdi-eye </v-icon>
                        </v-btn>
                        <v-btn icon color="blue" small>
                          <v-icon small> mdi-square-edit-outline </v-icon>
                        </v-btn>
                        <v-btn icon color="red" small>
                          <v-icon small> mdi-delete </v-icon>
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
                      <template v-if="selectedProduct !== null">
                        <editorProduct />
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
import { mapMutations, mapState } from "vuex";
import editorProduct from "./editorProduct";
export default {
  name: "ViewInventory",
  components: { editorProduct },
  data: () => {
    return {
      selectedProducts: [],
      selectedProduct: null,
      productStep: 1,
      loading: false,
      search: "",
      options: {},
      headers: [
        { text: "Avatar", align: "left", value: "avatar", sortable: false },
        { text: "NAME", align: "left", value: "name" },
        { text: "Barcode", align: "left", value: "barcode" },
        { text: "Category", align: "left", value: "category" },
        { text: "Company", align: "left", value: "company_name" },
        { text: "Post Price", align: "left", value: "cost_price" },
        { text: "Wholesale Price", align: "left", value: "wholesale_price" },
        { text: "Retailsale Price", align: "left", value: "retailsale_price" },
        { text: "Quantity", align: "left", value: "quantity" },
        { text: "Tax Percentage", align: "left", value: "tax_percentage" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    ...mapMutations({
      GET_SELECTED_PRODUCT: "productsModule/GET_SELECTED_PRODUCT",
    }),
    editItem(item) {
      if (item === null) {
        this.productStep = 2;
        this.selectedProduct = "create";
        this.GET_SELECTED_PRODUCT({ product: this.selectedProduct });
      }
    },
    tableView() {
      this.productStep = 1;
    },
    async getProducts() {
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
            .dispatch("productsModule/GET_PRODUCTS_ACTION", pagination)
            .finally(() => {
              this.loading = false;
            });
        } else if (search.length > 0) {
          if (pageNew > 1) {
            pageNew = this.employees.length === 0 ? 1 : pageNew;
            this.loading = true;
            pagination = {
              val: search,
              page: pageNew,
              sortRowsBy: sortBy[0],
              rowsPerPage: itemsPerPage,
              sortDesc: sortDesc[0],
            };
            this.$store
              .dispatch("productsModule/GET_PRODUCTS_ACTION", pagination)
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
              .dispatch("productsModule/GET_PRODUCTS_ACTION", pagination)
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
      products: (state) => state.productsModule.products,
      totalrowsPerPageProducts: (state) =>
        state.productsModule.productPagination.rowsPerPage,
      totalproducts: (state) => state.productsModule.totalproducts,
      productSortRowsBy: (state) => state.productsModule.productSortRowsBy,
    }),
  },
  watch: {
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getProducts();
      }
      this.getProducts();
    },
    options: {
      handler() {
        this.getProducts();
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
