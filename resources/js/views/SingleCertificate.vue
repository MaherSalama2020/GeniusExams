<template>
  <div>
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
          <!-- if arrived from home -->
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                x-small
                icon
                @click="linkToHome"
                v-if="reference_singlecertificate=='home'"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon color="white">home</v-icon>
              </v-btn>
            </template>
            home
          </v-tooltip>
          <v-icon
            small
            color="white"
            class="mr-1 ml-1"
            v-if="reference_singlecertificate=='home'"
          >arrow_forward</v-icon>
          <!-- if arrived from cart -->
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                x-small
                icon
                @click="linkToHome"
                v-if="reference_singlecertificate=='cart'"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon color="white">home</v-icon>
              </v-btn>
            </template>
            home
          </v-tooltip>
          <v-icon
            small
            color="white"
            class="mr-1 ml-1"
            v-if="reference_singlecertificate=='cart'"
          >arrow_forward</v-icon>
          <span
            @click="linkToCart"
            v-if="reference_singlecertificate=='cart'"
            class="activeBreadcrumb"
          >Shopping Cart</span>
          <v-icon
            small
            color="white"
            class="mr-1 ml-1"
            v-if="reference_singlecertificate=='cart'"
          >arrow_forward</v-icon>
          <!-- if arrived from orders -->
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                x-small
                icon
                @click="linkToHome"
                v-if="reference_singlecertificate=='orders'"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon color="white">home</v-icon>
              </v-btn>
            </template>
            home
          </v-tooltip>
          <v-icon
            small
            color="white"
            class="mr-1 ml-1"
            v-if="reference_singlecertificate=='orders'"
          >arrow_forward</v-icon>
          <span
            @click="linkToOrders"
            v-if="reference_singlecertificate=='orders'"
            class="activeBreadcrumb"
          >orders</span>
          <v-icon
            small
            color="white"
            class="mr-1 ml-1"
            v-if="reference_singlecertificate=='orders'"
          >arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">{{certificate.name}}</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Show Certificate</h3>
        </v-row>
      </v-col>
    </div>
    <v-container>
      <div class="row">
        <div class="col-md-5">
          <v-hover v-slot:default="{ hover }" class="mx-2 my-2 col-md-4">
            <v-card color="grey lighten-4" max-width="400">
              <v-img :aspect-ratio="16/9" :src="certificate.image" :alt="certificate.name">
                <v-expand-transition>
                  <div
                    v-if="hover"
                    class="font-weight-bold d-flex transition-fast-in-fast-out grey darken-2 v-card--reveal display-3 white--text"
                    style="height: 100%;"
                  >{{certificate.price | dollars}}</div>
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
                <h5 class="font-weight-bold orange--text mb-2">{{certificate.name}}</h5>

                <v-row no-gutters>
                  <span style="color:#FF9800;">{{certificate.rate}}</span>
                  <v-rating
                    :value="certificate.rate"
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
                    {{certificate.bookedNO}}
                  </div>
                  <div class="grey--text ml-1">
                    <v-icon small>mdi-star-circle</v-icon>
                    {{certificate.ratedNO}}
                  </div>
                </v-row>
                <div class="font-weight-light grey--text mb-2">{{certificate.description}}</div>
              </v-card-text>
              <v-card-subtitle>
                <span v-if="certificate.available_for>1">
                  <v-icon>mdi-calendar</v-icon>
                  {{certificate.available_for}} Days
                </span>
                <span v-if="certificate.available_for==1">
                  <v-icon>mdi-calendar</v-icon>
                  {{certificate.available_for}} Day
                </span>
              </v-card-subtitle>
              <!-- <v-divider light class="mt-0 mb-0" v-if="isLoggedIn"></v-divider>
              <v-card-actions class="pa-4" v-if="uRate!=0 && isLoggedIn">
                <div class="text--lighten-2 caption mr-2">Rated: ({{ uRate }})</div>
                <div class="ml-auto">
                  <v-rating
                    v-model="uRate"
                    background-color="grey darken-1"
                    empty-icon="$ratingFull"
                    color="#FF9800"
                    half-increments
                    readonly
                    size="20"
                  ></v-rating>
                </div>
              </v-card-actions>
              <v-card-actions class="pa-4" v-if="uRate==0&& isLoggedIn">
                <div class="text--lighten-2 caption mr-2">Rate: ({{ default_rating }})</div>
                <div class="ml-auto">
                  <v-rating
                    v-model="default_rating"
                    background-color="grey darken-1"
                    empty-icon="$ratingFull"
                    color="#FF9800"
                    half-increments
                    hover
                    size="20"
                    @input="rateCertificate"
                  ></v-rating>
                </div>
              </v-card-actions>-->
            </v-card>
          </v-hover>
        </div>
      </div>
    </v-container>
  </div>
</template>

<script>
import "../plugins/vuetify-datetime.js";

export default {
  props: ["isLoggedIn", "reference_singlecertificate"],
  filters: {
    dollars: (num) => `$${num ? num.toFixed(2) : num}`,
  },
  data() {
    return {
      certificate: [],
      spinner: false,
      user: {},
    };
  },
  computed: {
    CID() {
      return this.$route.params.id;
    },
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
  },
  components: {},
  mounted() {},
  watch: {
    CID: function (newValue, oldValue) {
      let url = `/api/certificates/${this.$route.params.id}`;
      axios.get(url).then((response) => {});
    },
  },

  beforeMount() {
    let url = `/api/certificates/${this.$route.params.id}`;
    axios.get(url).then((response) => {
      this.certificate = response.data;
    });
    if (localStorage.getItem("genius.jwt") != null) {
      this.user = JSON.parse(localStorage.getItem("genius.user"));
    }
  },
  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToCart() {
      this.$emit("linkToCart");
    },
    linkToOrders() {
      this.$emit("linkToOrders");
    },
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
    },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
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
</style>
