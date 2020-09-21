<template>
  <div>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <v-col>
        <v-row class="ml-14" no-gutters>
          <v-btn x-small icon @click="linkToHome">
            <v-icon color="white">home</v-icon>
          </v-btn>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span @click="linkToOrders" class="activeBreadcrumb">orders</span>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>

          <span class="inactiveBreadcrumb">Confirmation</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Confirmation</h3>
        </v-row>
      </v-col>
    </div>
    <v-container>
      <v-row no-gutters align="center" justify="center">
        <h2>
          <strong>Thank You!</strong>
        </h2>
      </v-row>
      <v-row no-gutters align="center" justify="center">
        <h2>
          <span class="medium-text">Your order has been placed.</span>
        </h2>
      </v-row>
      <v-row no-gutters align="center" justify="center">
        <v-btn
          class="ml-auto hovered-button"
          color="orange white--text"
          @click="showExams"
        >See your Orders</v-btn>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToOrders() {
      this.$emit("linkToOrders");
    },
    showExams() {
      this.$emit("showExams");
    },
  },
};
</script>

<style scoped>
.inactiveBreadcrumb {
  color: grey;
}
.activeBreadcrumb {
  color: whitesmoke;
  cursor: pointer;
}
.activeBreadcrumb:hover {
  text-decoration: underline;
}
.medium-text {
  font-size: 36px;
}
.hero-section {
  background: #ababab;
  height: 20vh;
  align-items: center;
  margin-bottom: 20px;
  margin-top: -20px;
}
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>