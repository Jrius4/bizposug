<template>
  <v-row>
    <v-col cols="12" lg="12">
      <v-window v-model="paymentStep">
        <v-window-item :value="1">
          <v-data-table
            dense
            show-select
            v-model="selectedPayments"
            :search="search"
            item-key="serial_no"
            :headers="headers"
            :items="payments"
            :sort-by="paymentSortRowsBy"
            class="mr-2"
            :loading="loading"
            :options.sync="options"
            :items-per-page="totalrowsPerPagePayments"
            :server-items-length="totalpayments"
          >
            <template v-slot:top>
              <v-row flat align="baseline" justify="space-around">
                <v-col cols="12" md="4" sm="8">
                  <v-text-field
                    v-model="search"
                    autofocus
                    append-icon="mdi-database-search"
                    label="Quick Search/Payment"
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
                    Make Payment
                  </v-btn>
                  <v-btn small dark color="grey-blue darken-4">
                    <v-icon>mdi-download-multiple</v-icon>
                    Export CSV
                  </v-btn>
                </v-col>
              </v-row>
            </template>

            <template v-slot:item.paid="{ item }">
              <p>{{ item.paid | currency }}</p>
            </template>
            <template v-slot:item.balance="{ item }">
              <p>{{ item.balance | currency }}</p>
            </template>
            <template v-slot:item.reciever="{ item }">
              <p>{{ item.reciever.name }}</p>
            </template>
          </v-data-table>
        </v-window-item>
      </v-window>
    </v-col>
  </v-row>
</template>

<script>
import { mapState } from "vuex";
export default {
  name: "Payments",
  data: (vm) => {
    return {
      search: "",
      loading: false,
      paymentStep: 1,
      selectedPayments: [],
      selectedPayment: null,
      options: {},
      headers: [
        { text: "S/N", align: "left", value: "serial_no" },
        { text: "Reciever", align: "left", value: "reciever", sortable: false },
        { text: "Type", align: "left", value: "type_payment" },
        { text: "Paid", align: "left", value: "paid" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "Date", align: "left", value: "created_at" },
      ],
    };
  },
  mounted() {
    this.getPayments();
  },
  computed: {
    ...mapState({
      payments: (state) => state.paymentsModule.payments,
      totalrowsPerPagePayments: (state) =>
        state.paymentsModule.paymentPagination.rowsPerPage,
      totalpayments: (state) => state.paymentsModule.totalpayments,
      paymentSortRowsBy: (state) => state.paymentsModule.paymentSortRowsBy,
    }),
  },
  methods: {
    async getPayments() {
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
            .dispatch("paymentsModule/GET_PAYMENTS_ACTION", pagination)
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
              .dispatch("paymentsModule/GET_PAYMENTS_ACTION", pagination)
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
              .dispatch("paymentsModule/GET_PAYMENTS_ACTION", pagination)
              .finally(() => {
                this.loading = false;
              });
          }
        }
      });
    },
  },
  watch: {
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getPayments();
      }
      this.getPayments();
    },
    options: {
      handler() {
        this.getPayments();
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
