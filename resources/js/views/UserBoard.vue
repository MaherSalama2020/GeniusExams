<template>
  <div>
    <!-- <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <h2 class="title">All your Exams</h2>
    </div>-->
    <!-- {{remainingDays}} -->
    <!-- {{orders}} -->
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <div class="row mx-15 justify-center" v-if="orders.length>0">
      <v-hover
        v-slot:default="{ hover }"
        v-for="(order, index) in orders"
        :key="index"
        class="mx-2 my-2 col-md-4"
      >
        <v-card color="grey lighten-4" max-width="400">
          <v-img :aspect-ratio="16/9" :src="order.certificate.image" :alt="order.certificate.name">
            <v-expand-transition>
              <div
                v-if="hover"
                class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                style="height: 100%;"
              >$. {{order.certificate.price}}</div>
            </v-expand-transition>
          </v-img>
          <v-card-text class="pt-6" style="position: relative;">
            <v-btn
              absolute
              color="orange"
              class="white--text"
              fab
              large
              right
              top
              @click="startSession(order.certificate_id)"
              v-if="remainingDays[index]>0"
            >
              <v-icon class="arrow">double_arrow</v-icon>
            </v-btn>
            <!-- <v-btn
              absolute
              color="orange"
              class="white--text"
              fab
              large
              right
              top
              @click="showSingleCertificate(order.certificate_id)"
              v-else
            >
              <v-icon>mdi-cart</v-icon>
            </v-btn>-->
            <h3 class="font-weight-light orange--text mb-2">{{order.certificate.name}}</h3>
            <v-row align="center" class="mx-0">
              <v-rating :value="4.5" color="amber" dense half-increments readonly size="14"></v-rating>
              <div class="grey--text ml-4">4.5 (413)</div>
            </v-row>
            <div class="font-weight-light grey--text mb-2">{{order.certificate.description}}</div>
          </v-card-text>
          <v-card-subtitle>
            <span v-if="remainingDays[index]>1">
              Remainig Days: {{remainingDays[index]}} Days
              <br />
              <v-icon>mdi-calendar</v-icon>
              {{moment(order.created_at).format('YYYY-MM-DD')}}
            </span>
            <span v-if="remainingDays[index]==1">
              Remainig Days: {{remainingDays[index]}} Day
              <br />
              <v-icon>mdi-calendar</v-icon>
              {{moment(order.created_at).format('YYYY-MM-DD')}}
            </span>
            <span class="error--text" v-if="remainingDays[index]<=0">
              Your subscription ended since:
              <br />
              <v-icon color="error">mdi-calendar</v-icon>
              {{moment(order.created_at).add(order.certificate.available_for,'days').format('YYYY-MM-DD')}}
            </span>
          </v-card-subtitle>
        </v-card>
      </v-hover>
    </div>
    <div class="row mx-15 justify-center" v-if="orders.length===0 && !spinner">
      <v-card class="mx-2 my-2 col-md-6">
        <v-card-title class="justify-center">No booked certificates</v-card-title>
      </v-card>
    </div>
  </div>
</template>



<script>
export default {
  data() {
    return {
      loading: false,
      user: null,
      orders: [],
      remainingDays: [],
      spinner: false,
    };
  },
  beforeMount() {
    this.user = JSON.parse(localStorage.getItem("genius.user"));

    axios.defaults.headers.common["Content-Type"] = "application/json";
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.getItem("genius.jwt");
    this.spinner = true;
    axios
      .get(`api/users/${this.user.id}/orders`)
      .then((response) => {
        this.orders = response.data;
        this.remainingDays = this.orders.map((order) => {
          let orderDate = moment(new Date(order.created_at)).format(
            "YYYY-MM-DD"
          );
          let endDate = moment(new Date(orderDate))
            .add(order.certificate.available_for, "days")
            .format("YYYY-MM-DD");
          let nowDate = moment(new Date()).format("YYYY-MM-DD");
          // alert(orderDate);
          // alert(endDate);
          // alert(nowDate);
          let diff = moment(new Date(endDate)).diff(nowDate, "days") + 1;
          // alert(diff);
          if (diff <= 0 && !order.is_finished) {
            let id = order.id;
            axios
              .put(`api/orders/${id}/finish`)
              .then((response) => {
                let index = this.orders.indexOf(order);
                this.orders[index].is_finished = 1;
                // this.orders.splice(index, 1);
                this.spinner = false;
                // console.log(response);
              })
              .catch((error) => {
                this.spinner = false;
                console.log(error);
              });
          }
          return diff;
        });
        // this.remainingDays = this.remainingDays.filter((remainingday) => {
        //   if (remainingday != null) return remainingday;
        // });
        // this.orders = this.orders.filter((order) => !order.is_finished);
        this.spinner = false;
        // console.log(this.orders);
      })
      .catch((error) => {
        this.spinner = fasle;
      });
  },
  methods: {
    moment: function (date) {
      return moment(date);
    },
    startSession(certificate_id) {
      this.loading = true;
      setTimeout(() => (this.loading = false), 2000);
      this.$emit("StartSession", certificate_id);
    },
    showSingleCertificate(id) {
      this.$emit("showSingleCertificate", id);
    },
  },
};
</script>
<style scoped>
.small-text {
  font-size: 14px;
}
.certificate-box {
  border: 1px solid #cccccc;
  padding: 10px 15px;
}
.hero-section {
  background: #ababab;
  height: 20vh;
  align-items: center;
  margin-bottom: 20px;
  margin-top: -20px;
}
.title {
  font-size: 60px;
  color: #ffffff;
}
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
.arrow:hover {
  animation: moveRightToLeft -2s 1s infinite linear;
}
@keyframes moveRightToLeft {
  0% {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(8px, 0);
  }
  50.1% {
    transform: translate(-4px, 0);
  }
  100% {
    transform: translate(8px, 0);
  }
}
</style>