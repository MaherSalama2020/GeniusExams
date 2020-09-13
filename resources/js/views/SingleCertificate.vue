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
    </div>
  </div>
</template>

<script>
import "../plugins/vuetify-datetime.js";

export default {
  data() {
    return {
      certificate: [],
      spinner: false,
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
      axios.get(url).then((response) => {
        this.certificate = response.data;
      });
    },
  },

  beforeMount() {
    let url = `/api/certificates/${this.$route.params.id}`;
    axios.get(url).then((response) => {
      this.certificate = response.data;
    });
  },
  methods: {
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
    },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
    },
  },
};
</script>
