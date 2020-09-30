<template>
  <v-app style="height: 100px">
    <v-card-text class="teal white--text elevation-6">
      <h3>Dashboard</h3>
      <p>
        Welcome
        <span v-if="user !== null" class="font-italic">{{
          `${user.name ? user.name : ""}`.toUpperCase()
        }}</span>
        <span v-if="user == null" class="font-italic">username</span>.
      </p>
    </v-card-text>
  </v-app>
</template>

<script>
import Axios from "axios";
export default {
  name: "dashboard",
  data: function () {
    return {
      user: null,
    };
  },
  methods: {},
  async created() {
    this.$store
      .dispatch("GET_USER_TOKEN_ACTION", {})
      .then((response) => {})
      .catch((err) => {
        console.error(err);
      });
    this.$store
      .dispatch("GET_USER_ACTION", {})
      .then((response) => {
        this.user = this.$store.state.user;
      })
      .catch((err) => {
        console.log(err);
      });
  },
};
</script>

<style lang="css" scoped>
</style>
