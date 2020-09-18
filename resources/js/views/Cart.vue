<template>
  <v-main>
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
          <span class="inactiveBreadcrumb">Shopping Cart</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Shopping cart</h3>
        </v-row>
      </v-col>
    </div>
    <v-container>
      <v-col v-if="!isLoggedIn" class="col-md-7">
        <h4>You need to login or register to continue</h4>
        <v-btn color="success" @click="enrollLogin">Login</v-btn>
        <v-btn color="info" @click="enrollRegister">Register</v-btn>
      </v-col>
    </v-container>
    <v-container v-if="isLoggedIn">
      <!-- Shopping Cart -->
      <v-row no-gutters v-if="shoppingcart && numInCart>0">
        <v-col class="col-md-8">
          <v-row no-gutters>
            <h4 class="headline font-weight-bold">{{numInCart}}</h4>
            <h4 class="headline font-weight-bold" v-if="numInCart==1">. Certificate in Cart</h4>
            <h4 class="headline font-weight-bold" v-if="numInCart>1">. Certificates in Cart</h4>
          </v-row>
          <v-row v-if="numInCart===0" no-gutters>
            <p>You haven't added any certificates to your cart.</p>
          </v-row>
          <v-row no-gutters>
            <v-col class="col-md-12">
              <v-card v-for="item in shoppingcart" :key="item.id+'-forsaleInCart'" class="mb-1">
                <v-card-text v-bind:class="{'error lighten-4':findInProgress(item, item.id)}">
                  <v-slide-y-transition>
                    <v-row
                      no-gutters
                      justify="center"
                      align="center"
                      v-if="findInProgress(item, item.id)"
                    >
                      <v-col>
                        <v-alert
                          type="error"
                          border="left"
                          colored-border
                          color="error accent-4"
                          elevation="2"
                          dismissible
                        >You already have this certificate ({{item.name}}) in progress, please remove it from cart.</v-alert>
                      </v-col>
                    </v-row>
                  </v-slide-y-transition>
                  <v-row no-gutters>
                    <v-col class="col-md-2">
                      <v-img :src="item.image" contain />
                    </v-col>
                    <v-col class="col-md-7">
                      <v-row no-gutters class="ml-2">
                        <h5
                          class="headline font-weight-bold mouse-over orange--text"
                          @click="showSingleCertificate(item.id)"
                        >{{item.name}}</h5>
                      </v-row>
                      <v-row class="caption pa-4 pt-0" no-gutters>{{item.description}}</v-row>
                    </v-col>
                    <v-col class="col-md-1">
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              icon
                              small
                              @click="removeFromCart(item.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>delete_outline</v-icon>
                            </v-btn>
                          </template>
                          <span>Remove from Cart</span>
                        </v-tooltip>
                      </v-row>
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              icon
                              small
                              @click="addToWhishlist(item.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>mdi-heart-outline</v-icon>
                            </v-btn>
                          </template>
                          <span>Move to Whish List</span>
                        </v-tooltip>
                      </v-row>
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              icon
                              small
                              @click="addToSavedlist(item.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>mdi-clock-outline</v-icon>
                            </v-btn>
                          </template>
                          <span>Save for later</span>
                        </v-tooltip>
                      </v-row>
                    </v-col>
                    <v-col class="col-md-2">
                      <v-row justify="center" align="start" no-gutters>
                        <h5 style="color:red;">{{item.price | dollars}}</h5>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
        <v-col class="col-md-4 pa-4">
          <v-row class="mb-1 mt-0" no-gutters>
            <span v-bind:class="{'text-decoration-line-through error--text': subtotal!=-1}">Total:</span>
          </v-row>
          <v-row class="mt-1 mb-1" no-gutters>
            <h2
              class="font-weight-bold"
              v-bind:class="{'text-decoration-line-through error--text': subtotal!=-1}"
            >{{total | dollars}}</h2>
          </v-row>
          <v-row class="mb-1 mt-0" no-gutters v-if="subtotal!=-1">SubTotal:</v-row>
          <v-row class="mt-1 mb-1" no-gutters v-if="subtotal!=-1">
            <h2 class="font-weight-bold">{{subtotal | dollars}}</h2>
          </v-row>
          <v-row class="mt-1 mb-1" no-gutters>
            <v-btn large block color="orange white--text" @click="showCheckout">checkout</v-btn>
          </v-row>
          <v-divider class="mt-2 mb-2" />
          <v-slide-y-transition>
            <v-row
              no-gutters
              align="center"
              justify="center"
              v-if="responseReady&&responseMessageStatus"
            >
              <v-col>
                <v-alert
                  type="success"
                  border="left"
                  colored-border
                  color="success accent-4"
                  elevation="2"
                  dismissible
                >{{responseMessage}}</v-alert>
              </v-col>
            </v-row>
          </v-slide-y-transition>
          <v-slide-y-transition>
            <v-row
              no-gutters
              align="center"
              justify="center"
              v-if="responseReady&&!responseMessageStatus"
            >
              <v-col>
                <v-alert
                  type="error"
                  border="left"
                  colored-border
                  color="error accent-4"
                  elevation="2"
                  dismissible
                >{{responseMessage}}</v-alert>
              </v-col>
            </v-row>
          </v-slide-y-transition>
          <v-row no-gutters align="center" justify="center">
            <v-form
              v-model="isValid"
              ref="couponForm"
              v-on:submit.prevent="applyCoupon"
              class="col-md-12"
            >
              <v-row no-gutters>
                <v-col class="col-md-12">
                  <v-text-field
                    label="Enter Coupon"
                    outlined
                    required
                    dense
                    v-model="couponCode"
                    :rules="requiredRules"
                    color="purple"
                  >
                    <template v-slot:append>
                      <v-btn
                        small
                        @click="applyCoupon"
                        color="orange white--text"
                        :disabled="!isValid"
                      >Apply</v-btn>
                    </template>
                  </v-text-field>
                </v-col>
              </v-row>
            </v-form>
          </v-row>
        </v-col>
      </v-row>
      <!-- saved for later -->
      <v-row no-gutters v-if="Savedlist" class="mt-10">
        <v-col class="col-md-8">
          <v-row no-gutters>
            <h4 class="headline font-weight-bold">Saved for later</h4>
          </v-row>
          <v-row v-if="numInSavedlist===0" no-gutters>
            <p>You haven't saved any certificatesfor later.</p>
          </v-row>
          <v-row v-if="numInSavedlist>0" no-gutters>
            <v-col class="col-md-12">
              <v-card v-for="item in Savedlist" :key="item.id+'-savedlistCart'" class="mb-1">
                <v-card-text>
                  <v-row no-gutters>
                    <v-col class="col-md-2">
                      <v-img :src="item.image" contain />
                    </v-col>
                    <v-col class="col-md-7">
                      <v-row no-gutters class="ml-2">
                        <h5
                          class="font-weight-bold mouse-over orange--text"
                          @click="showSingleCertificate(item.id)"
                        >{{item.name}}</h5>
                      </v-row>
                      <v-row no-gutters class="ml-2">{{item.description}}</v-row>
                    </v-col>
                    <v-col class="col-md-1">
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              icon
                              small
                              @click="removeFromSavedlist(item.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>delete_outline</v-icon>
                            </v-btn>
                          </template>
                          <span>Remove from Saved List</span>
                        </v-tooltip>
                      </v-row>
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn icon small @click="addToCart(item.id)" v-bind="attrs" v-on="on">
                              <v-icon>mdi-cart-plus</v-icon>
                            </v-btn>
                          </template>
                          <span>Add to Cart</span>
                        </v-tooltip>
                      </v-row>
                    </v-col>
                    <v-col class="col-md-2">
                      <v-row justify="center" align="start" no-gutters>
                        <h5 style="color:red;">{{item.price | dollars}}</h5>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <!-- Whishlist -->
      <v-row no-gutters v-if="Whishlist" class="mt-10">
        <v-col class="col-md-8">
          <v-row no-gutters>
            <h4 class="headline font-weight-bold">Recently wishlisted</h4>
          </v-row>
          <v-row v-if="numInWhishlist===0" no-gutters>
            <p>You haven't added any certificates to your wishlist.</p>
          </v-row>
          <v-row v-if="numInWhishlist>0" no-gutters>
            <v-col class="col-md-12">
              <v-card v-for="item in Whishlist" :key="item.id+'-whishlistCart'" class="mb-1">
                <v-card-text>
                  <v-row no-gutters>
                    <v-col class="col-md-2">
                      <v-img :src="item.image" contain />
                    </v-col>
                    <v-col class="col-md-7">
                      <v-row no-gutters class="ml-2">
                        <h5
                          class="font-weight-bold mouse-over orange--text"
                          @click="showSingleCertificate(item.id)"
                        >{{item.name}}</h5>
                      </v-row>
                      <v-row no-gutters class="ml-2">{{item.description}}</v-row>
                    </v-col>
                    <v-col class="col-md-1">
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              icon
                              small
                              @click="removeFromWhishlist(item.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon>delete_outline</v-icon>
                            </v-btn>
                          </template>
                          <span>Remove from Whish List</span>
                        </v-tooltip>
                      </v-row>
                      <v-row justify="end" no-gutters>
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn icon small @click="addToCart(item.id)" v-bind="attrs" v-on="on">
                              <v-icon>mdi-cart-plus</v-icon>
                            </v-btn>
                          </template>
                          <span>Add to Cart</span>
                        </v-tooltip>
                      </v-row>
                    </v-col>
                    <v-col class="col-md-2">
                      <v-row justify="center" align="start" no-gutters>
                        <h5 style="color:red;">{{item.price | dollars}}</h5>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>
