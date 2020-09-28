<template>
  <v-main>
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
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
          <span class="activeBreadcrumb" @click="linkToCart"
            >Shopping Cart</span
          >
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">Checkout</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Shopping cart</h3>
        </v-row>
      </v-col>
    </div>
    <v-container>
      <v-row no-gutters>
        <v-col v-if="!isLoggedIn" class="col-md-7">
          <h4>You need to login or register to continue</h4>
          <v-btn color="success" @click="enrollLogin">Login</v-btn>
          <v-btn color="info" @click="enrollRegister">Register</v-btn>
        </v-col>
        <v-col v-if="isLoggedIn" class="col-md-7">
          <v-row no-gutters>
            <h4 class="font-weight-bold">Checkout</h4>
          </v-row>
          <v-row
            justify="start"
            align="start"
            no-gutters
            class="w-50"
            ref="paypal"
            v-if="subtotal == -1 || subtotal > 0"
          ></v-row>
          <v-row
            justify="start"
            align="start"
            no-gutters
            class="w-50"
            v-if="subtotal == 0"
          >
            <v-btn
              color="orange white--text"
              @click="placeFreeShoppingCart"
              class="hovered-button"
              >Place Free Order</v-btn
            >
          </v-row>
          <!-- <div v-show="showpaypal === true & loadding === true" class="container">
          <div class="row text-center">
            <div class="w-100 text-center">
              <h5 class="text-center">Checking your payment please wait!</h5>
            </div>
          </div>
          </div>-->
          <!-- Shopping Cart -->
          <v-row no-gutters v-if="shoppingcart && numInCart > 0" class="mt-6">
            <v-col class="col-md-12">
              <v-row no-gutters>
                <h4 class="font-weight-bold">Order Details</h4>
              </v-row>
              <v-row no-gutters>
                <v-col class="col-md-12">
                  <v-card
                    v-for="item in shoppingcart"
                    :key="item.id + '-forsaleInCart'"
                    class="mb-0"
                  >
                    <v-card-text>
                      <v-row no-gutters>
                        <v-col class="col-md-1">
                          <v-img :src="item.image" contain />
                        </v-col>
                        <v-col class="col-md-8">
                          <v-row no-gutters class="ml-2">
                            <h5 class="font-weight-bold">{{ item.name }}</h5>
                          </v-row>
                          <v-row no-gutters class="ml-2">{{
                            item.description
                          }}</v-row>
                        </v-col>
                        <v-col class="col-md-2">
                          <v-row justify="center" align="start" no-gutters>
                            <h5>{{ item.price | dollars }}</h5>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
          <!-- end Shopping Cart -->
        </v-col>
        <v-col v-if="isLoggedIn" class="col-md-5">
          <v-card class="ml-6 pa-6">
            <h4 class="font-weight-bold">Summary</h4>
            <v-card tile flat class="d-flex">
              <v-card flat tile>Original Price:</v-card>
              <v-card flat tile class="ml-auto">{{ total | dollars }}</v-card>
            </v-card>
            <v-divider class="mt-w mb-2" />
            <v-card tile flat class="d-flex">
              <v-card tile flat v-if="subtotal == -1" class="font-weight-bold"
                >Total:</v-card
              >
              <v-card
                tile
                flat
                v-if="subtotal == -1"
                class="font-weight-bold ml-auto"
                >{{ total | dollars }}</v-card
              >
            </v-card>
            <v-card flat tile class="d-flex">
              <v-card flat tile v-if="subtotal >= 0" class="font-weight-bold"
                >Total:</v-card
              >
              <v-card
                flat
                tile
                v-if="subtotal >= 0"
                class="font-weight-bold ml-auto"
                >{{ subtotal | dollars }}</v-card
              >
            </v-card>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
import "../plugins/vuetify-datetime.js";

