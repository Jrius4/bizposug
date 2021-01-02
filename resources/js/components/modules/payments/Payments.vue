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
                <v-col cols="12" md="3" sm="6">
                  <v-text-field
                    v-model="search"
                    autofocus
                    append-icon="mdi-database-search"
                    label="Quick Search/Payment"
                    clearable
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" sm="6">
                  <v-btn small @click="MakePayment()" dark color="red darken-4">
                    <v-icon>mdi-plus</v-icon>
                    Make Payment
                  </v-btn>
                  <v-btn small dark color="grey-blue darken-4">
                    <v-icon>mdi-download-multiple</v-icon>
                    Export Excel
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
            <template v-slot:item.received_by="{ item }">
              <p>{{ item.received_by }}</p>
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn icon color="teal" @click="viewItem(item)" small>
                <v-icon small> mdi-eye </v-icon>
              </v-btn>
            </template>
          </v-data-table>
        </v-window-item>
        <v-window-item :value="2">
            <template>
                <div>
                    <v-row align="baseline" justify="center">
                        <v-col cols="12" md="8">
                            <v-btn
                          text
                          large
                          @click="tableView"
                          color="teal darken-3"
                          dark
                        >
                          <v-icon>mdi-table-arrow-left</v-icon> Back
                        </v-btn>
                        </v-col>
                    </v-row>
                    <v-row align="baseline" justify="center">
                        <v-col cols="12" md="12">
                            <editor-payments/>
                        </v-col>

                    </v-row>
                </div>

            </template>
        </v-window-item>
        <v-window-item :value="3">
            <div>
                <v-row align="baseline" justify="center">
                        <v-col cols="12" md="8">
                            <v-btn
                          text
                          large
                          @click="tableView"
                          color="teal darken-3"
                          dark
                        >
                          <v-icon>mdi-table-arrow-left</v-icon> Back
                        </v-btn>
                        </v-col>
                </v-row>
                <v-row align="baseline" justify="center">
                    <v-col cols="12" md="8">
                        <table class="table table-sm" v-if="selectedPayment !== null">
                            <tbody>
                                <tr>
                                    <th>Recieved By</th>
                                    <td colspan="3">{{selectedPayment.received_by}}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td colspan="3">{{selectedPayment.created_at}}</td>
                                </tr>

                            </tbody>
                            <tbody v-if="selectedPayment.items !== null">
                                    <tr>
                                        <th colspan="4">Items</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                    <tr v-for="(it,i) in JSON.parse(selectedPayment.items)" :key="`${i}-itemP`">
                                        <td>{{it.name}}</td>
                                        <td>{{it.qty }}{{it.units || null }}</td>
                                        <td>{{it.rate}}</td>
                                        <td>{{it.amount}}</td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                    <th>Paid</th>
                                    <td colspan="3">{{selectedPayment.paid | currency}}</td>
                                </tr>
                                </tbody>
                                 <tbody>
                                    <tr>
                                    <th>Description</th>
                                    <td colspan="3"><p>{{selectedPayment.description}} </p></td>
                                </tr>
                                </tbody>

                                 <tbody>
                                    <tr>
                                    <th>Balance</th>
                                    <td colspan="3"><p>{{selectedPayment.balance | currency}} </p></td>
                                </tr>
                                </tbody>
                        </table>
                    </v-col>
                </v-row>
            </div>
        </v-window-item>
      </v-window>
    </v-col>
  </v-row>
</template>

<script>
import { mapState,mapMutations } from "vuex";
import editorPayments from "./editorPayments"
export default {
  name: "Payments",
  components:{editorPayments},
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
        { text: "Reciever", align: "left", value: "received_by", sortable: false },
        { text: "Type", align: "left", value: "type_payment" },
        { text: "Paid", align: "left", value: "paid" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "Date", align: "left", value: "created_at" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getPayments();
  },
  computed: {
    ...mapState({
        openWindow:(state)=>state.paymentsModule.openWindow,
        payments: (state) => state.paymentsModule.payments,
        totalrowsPerPagePayments: (state) =>
            state.paymentsModule.paymentPagination.rowsPerPage,
        totalpayments: (state) => state.paymentsModule.totalpayments,
        paymentSortRowsBy: (state) => state.paymentsModule.paymentSortRowsBy,
    }),
  },
  methods: {
      ...mapMutations({
      GET_SELECTED_PAYMENT: "paymentsModule/GET_SELECTED_PAYMENT",
      GET_OPEN_WINDOW:"paymentsModule/GET_OPEN_WINDOW",
    }),
    viewItem(item){
        this.paymentStep = 3;
        this.selectedPayment = item;
    },
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
            pageNew = this.payments.length === 0 ? 1 : pageNew;
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
    MakePayment() {
        this.paymentStep = 2;
    },
    tableView(){
        this.paymentStep = 1;
    }
  },
  watch: {
      openWindow(val){
          this.getPayments();
          if(val === 1){
              this.paymentStep = val
          }

      },
      paymentStep(val){
          this.getPayments();
          this.GET_OPEN_WINDOW({openWindow:val});
      },
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
