<template>
  <v-row>
    <v-col cols="12" lg="12">
      <v-window v-model="workerStep">
        <v-window-item :value="1">
          <v-data-table
            dense
            show-select
            v-model="selectedworkers"
            :search="search"
            item-key="id"
            :headers="headers"
            :items="workers"
            :sort-by="workerSortRowsBy"
            class="mr-2"
            :loading="loading"
            :options.sync="options"
            :items-per-page="totalrowsPerPageworkers"
            :server-items-length="totalworkers"
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
                  <v-btn small dark color="teal darken-4" @click="makeItem()">
                    <v-icon>mdi-plus</v-icon>
                    Add Worker
                  </v-btn>
                  <v-btn small dark color="grey-blue darken-4">
                    <v-icon>mdi-download-multiple</v-icon>
                    Export Excel
                  </v-btn>
                </v-col>
              </v-row>
            </template>

            <template v-slot:item.salary="{ item }">
              <p>{{ item.salary | currency }}</p>
            </template>
            <template v-slot:item.balance="{ item }">
              <p>{{ item.balance | currency }}</p>
            </template>

            <template v-slot:item.action="{ item }">
              <v-btn icon color="teal" small>
                <v-icon small> mdi-eye </v-icon>
              </v-btn>
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
  name: "Workers",
  data: (vm) => {
    return {
      search: "",
      loading: false,
      workerStep: 1,
      selectedworkers: [],
      selectedworker: null,
      options: {},
      headers: [
        { text: "Name", align: "left", value: "name" },
        { text: "Contact", align: "left", value: "contact", sortable: false },
        { text: "Job", align: "left", value: "role" },
        { text: "Salary", align: "left", value: "paid" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "DOB", align: "left", value: "d_o_b" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getworkers();
  },
  computed: {
    ...mapState({
      workers: (state) => state.workersModule.workers,
      totalrowsPerPageworkers: (state) =>
        state.workersModule.workerPagination.rowsPerPage,
      totalworkers: (state) => state.workersModule.totalworkers,
      workerSortRowsBy: (state) => state.workersModule.workerSortRowsBy,
    }),
  },
  methods: {
    async getworkers() {
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
            .dispatch("workersModule/GET_WORKERS_ACTION", pagination)
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
              .dispatch("workersModule/GET_WORKERS_ACTION", pagination)
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
              .dispatch("workersModule/GET_WORKERS_ACTION", pagination)
              .finally(() => {
                this.loading = false;
              });
          }
        }
      });
    },
    makeItem() {},
  },
  watch: {
    search(value) {
      if (!this.search) {
        this.search = "";
      }
      if (this.search !== "") {
        this.getworkers();
      }
      this.getworkers();
    },
    options: {
      handler() {
        this.getworkers();
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