<script>
export default {
  props: ["isLoggedIn"],
  filters: {
    dollars: (num) => `$${num ? num.toFixed(2) : num}`,
  },

  data() {
    return {
      user: {},
      spinner: false,
      isValid: false,
      couponCode: "",
      requiredRules: [(v) => !!v || "required!!"],
      responseMessageStatus: "",
      responseReady: false,
      responseMessage: "",
      couponsubtotal: -1,
      certificates: [],
    };
  },
  mounted() {
    this.spinner = true;
    axios
      .get("api/certificates/")
      .then((response) => {
        if (this.isLoggedIn) {
          this.user = JSON.parse(localStorage.getItem("genius.user"));
          this.certificates = response.data;
          this.certificates.forEach((certificate) => {
            //
            certificate.orders.forEach((order) => {
              let orderDate = moment(new Date(order.created_at)).format(
                "YYYY-MM-DD"
              );
              let endDate = moment(new Date(orderDate))
                .add(certificate.available_for, "days")
                .format("YYYY-MM-DD");
              let nowDate = moment(new Date()).format("YYYY-MM-DD");
              let diff = moment(new Date(endDate)).diff(nowDate, "days") + 1;
              if (diff <= 0 && !order.is_finished) {
                let id = order.id;

                let certificate_id = order.certificate_id;
                axios
                  .put(`api/orders/${id}/finish`)
                  .then((response) => {
                    let index = certificate.orders.indexOf(order);
                    certificate.orders[index].is_finished = 1;
                    this.spinner = false;
                  })
                  .catch((error) => {
                    this.spinner = false;
                    console.log(error);
                  });
              }
            });
            //
          });
        }
        this.spinner = false;
      })
      .catch((error) => {
        this.spinner = false;
      });
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
      let total = this.shoppingcart.reduce((acc, cur) => acc + cur.price, 0);
      return total;
    },
    subtotal() {
      let subtotal = this.couponsubtotal;
      return subtotal;
    },
    /* snip */
    shoppingcart() {
      if (this.certificates.length == 0) return;
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
    enrollLogin() {
      this.$emit("enrollLogin", this.$route.fullPath);
    },
    enrollRegister() {
      this.$emit("enrollRegister", this.$route.fullPath);
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
    showCheckout() {
      this.addTotal(this.total);
      this.addSubTotal(this.subtotal);

      this.$emit("showCheckout");
      // if (this.subtotal === 0) this.$emit("showCheckout", this.total);
      // else if (this.subtotal > 0) this.$emit("showCheckout", this.subtotal);
    },
    findInProgress(certificate, certificate_id) {
      if (this.isLoggedIn) {
        if (certificate.orders.length == 0) return false;
        else {
          let is_finished = true;
          certificate.orders.forEach((order) => {
            if (order.user_id === this.user.id) {
              is_finished = is_finished && order.is_finished;
            }
          });
          if (is_finished) return false;
          else if (!is_finished) return true;
        }
      }
    },
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
    },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
    },
    addToWhishlist(certificate_id) {
      this.$store.dispatch("addToWhishlist", certificate_id);
    },
    removeFromWhishlist(certificate_id) {
      this.$store.dispatch("removeFromWhishlist", certificate_id);
    },
    addToSavedlist(certificate_id) {
      this.$store.dispatch("addToSavedlist", certificate_id);
    },
    removeFromSaveslist(certificate_id) {
      this.$store.dispatch("removeFromSavedlist", certificate_id);
    },
    applyCoupon(e) {
      this.responseReady = false;
      this.responseMessage = "";
      this.responseMessageStatus = false;
      let couponCode = this.couponCode;
      let total = this.total;
      axios
        .post("/api/coupons/apply", { couponCode, total })
        .then((response) => {
          this.responseReady = true;
          this.responseMessageStatus = response.data.status;
          this.responseMessage = response.data.message;
          if (this.responseMessageStatus)
            this.couponsubtotal = response.data.subtotal;
          this.$refs.couponForm.reset();
        })
        .catch((error) => {
          this.responseReady = true;
          this.responseMessageStatus = false;
          this.responseMessage = "Something went wrong.";
        });
    },
    addTotal(total) {
      this.$store.dispatch("addTotal", total);
    },
    addSubTotal(subtotal) {
      this.$store.dispatch("addSubTotal", subtotal);
    },
    showSingleCertificate(id) {
      this.$emit("showSingleCertificate", id, "cart");
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
.mouse-over {
  cursor: pointer;
}
.mouse-over:hover {
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
</style>