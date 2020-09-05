<template>
  <div>
    <Parallax />
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
    <div class="row mx-15 justify-center" v-if="certificates.length>0">
      <v-hover
        v-slot:default="{ hover }"
        v-for="certificate in certificates"
        :key="certificate.id"
        class="mx-2 my-2 col-md-4"
      >
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
          <!-- sdsdsds -->
          <v-card-text class="pt-6" style="position: relative;">
            <v-btn
              absolute
              color="orange"
              class="white--text"
              fab
              large
              right
              top
              @click="showSingleCertificate(certificate.id)"
            >
              <v-icon>mdi-cart</v-icon>
            </v-btn>
            <h3 class="font-weight-light orange--text mb-2">{{certificate.name}}</h3>
            <v-row align="center" class="mx-0">
              <v-rating :value="4.5" color="amber" dense half-increments readonly size="14"></v-rating>
              <div class="grey--text ml-4">4.5 (413)</div>
            </v-row>
            <div class="font-weight-light grey--text mb-2">{{certificate.description}}</div>
          </v-card-text>
        </v-card>
      </v-hover>
    </div>
    <div class="row mx-15 justify-center" v-if="certificates.length==0 && spinner==false">
      <v-card class="mx-2 my-2 col-md-6">
        <v-card-title class="justify-center">No certificates to book</v-card-title>
      </v-card>
    </div>
  </div>
</template>

<script>
import Parallax from "../components/applayout/Parallax";
import * as easings from "vuetify/es5/services/goto/easing-patterns";

export default {
  props: ["isLoggedIn"],
  data() {
    return {
      loading: false,
      selection: 1,
      certificates: [],
      user: null,
      spinner: false,
    };
  },
  components: { Parallax },
  mounted() {
    this.spinner = true;
    axios.get("api/certificates/").then((response) => {
      if (this.isLoggedIn) {
        this.user = JSON.parse(localStorage.getItem("genius.user"));
        this.certificates = response.data.filter((certificate) => {
          if (certificate.orders.length == 0) return certificate;
          else {
            // let bookedBeforeFromThisUser = false;
            let is_finished = true;
            certificate.orders.forEach((order) => {
              if (order.user_id === this.user.id) {
                // bookedBeforeFromThisUser = true;
                is_finished = is_finished && order.is_finished;
              }
            });
            // if (!bookedBeforeFromThisUser) return certificate;
            if (is_finished) return certificate;
          }
        });
      } else {
        this.certificates = response.data;
      }
      this.spinner = false;
    });
  },
  watch: {
    isLoggedIn: function (newValue, oldValue) {
      // alert("Watched");
      if (newValue === true) {
        this.user = JSON.parse(localStorage.getItem("genius.user"));
        this.spinner = true;
        axios.get("api/certificates/").then((response) => {
          this.certificates = response.data.filter(
            (certificate) => certificate.orders.length == 0
          );
          this.spinner = false;
        });
      } else {
        this.spinner = true;
        axios.get("api/certificates/").then((response) => {
          this.certificates = response.data;
          this.spinner = false;
        });
      }
    },
  },
  methods: {
    showSingleCertificate(id) {
      this.loading = true;
      setTimeout(() => (this.loading = false), 2000);
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
.image {
  width: 50;
  height: 50;
}
.hero-section {
  height: 30vh;
  background: #ababab;
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
</style>