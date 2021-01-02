<template>
  <v-row>
    <v-col cols="12" lg="12">
      <v-window v-model="serviceStep">
        <v-window-item :value="1">
          <v-data-table
            dense
            show-select
            v-model="selectedservices"
            :search="search"
            item-key="serial_no"
            :headers="headers"
            :items="services"
            :sort-by="serviceSortRowsBy"
            class="mr-2"
            :loading="loading"
            :options.sync="options"
            :items-per-page="totalrowsPerPageservices"
            :server-items-length="totalservices"
          >
            <template v-slot:top>
              <v-row flat align="baseline" justify="space-around">
                <v-col cols="12" md="3" sm="6">
                  <v-text-field
                    v-model="search"
                    autofocus
                    append-icon="mdi-database-search"
                    label="Quick Search/service"
                    clearable
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" sm="6">
                  <v-btn small @click="Makeservice()" dark color="red darken-4">
                    <v-icon>mdi-plus</v-icon>
                    Make service
                  </v-btn>
                  <v-btn small dark color="grey-blue darken-4">
                    <v-icon>mdi-download-multiple</v-icon>
                    Export Excel
                  </v-btn>
                </v-col>
              </v-row>
            </template>

            <template v-slot:item.amount_paid="{ item }">
              <p>{{ item.amount_paid | currency }}</p>
            </template>
            <template v-slot:item.served="{ item }">
              <p>{{ item.client_details | clt_details }}</p>
            </template>
            <template v-slot:item.balance_due="{ item }">
              <p>{{ item.balance_due | currency }}</p>
            </template>
            <template v-slot:item.served_by="{ item }">
              <p>{{ item.served_by }}</p>
            </template>
            <template v-slot:item.created_at="{ item }">
              <p>{{ item.created_at | moment }}</p>
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
                            <alpha-editor/>
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
                        <table class="table table-sm" v-if="selectedservice !== null">
                            <tbody>
                                <tr>
                                    <th>Served By</th>
                                    <td colspan="3">{{selectedservice.served_by}}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td colspan="3">{{selectedservice.created_at | moment}}</td>
                                </tr>

                            </tbody>
                            <tbody v-if="selectedservice.customer_id !== null">
                                <tr>
                                    <th colspan="4">Customer</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td colspan="3">{{selectedservice.customer.name}}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td colspan="3">{{selectedservice.customer.contact}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3">{{selectedservice.customer.address}}</td>
                                </tr>
                            </tbody>
                            <tbody v-if="selectedservice.customer_id === null">
                                <tr>
                                    <th th colspan="4">Customer</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td colspan="3">{{selectedservice.client_details.name}}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td colspan="3">{{selectedservice.client_details.contact}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3">{{selectedservice.client_details.address}}</td>
                                </tr>
                            </tbody>
                            <tbody v-if="selectedservice.items !== null">
                                    <tr>
                                        <th colspan="4">Items</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                    <tr v-for="(it,i) in JSON.parse(selectedservice.items)" :key="`${i}-itemP`">
                                        <td>{{it.name}}</td>
                                        <td>{{it.qty }}{{it.units || null }}</td>
                                        <td>{{it.rate | currency}}</td>
                                        <td>{{it.amount | currency}}</td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>Amount Agreed</th>
                                        <td colspan="3">{{selectedservice.amount_agreed | currency}}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount Paid</th>
                                        <td colspan="3">{{selectedservice.amount_paid | currency}}</td>
                                    </tr>
                                    <tr>
                                        <th>Balance Due</th>
                                        <td colspan="3">{{selectedservice.balance_due | currency}}</td>
                                    </tr>
                                    <tr>
                                        <th>Previous Balance</th>
                                        <td colspan="3">{{selectedservice.prev_balance | currency}}</td>
                                    </tr>
                                </tbody>
                                 <tbody>
                                    <tr>
                                    <th>Description</th>
                                    <td colspan="3"><p>{{selectedservice.comment}} </p></td>
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
import AlphaEditor from './AlphaEditor.vue';
import moment from 'moment-timezone';

export default {
  components: { AlphaEditor },
  name: "ServicesTable",
  data: (vm) => {
    return {
      search: "",
      loading: false,
      serviceStep: 1,
      selectedservices: [],
      selectedservice: null,
      options: {},
      headers: [
        { text: "S/N", align: "left", value: "serial_no" },
        { text: "Served", align: "left", value: "served" },
        { text: "Reciever", align: "left", value: "served_by", sortable: false },
        { text: "Type", align: "left", value: "service_type" },
        { text: "Paid", align: "left", value: "amount_paid" },
        { text: "Balance", align: "left", value: "balance_due" },
        { text: "Date", align: "left", value: "created_at" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getservices();
  },
  computed: {
    ...mapState({
        openWindow:(state)=>state.servicesModule.openWindow,
        services: (state) => state.servicesModule.services,
        totalrowsPerPageservices: (state) =>
            state.servicesModule.servicePagination.rowsPerPage,
        totalservices: (state) => state.servicesModule.totalservices,
        serviceSortRowsBy: (state) => state.servicesModule.serviceSortRowsBy,
    }),
  },
  methods: {
      ...mapMutations({
      GET_SELECTED_SERVICE: "servicesModule/GET_SELECTED_SERVICE",
      GET_OPEN_WINDOW:"servicesModule/GET_OPEN_WINDOW",
    }),
    viewItem(item){
        this.serviceStep = 3;
        this.selectedservice = item;
    },
    async getservices() {
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
            .dispatch("servicesModule/GET_SERVICES_ACTION", pagination)
            .finally(() => {
              this.loading = false;
            });
        } else if (search.length > 0) {
          if (pageNew > 1) {
            pageNew = this.services.length === 0 ? 1 : pageNew;
            this.loading = true;
            pagination = {
              val: search,
              page: pageNew,
              sortRowsBy: sortBy[0],
              rowsPerPage: itemsPerPage,
              sortDesc: sortDesc[0],
            };
            this.$store
              .dispatch("servicesModule/GET_SERVICES_ACTION", pagination)
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
              .dispatch("servicesModule/GET_SERVICES_ACTION", pagination)
              .finally(() => {
                this.loading = false;
              });
          }
        }
      });
    },
    Makeservice() {
        this.serviceStep = 2;
    },
    tableView(){
        this.serviceStep = 1;
    }
  },
  watch: {
      openWindow(val){
          this.getservices();
          if(val === 1){
              this.serviceStep = val
          }

      },
      serviceStep(val){
          this.getservices();
          this.GET_OPEN_WINDOW({openWindow:val});
      },
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getservices();
      }
      this.getservices();
    },
    options: {
      handler() {
        this.getservices();
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
    clt_details(value){

        return `${value.name}-${value.contact}`

    },
    moment(value){
        return moment(value).tz('Africa/Kampala').format('ddd, Do/MMM/YYYY hh:mm:ss A');
    }
  },
};
</script>

<style></style>
