<template>
  <v-main>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <v-col>
        <v-row class="ml-14" no-gutters>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn x-small icon @click="linkToHome" v-bind="attrs" v-on="on">
                <v-icon color="white">home</v-icon>
              </v-btn>
            </template>
            Home
          </v-tooltip>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">About</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">About</h3>
        </v-row>
      </v-col>
    </div>
    <v-row no-gutters justify="start" align="center">
      <div class="big-title ml-10 border-bottom black--text">About</div>
    </v-row>
  </v-main>
</template>
<script>
export default {
  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
  },
};
</script>