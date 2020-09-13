<template>
  <v-main>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <v-col>
        <v-row class="ml-14" no-gutters>
          <v-btn x-small icon @click="linkToHome">
            <v-icon>home</v-icon>
          </v-btn>/&nbsp;
          <span class="activeBreadcrumb">Shopping Cart</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Shopping cart</h3>
        </v-row>
      </v-col>
    </div>
    <v-container>
      <!-- Shopping Cart -->
      <v-row no-gutters v-if="shoppingcart && numInCart>0">
        <v-col class="col-md-8">
          <v-row no-gutters>
            <h4>{{numInCart}}</h4>
            <h4 v-if="numInCart==1">. Certificate in Cart</h4>
            <h4 v-if="numInCart>1">. Certificates in Cart</h4>
          </v-row>
          <v-row v-if="numInCart===0" no-gutters>
            <p>You haven't added any certificates to your cart.</p>
          </v-row>
          <v-row no-gutters>
            <v-col class="col-md-12">
              <v-card v-for="item in shoppingcart" :key="item.id+'-forsaleInCart'" class="mb-1">
                <v-card-text>
                  <v-row no-gutters>
                    <v-col class="col-md-2">
                      <v-img :src="item.image" contain />
                    </v-col>
                    <v-col class="col-md-7">
                      <v-row no-gutters class="ml-2">
                        <h5 class="font-weight-bold">{{item.name}}</h5>
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
                              <v-icon>mdi-heart</v-icon>
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
                              <v-icon>watch_later</v-icon>
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
        <v-col class="col-md-3 ml-2">
          <v-row class="mb-1 mt-0" no-gutters>
            <span v-bind:class="{'text-decoration-line-through error--text': subtotal!=0}">Total:</span>
          </v-row>
          <v-row class="mt-1 mb-1" no-gutters>
            <h2
              class="font-weight-bold"
              v-bind:class="{'text-decoration-line-through error--text': subtotal!=0}"
            >{{total | dollars}}</h2>
          </v-row>
          <v-row class="mb-1 mt-0" no-gutters v-if="subtotal!=0">SubTotal:</v-row>
          <v-row class="mt-1 mb-1" no-gutters v-if="subtotal!=0">
            <h2 class="font-weight-bold">{{subtotal | dollars}}</h2>
          </v-row>
          <v-row class="mt-1 mb-1" no-gutters>
            <v-btn large block color="orange white--text" @click="showCheckout">checkout</v-btn>
          </v-row>
          <v-divider class="mt-2 mb-2" />
          <v-row class="mt-1 mb-1" no-gutters>
            <v-row align="center" justify="center">
              <v-col>
                <v-slide-y-transition>
                  <v-alert
                    color="success lighten-4"
                    icon="check"
                    v-if="responseReady&&responseMessageStatus"
                  >{{responseMessage}}</v-alert>
                  <v-alert
                    color="error lighten-4"
                    icon="warning"
                    v-if="responseReady&&!responseMessageStatus"
                  >{{responseMessage}}</v-alert>
                </v-slide-y-transition>
              </v-col>
            </v-row>
            <v-row align="center" justify="center">
              <v-form v-model="isValid" ref="couponForm">
                <v-row no-gutters>
                  <v-col class="col-md-9" style="background-color:'red'">
                    <v-text-field
                      label="Enter Coupon"
                      outlined
                      required
                      dense
                      v-model="couponCode"
                      :rules="requiredRules"
                      color="purple"
                    />
                  </v-col>
                  <v-col class="col-md-3 ml-0">
                    <v-btn
                      depressed
                      @click="applyCoupon"
                      color="orange white--text"
                      :disabled="!isValid"
                    >Apply</v-btn>
                  </v-col>
                </v-row>
              </v-form>
            </v-row>
          </v-row>
        </v-col>
      </v-row>
      <!-- saved for later -->
      <v-row no-gutters v-if="Savedlist" class="mt-10">
        <v-col class="col-md-8">
          <v-row no-gutters>
            <h4>Saved for later</h4>
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
                        <h5 class="font-weight-bold">{{item.name}}</h5>
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
                              <v-icon>add_shopping_cart</v-icon>
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
            <h4>Recently wishlisted</h4>
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
                        <h5 class="font-weight-bold">{{item.name}}</h5>
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
                              <v-icon>add_shopping_cart</v-icon>
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
  props: ["certificates"],
  filters: {
    dollars: (num) => `$${num ? num.toFixed(2) : num}`,
  },

  data() {
    return {
      isValid: false,
      couponCode: "",
      requiredRules: [(v) => !!v || "required!!"],
      responseMessageStatus: "",
      responseReady: false,
      responseMessage: "",
      couponsubtotal: 0,
    };
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
    showCheckout() {
      this.addTotal(this.total);
      this.addSubTotal(this.subtotal);

      this.$emit("showCheckout");
      // if (this.subtotal === 0) this.$emit("showCheckout", this.total);
      // else if (this.subtotal > 0) this.$emit("showCheckout", this.subtotal);
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
    applyCoupon() {
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
  },
};
</script>
<style scoped>
.activeBreadcrumb {
  color: whitesmoke;
}
</style>