<template>
  <v-main>
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
    <!-- <div class="mr-10 ml-10 mb-0" v-if="orders.length>0">
      <span class="headline font-weight-bold">What to train next</span>
      <br />
      <span class="subtitle font-weight-bold">Your purchased certificates</span>
    </div>-->
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
            home
          </v-tooltip>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">Orders</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Orders</h3>
        </v-row>
      </v-col>
    </div>
    <div v-if="orders.length>0">
      <vueper-slides
        ref="myVueperSlides"
        class="ml-10 mr-10 no-shadow thumbnails py-0"
        :autoplay="false"
        :visible-slides="5"
        slide-multiple
        :gap="1"
        :bullets="false"
        :arrows="true"
        :touchable="false"
        :breakpoints="breakpoints"
      >
        <vueper-slide v-for="(order, index) in orders" :key="index">
          <template v-slot:content>
            <v-hover v-slot:default="{ hover }" class="mx-2 my-2 col-md-4">
              <v-card color="grey lighten-4" max-width="400">
                <v-img
                  :aspect-ratio="16/9"
                  :src="order.certificate.image"
                  :alt="order.certificate.name"
                >
                  <v-expand-transition>
                    <div
                      v-if="hover"
                      class="font-weight-bold d-flex transition-fast-in-fast-out grey darken-2 v-card--reveal display-3 white--text"
                      style="height: 100%;"
                    >{{order.certificate.price | dollars}}</div>
                  </v-expand-transition>
                </v-img>
                <v-card-text class="pt-6" style="position: relative;">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        absolute
                        color="black lighten-1"
                        class="white--text"
                        fab
                        small
                        right
                        top
                        @click="startSession(order.certificate_id)"
                        v-if="remainingDays[index]>0"
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon class="arrow">double_arrow</v-icon>
                      </v-btn>
                    </template>
                    Start Training
                  </v-tooltip>
                  <v-btn
                    absolute
                    color="black lighten-1"
                    class="white--text"
                    fab
                    small
                    right
                    top
                    @click="addToCart(order.certificate_id)"
                    v-if="remainingDays[index]<=0 && (inCart.find((item)=> item== order.certificate_id)>-1?false:true)"
                  >
                    <v-icon>mdi-cart-plus</v-icon>
                  </v-btn>
                  <v-btn
                    absolute
                    color="black lighten-1"
                    class="white--text"
                    fab
                    small
                    right
                    top
                    @click="removeFromCart(order.certificate_id)"
                    v-if="remainingDays[index]<=0 && (inCart.find((item)=> item== order.certificate_id)>-1?true:false)"
                  >
                    <v-icon>mdi-cart-minus</v-icon>
                  </v-btn>
                  <h3
                    class="textover font-weight-light orange--text mb-2 mouse-over"
                    @click="showSingleCertificate(order.certificate.id)"
                  >{{order.certificate.name}}</h3>
                  <v-row align="center" class="mx-0" no-gutters>
                    <span style="color:#FF9800;">{{order.certificate.rate}}</span>
                    <v-rating
                      :value="order.certificate.rate"
                      color="#FF9800"
                      background-color="grey darken-1"
                      empty-icon="$ratingFull"
                      dense
                      half-increments
                      readonly
                      size="14"
                    ></v-rating>

                    <div class="grey--text ml-auto">
                      <v-icon small>book</v-icon>
                      {{order.certificate.bookedNO}}
                    </div>
                    <div class="grey--text ml-1">
                      <v-icon small>mdi-star-circle</v-icon>
                      {{order.certificate.ratedNO}}
                    </div>
                  </v-row>
                  <div
                    class="font-weight-light grey--text mb-2 textover"
                  >{{order.certificate.description}}</div>
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
          </template>
        </vueper-slide>
      </vueper-slides>
    </div>
    <div class="row mx-15 justify-center" v-if="orders.length===0 && !spinner">
      <v-card class="mx-2 my-2 col-md-6">
        <v-card-title class="justify-center">No booked certificates</v-card-title>
      </v-card>
    </div>
  </v-main>
</template>



<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
export default {
  filters: {
    dollars: (num) => `$${num ? num.toFixed(2) : num}`,
  },
  data() {
    return {
      loading: false,
      user: null,
      orders: [],
      remainingDays: [],
      spinner: false,
      breakpoints: {
        1200: {
          slideRatio: 1 / 4,
          arrows: false,
          bullets: true,
          bulletsOutside: true,
        },
        900: {
          slideRatio: 1 / 4,
          arrows: false,
          bullets: true,
          bulletsOutside: true,
        },
        600: {
          slideRatio: 1 / 4,
          arrows: false,
          bullets: true,
          bulletsOutside: true,
        },
        // The order you list breakpoints does not matter, Vueper Slides will sort them for you.
        1100: {
          slideRatio: 1 / 4,
          arrows: false,
          bullets: true,
          bulletsOutside: true,
        },
      },
    };
  },
  components: { VueperSlides, VueperSlide },
  computed: {
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
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
            let certificate_id = order.certificate_id;
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
        this.spinner = false;
      });
  },
  methods: {
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
    },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
    moment: function (date) {
      return moment(date);
    },
    startSession(certificate_id) {
      this.loading = true;
      setTimeout(() => (this.loading = false), 2000);
      this.$emit("StartSession", certificate_id);
    },
    showSingleCertificate(id) {
      this.$emit("showSingleCertificate", id, "orders");
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
.mouse-over {
  cursor: pointer;
}
.mouse-over:hover {
  text-decoration: underline;
}
.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* number of lines to show */
  -webkit-box-orient: vertical;
}
</style>