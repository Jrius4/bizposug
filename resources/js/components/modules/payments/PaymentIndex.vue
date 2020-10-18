<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <v-row align="baseline" justify="center">
          <v-col cols="12" lg="12" md="12">
            <base-v-component
              :heading="`${selectedComponent} view`.toUpperCase()"
              class="teal--text text--darken-3"
            />
            <base-material-card
              icon="mdi-account-group-outline"
              :title="`${selectedComponent} view`.toUpperCase()"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-2"
            >
              <v-row align="baseline" justify="center">
                <v-col cols="12" md="12">
                  <v-btn
                    dark
                    color="red darken-3"
                    @click="selectedComponent = 'payments'"
                    >Payments</v-btn
                  >
                  <v-btn
                    dark
                    color="teal darken-3"
                    @click="selectedComponent = 'workers'"
                    >Workers</v-btn
                  >
                  <v-btn
                    dark
                    color="blue-gray darken-3"
                    @click="selectedComponent = 'suppliers'"
                    >Suppliers</v-btn
                  >
                </v-col>
              </v-row>
              <transition
                enter-active-class="animated fadeIn"
                leave-active-class="animated fadeOut"
                mode="out-in"
                key="hello"
              >
                <component v-bind:is="openComponent"></component>
              </transition>
            </base-material-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Payments from "./Payments";
import Workers from "./Workers";
import Suppliers from "./Suppliers";
export default {
  name: "PaymentIndex",
  components: { Payments, Workers },
  data: (vm) => {
    return {
      selectedComponent: "payments",
    };
  },
  computed: {
    openComponent() {
      switch (this.selectedComponent) {
        case "workers":
          return Workers;
        case "payments":
          return Payments;
        case "suppliers":
          return Suppliers;
        default:
          Payments;
      }
    },
  },
};
</script>

<style>
</style>
