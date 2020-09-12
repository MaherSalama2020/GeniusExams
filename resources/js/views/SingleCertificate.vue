<template>
  <div class="container">
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
    <div class="row">
      <v-alert
        v-if="isLoggedIn && canOrder=='no'"
        color="red lighten-4"
        icon="warning"
      >You already have an order for this certificate. See your Requests.</v-alert>
    </div>
    <div class="row">
      <div class="col-md-4">
        <v-hover v-slot:default="{ hover }" class="mx-2 my-2 col-md-4">
          <v-card color="grey lighten-4" max-width="400">
            <v-img :aspect-ratio="16/9" :src="certificate.image" :alt="certificate.name">
              <v-expand-transition>
                <div
                  v-if="hover"
                  class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                  style="height: 100%;"
                >$. {{certificate.price}}</div>
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
                v-if="inCart.find((item)=> item== certificate.id)>-1?false:true"
                @click="addToCart(certificate.id)"
              >
                <v-icon>add_shopping_cart</v-icon>
              </v-btn>
              <v-btn
                absolute
                color="orange"
                class="white--text"
                fab
                large
                right
                top
                v-if="inCart.find((item)=> item== certificate.id)>-1?true:false"
                @click="removeFromCart(certificate.id)"
              >
                <v-icon>remove_shopping_cart</v-icon>
              </v-btn>
              <h3 class="font-weight-light orange--text mb-2">{{certificate.name}}</h3>
              <v-row align="center" class="mx-0">
                <v-rating :value="4.5" color="amber" dense half-increments readonly size="14"></v-rating>
                <div class="grey--text ml-4">4.5 (413)</div>
              </v-row>
              <div class="font-weight-light grey--text mb-2">{{certificate.description}}</div>
            </v-card-text>

            <v-card-subtitle>
              <span v-if="certificate.available_for>1">{{certificate.available_for}} Days</span>
              <span v-if="certificate.available_for==1">{{certificate.available_for}} Day</span>
            </v-card-subtitle>
          </v-card>
        </v-hover>
      </div>
      <div v-if="!isLoggedIn" class="col-md-6">
        <h4>You need to login to continue</h4>
        <v-btn color="success" @click="enrollLogin">Login</v-btn>
        <v-btn color="info" @click="enrollRegister">Register</v-btn>
      </div>
      <div v-if="isLoggedIn && canOrder=='yes'" class="col-md-6">
        <div class="mx-auto w-50" ref="paypal"></div>
        <div v-show="showpaypal === true & loadding === true" class="container">
          <div class="row text-center">
            <div class="w-100 text-center">
              <h5 class="text-center">Checking your payment please wait!</h5>
            </div>
          </div>
        </div>
      </div>
      <!-- <div v-if="isLoggedIn" class="col-md-4">
        <div>
          <vuetify-datetime dense v-model="date" v-bind:label="label" v-bind:options="options" />
          Selected Date: {{ date }}
        </div>
      </div>-->
    </div>
  </div>
</template>

<script>
import "../plugins/vuetify-datetime.js";

export default {
  data() {
    return {
      date: new Date().getTime(),
      label: "Date",
      options: {
        tabDateTitle: "Date",
        tabTimeTitle: "Time",
        format: "YYYY-MM-DD",
        icon: "event",
        iconTime: "av_timer",
        titleBarColor: "cyan",
        backgroundColor: "white",
        closeOnDateClick: false,
        useSeconds: false,
        clearable: false,
        outlined: true,
      },
      loading: false,
      isLoggedIn: null,
      certificate: [],
      canOrder: "yes",
      showpaypal: false,
      loadding: false,
      paidFor: false,
      spinner: false,
    };
  },
  computed: {
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
  },
  components: {},
  mounted() {
    this.isLoggedIn = localStorage.getItem("genius.jwt") != null;
    if (this.isLoggedIn) {
      this.spinner = true;

      let certificate_id = this.$route.params.id;
      axios
        .post("/api/orders/canorder/", { certificate_id })
        .then((response) => {
          if (response.data.canOrder == "yes") {
            this.canOrder = "yes";
            const script = document.createElement("script");
            script.src =
              "https://www.paypal.com/sdk/js?client-id=Ad0ww_ziwx0wIW6yU2hEsa-FLNggul873VNEDAcAbag-sIijYtWBby66tEINVwXn-zOFS45m0nSzKBAz";
            script.addEventListener("load", this.setLoaded);
            document.body.appendChild(script);
          } else {
            this.canOrder = "no";
          }
          // this.spinner = false;
        });
    }
  },

  beforeMount() {
    let url = `/api/certificates/${this.$route.params.id}`;
    axios.get(url).then((response) => {
      this.certificate = response.data;
    });
    if (localStorage.getItem("genius.jwt") != null) {
      this.user = JSON.parse(localStorage.getItem("genius.user"));
      axios.defaults.headers.common["Content-Type"] = "application/json";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + localStorage.getItem("genius.jwt");
    }
  },
  methods: {
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
    },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
    },
    setLoaded: function (resp) {
      let description = this.certificate.description;
      let price = this.certificate.price;

      window.paypal
        .Buttons({
          createOrder: async (data, actions) => {
            return actions.order.create({
              purchase_units: [
                {
                  description: "Genius Exams",
                  amount: {
                    currency_code: "USD",
                    value: price,
                  },
                },
              ],
            });
          },
          onApprove: async (data, actions, resp) => {
            this.loadding = true;
            this.spinner = true;
            const order = await actions.order.capture();
            this.data;
            // this.paidFor = true;
            // this.loadding = false;
            // window.location.href = "./paymentsuccess/" + this.resp;
            this.placeOrder();
          },
          onError: (err) => {
            console.log(err);
            this.spinner = false;
          },
        })
        .render(this.$refs.paypal)
        .then(() => {
          this.spinner = false;
        })
        .catch(() => {
          this.spinner = false;
        });
    },
    enrollLogin() {
      this.$emit("enrollLogin", this.$route.fullPath);
    },
    enrollRegister() {
      this.$emit("enrollRegister", this.$route.fullPath);
    },
    placeOrder() {
      // e.preventDefault();
      // let fdate = new Date(this.date);
      // let hours = fdate.getHours();
      // let minutes = fdate.getMinutes();
      // minutes = minutes < 10 ? "0" + minutes : minutes;
      // let time = hours + ":" + minutes;
      // let month = fdate.getMonth() + 1;
      // let date = fdate.getFullYear() + "-" + month + "-" + fdate.getDate();

      this.$router.push("/").catch((err) => {});
      let certificate_id = this.certificate.id;
      // let date = this.date;

      axios
        .post("/api/orders/", { certificate_id })
        .then((response) => {
          axios
            .post(`api/certificates/bookedno`, { certificate_id })
            .then((response) => {
              this.certificate.bookedNO = this.certificate.bookedNO + 1;
              this.$forceUpdate();
              this.spinner = false;
            })
            .catch((err) => {
              this.spinner = false;
            });
          this.$emit("showConfirmation");
        })
        .catch((err) => {
          this.spinner = false;
        });
    },
  },
};
</script>

<style scoped>
.small-text {
  font-size: 18px;
}
.title {
  font-size: 36px;
}
.order-box {
  border: 1px solid #cccccc;
  padding: 10px 15px;
}
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
</style>