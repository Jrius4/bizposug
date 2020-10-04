<template>
  <div v-if="warningZone">
    <h1>Are you still with us!</h1>
    <v-app style="height: 100px">
      <v-container>
        <v-row justify="center">
          <v-dialog v-model="dialog" persistent max-width="320">
            <v-card>
              <v-card-subtitle class="headline red--text text--darken-3"
                >Session out warning!</v-card-subtitle
              >
              <v-divider class="red darker-3"></v-divider>
              <v-card-text>
                <h4>
                  Are you still with <strong class="text-primary">Us!</strong>
                </h4>
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-row>
      </v-container>
    </v-app>
  </div>
</template>

<script>
export default {
  name: "AutoLogout",
  data: () => {
    return {
      events: ["click", "mousemove", "mousedown", "scroll", "keypress", "load"],
      warningTimer: null,
      logoutTimer: null,
      warningZone: false,
      dialog: false,
    };
  },
  mounted() {
    this.events.forEach((event) => {
      window.addEventListener(event, this.resetTimer);
    });
    this.setTimers();
  },
  destroyed() {
    this.events.forEach((event) => {
      window.removeEventListener(event, this.resetTimer);
    });
    this.resetTimer();
  },
  computed: {},
  methods: {
    setTimers() {
      this.warningTimer = setTimeout(this.warngingMessage, 14 * 60 * 1000); //14 minutes = 14*60*1000 milliseconds
      this.logoutTimer = setTimeout(this.logoutUser, 15 * 60 * 1000); //15 minutes = 15*60*1000 milliseconds
      this.warningZone = false;
      this.dialog = false;
    },
    warngingMessage() {
      this.warningZone = true;
      this.dialog = true;
    },
    logoutUser() {
      document.getElementById("logout-form").submit();
    },
    resetTimer() {
      clearTimeout(this.warningTimer);
      clearTimeout(this.logoutTimer);
      this.setTimers();
    },
  },
  watch: {},
};
</script>

<style>
</style>
