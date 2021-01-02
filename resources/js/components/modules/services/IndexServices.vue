<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <v-row align="baseline" justify="center">
          <v-col cols="12" lg="12" md="12">
            <base-v-component
              :heading="`${currentHeading} view`.toUpperCase()"
              class="teal--text text--darken-3"
            />
            <base-material-card
              icon="mdi-account-group-outline"
              :title="`${currentHeading} view`.toUpperCase()"
              class="px-5 py-3 elevation-4"
              color="teal darken-4"
              titleColor="teal--text text--darken-2"
            >

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
import AlphaEditor from "./AlphaEditor";
import BetaEditor from "./BetaEditor";
import ServicesTable from "./ServicesTable";
export default {
  name: "IndexServices",
  components: { AlphaEditor, BetaEditor },
  data: (vm) => {
    return {
      selectedComponent: "ServicesTable",
    };
  },
  computed: {
    currentHeading(){
        switch (this.selectedComponent) {
        case "ServicesTable":
          return 'services table';
        default:
          'services table';
      }
    },
    openComponent() {
      switch (this.selectedComponent) {
        case "ServicesTable":
          return ServicesTable;
        default:
          ServicesTable;
      }
    },
  },
};
</script>

<style>
</style>
