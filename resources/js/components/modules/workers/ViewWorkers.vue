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
              title="Workers"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-4"
            >
              <v-window v-model="workerStep">
                <v-window-item :value="1">
                  <v-data-table
                    dense
                    show-select
                    v-model="selectedworkers"
                    :search="search"
                    item-key="barcode"
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
                    <template v-slot:item.salary="{ item }">
                      <p>{{ item.salary | currency }}</p>
                    </template>

                    <template v-slot:item.balance="{ item }">
                      <p>{{ item.balance | currency }}</p>
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
                      <template v-if="selectedworker !== null">
                        <editor-worker />
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
import editorWorker from "./editorWorker";
export default {
  name: "ViewWorkers",
  components: { editorWorker },
  data: () => {
    return {
      selectedworkers: [],
      selectedworker: null,
      workerStep: 1,
      loading: false,
      search: "",
      options: {},
      headers: [

        { text: "NAME", align: "left", value: "name" },
        { text: "Contact", align: "left", value: "contact" },
        { text: "Address", align: "left", value: "address" },
        { text: "Balance", align: "left", value: "balance" },
        { text: "Salary", align: "left", value: "salary" },
        { text: "Action", align: "left", value: "action", sortable: false },
      ],
    };
  },
  mounted() {
    this.getworkers();
  },
  methods: {
      ...mapActions({
          GET_WORKER_ACTION: "workersModule/GET_WORKER_ACTION",
      }),
    ...mapMutations({
      GET_SELECTED_WORKER: "workersModule/GET_SELECTED_WORKER",
      GET_OPEN_WINDOW:"workersModule/GET_OPEN_WINDOW",
    }),
    editItem(item) {
      if(item!==null) {
        this.GET_WORKER_ACTION({id:item.id});
        this.workerStep = 2;
        this.selectedworker = "update";
        this.GET_SELECTED_WORKER({ action_done: this.selectedworker });
    }
    else{
        this.workerStep = 2;
        this.selectedworker = "create";
        this.GET_SELECTED_WORKER({ action_done: this.selectedworker });
    }

    },
    tableView() {
      this.workerStep = 1;
    },
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
            pageNew = this.workers.length === 0 ? 1 : pageNew;
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
  },
  computed: {
    ...mapState({
        openWindow: state => state.workersModule.openWindow,
        worker: state => state.workersModule.worker,
        message: state => state.workersModule.message,
      workers: (state) => state.workersModule.workers,
      totalrowsPerPageworkers: (state) =>
        state.workersModule.workerPagination.rowsPerPage,
      totalworkers: (state) => state.workersModule.totalworkers,
      workerSortRowsBy: (state) => state.workersModule.workerSortRowsBy,
    }),
  },
  watch: {
      workerStep(val){
          this.getworkers();
          this.GET_OPEN_WINDOW({openWindow:val});

      },
      openWindow(val){
          if(val === 1)this.workerStep = 1;
      },
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