export default {
  props: ["certificates"],
  filters: {
    dollars: (num) => `$${num ? num.toFixed(2) : num}`,
  },
  data() {
    return {
      loading: false,
      isLoggedIn: null,
      certificate: [],
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
    /* snip */
    shoppingcart() {
      return this.$store.getters.inCart.map((cartItem) => {
        return this.certificates.find((certificate) => {
          return cartItem === certificate.id;
        });
      });
    },
  },
  components: {},
  mounted() {
    this.isLoggedIn = localStorage.getItem("genius.jwt") != null;
    if (this.isLoggedIn) {
      this.spinner = true;
      let certificate_id = this.$route.params.id;
      const script = document.createElement("script");
      script.src =
        "https://www.paypal.com/sdk/js?client-id=Ad0ww_ziwx0wIW6yU2hEsa-FLNggul873VNEDAcAbag-sIijYtWBby66tEINVwXn-zOFS45m0nSzKBAz";
      script.addEventListener("load", this.setLoaded);
      document.body.appendChild(script);
    }
  },

  beforeMount() {
    if (localStorage.getItem("genius.jwt") != null) {
      this.user = JSON.parse(localStorage.getItem("genius.user"));
      axios.defaults.headers.common["Content-Type"] = "application/json";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + localStorage.getItem("genius.jwt");
    }
  },
  computed: {
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
    inSavedlist() {
      return this.$store.getters.inSavedlist;
    },
    numInSavedlist() {
      return this.inSavedlist.length;
    },
    inWhishlist() {
      return this.$store.getters.inWhishlist;
    },
    numInWhishlist() {
      return this.inWhishlist.length;
    },
    total() {
      return this.$store.getters.total;
    },
    subtotal() {
      return this.$store.getters.subtotal;
    },
    /* snip */
    shoppingcart() {
      return this.$store.getters.inCart.map((cartItem) => {
        return this.certificates.find((certificate) => {
          return cartItem === certificate.id;
        });
      });
    },
    /* snip */
    Whishlist() {
      return this.$store.getters.inWhishlist.map((item) => {
        return this.certificates.find((certificate) => {
          return item === certificate.id;
        });
      });
    },
    /* snip */
    Savedlist() {
      return this.$store.getters.inSavedlist.map((item) => {
        return this.certificates.find((certificate) => {
          return item === certificate.id;
        });
      });
    },
  },
  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToCart() {
      this.$emit("linkToCart");
    },
    setLoaded: function (resp) {
      // let description = this.certificate.description;
      let price =
        this.subtotal === -1 ? this.total.toFixed(2) : this.subtotal.toFixed(2);

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
            this.placeShoppingCart();
          },
          onError: (err) => {
            // console.log(err);
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
    placeShoppingCart() {
      this.$router.push("/").catch((err) => {});
      let inCart = JSON.stringify(this.inCart);
      let total = this.total.toFixed(2);
      let subtotal = this.subtotal.toFixed(2);
      // alert(inCart);
      axios
        .post("/api/orders/placeshoppingcart", { inCart, total, subtotal })
        .then((response) => {
          this.resetState();
          this.spinner = false;
          this.$emit("showConfirmation");
        })
        .catch((err) => {
          this.spinner = false;
        });
    },
    placeFreeShoppingCart() {
      this.spinner = true;
      this.$router.push("/").catch((err) => {});
      let inCart = JSON.stringify(this.inCart);
      let total = this.total.toFixed(2);
      let subtotal = this.subtotal.toFixed(2);
      // alert(inCart);
      axios
        .post("/api/orders/placeshoppingcart", { inCart, total, subtotal })
        .then((response) => {
          this.resetState();
          this.spinner = false;
          this.$emit("showConfirmation");
        })
        .catch((err) => {
          this.spinner = false;
        });
    },
    resetState() {
      this.$store.dispatch("resetState");
    },

    removeFromProgress(certificate_id) {
      this.$store.dispatch("removeFromProgress", certificate_id);
    },
  },
};
</script>
<style scoped>
.orderSummary {
  position: fixed;
  top: 30%;
  right: 10%;
}
.mouse-over {
  cursor: pointer;
}
.mouse-over:hover {
  text-decoration: underline;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>

