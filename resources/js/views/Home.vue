<template>
  <div>
    <HomeSlider @scrollToCertificates="scrollToCertificates" />
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
    <!-- <v-row>
      <v-spacer />
      <v-btn
        class="mt-5 mr-5"
        @click="alertShoppingCartDialog"
        color="orange white--text"
      >Cart ({{ numInCart }})</v-btn>
    </v-row>-->
    <v-row no-gutters justify="center" align="center" id="home_certifiacates">
      <v-col cols="12" sm="auto" md="auto" lg="auto" class="underline-from-center">
        <span class="big-dot mr-2">.</span>
        <span class="big-dot mr-2">.</span>
        <span class="medium-title black--text font-weight-bold">What to train next</span>
        <span class="big-dot ml-2 mr-2">.</span>
        <span class="big-dot mr-2">.</span>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center" align="start" v-if="certificates.length>0">
      <v-col cols="12" sm="auto" md="auto" lg="auto">
        <span class="big-title black--text font-weight-bold">Students are viewing</span>
      </v-col>
    </v-row>

    <!-- <button @click="$refs.myVueperSlides.previous()">Previous</button>
      <button @click="$refs.myVueperSlides.goToSlide(6 - 1)">Go to slide 6</button>
    <button @click="$refs.myVueperSlides.next()">Next</button>-->
    <div v-if="certificates.length>0">
      <!-- <span style="position:relative; top:8rem;   z-index:999;">
        <v-btn @click="$refs.myVueperSlides.previous()" fab medium>
          <v-icon>keyboard_arrow_left</v-icon>
        </v-btn>
      </span>-->

      <vueper-slides
        ref="myVueperSlides"
        class="ml-10 mr-10 no-shadow"
        :autoplay="false"
        :visible-slides="5"
        slide-multiple
        :gap="1"
        :slide-ratio="1 / 6"
        :bullets="false"
        :breakpoints="breakpoints"
        :arrows="true"
        :touchable="false"
      >
        <!-- <template v-slot:arrow-left class="ml-5">
          <v-icon x-large>keyboard_arrow_left</v-icon>
        </template>
        <template v-slot:arrow-right>
          <v-icon x-large>keyboard_arrow_right</v-icon>
        </template>-->

        <vueper-slide
          v-for="(certificate,index) in certificates"
          :key="certificate.id+'-vueper-slide'"
        >
          <template v-slot:content>
            <v-menu
              :nudge-width="0"
              offset-x
              open-on-hover
              rounded="0"
              :left="((index+1)%5==0||(index+1)%5==4)?true:false"
              :right="((index+1)%5!=0||(index+1)%5!=4)?true:false"
              :transition="((index+1)%5==0||(index+1)%5==4)?'scroll-x-reverse-transition':'scroll-x-transition'"
              open-delay="200"
              disable-keys
              :close-on-content-click="false"
              :close-on-click="false"
              max-width="325"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-hover v-slot:default="{ hover }">
                  <v-card color="grey lighten-4 " v-bind="attrs" v-on="on">
                    <!-- {{attrs.aria-expanded}} -->
                    <v-img :aspect-ratio="16/9" :src="certificate.image" :alt="certificate.name">
                      <v-expand-transition>
                        <div
                          v-if="hover"
                          class="font-weight-bold d-flex transition-fast-in-fast-out grey darken-2 v-card--reveal display-3 white--text"
                          style="height: 100%;"
                        >{{certificate.price | dollars}}</div>
                      </v-expand-transition>
                    </v-img>
                    <v-card-text class="pt-2" style="position: relative;">
                      <v-btn
                        absolute
                        color="black"
                        class="white--text hovered-button-black"
                        fab
                        small
                        right
                        top
                        v-if="inCart.find((item)=> item== certificate.id)>-1?false:true"
                        @click="addToCart(certificate.id)"
                      >
                        <v-icon small>mdi-cart-plus</v-icon>
                      </v-btn>

                      <v-btn
                        absolute
                        color="black"
                        class="white--text hovered-button-black"
                        fab
                        small
                        right
                        top
                        v-if="inCart.find((item)=> item== certificate.id)>-1?true:false"
                        @click="removeFromCart(certificate.id)"
                      >
                        <v-icon small>mdi-cart-minus</v-icon>
                      </v-btn>

                      <h5
                        class="font-weight-light orange--text mouse-over textover-1"
                        @click="showSingleCertificate(certificate.id)"
                      >{{certificate.name}}</h5>
                      <v-row no-gutters align="center">
                        <span style="color:#FF9800">&nbsp;{{certificate.rate}}</span>
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
                          <v-icon x-small>book</v-icon>
                          <span class="smallText">{{certificate.bookedNO}}</span>
                          <v-icon class="ml-1" small>mdi-star-circle</v-icon>
                          <span class="smallText">{{certificate.ratedNO}}</span>
                        </div>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-hover>
              </template>
              <v-card flat tile shaped max-width="325" max-height="700" color="white" class="pa-4">
                <div class="font-weight-black text-left textover-2 ml-2">{{certificate.name}}</div>
                <div class="font-weight-light text-right medium-text ml-2">
                  <span style="color:green;">
                    <v-icon small color="success">mdi-calendar</v-icon>
                    updated
                    {{monthNames[new Date(certificate.created_at).getMonth()]}}
                    {{new Date(certificate.created_at).getFullYear()}}
                  </span>
                </div>
                <div class="medium-text grey--text">
                  {{certificate.exams.length}}
                  <span class="large-text">.</span> total ackages
                  <span class="large-text">.</span>
                  <span v-if="certificate.exams[0]">{{certificate.exams[0].type}}</span>
                  <span class="large-text">.</span>
                </div>
                <div class="medium-text black--text">
                  <span class="textover-3">{{certificate.description}}</span>
                </div>
                <v-list>
                  <v-list-item
                    dense
                    v-for="(exam, index) in certificate.exams"
                    :key="exam.id+'-popuplist'"
                  >
                    <v-list-item-icon>
                      <v-icon>mdi-check</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>{{certificate.exams[index].name}}</v-list-item-title>
                  </v-list-item>
                </v-list>
                <v-row align="center" justify="start">
                  <v-col class="col-md-9">
                    <v-btn
                      block
                      color="black"
                      class="white--text hovered-button-black"
                      large
                      v-if="inCart.find((item)=> item== certificate.id)>-1?false:true"
                      @click="addToCart(certificate.id)"
                    >Add To Cart</v-btn>
                    <v-btn
                      block
                      color="black"
                      class="white--text hovered-button-black"
                      large
                      v-if="inCart.find((item)=> item== certificate.id)>-1?true:false"
                      @click="removeFromCart(certificate.id)"
                    >Remove From Cart</v-btn>
                  </v-col>
                  <v-col class="col-md-3">
                    <v-btn
                      fab
                      medium
                      outlined
                      color="black"
                      v-if="inWhishlist.find((item)=> item== certificate.id)>-1?false:true"
                      @click="addToWhishlist(certificate.id)"
                    >
                      <v-icon color="black" large>mdi-heart-outline</v-icon>
                    </v-btn>
                    <v-btn
                      fab
                      medium
                      outlined
                      color="error"
                      v-if="inWhishlist.find((item)=> item== certificate.id)>-1?true:false"
                      @click="removeFromWhishlist(certificate.id)"
                    >
                      <v-icon color="error" large>mdi-heart</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-card>
            </v-menu>
          </template>
        </vueper-slide>
      </vueper-slides>
    </div>

    <div class="row mx-15 justify-center" v-if="certificates.length==0 && spinner==false">
      <v-card class="mx-2 my-2 col-md-6">
        <v-card-title class="justify-center">No certificates to book</v-card-title>
      </v-card>
    </div>
    <ShoppingCartDialog
      :showShoppingCartDialog="showShoppingCartDialog"
      @closeShoppingCartDialog="closeShoppingCartDialog"
      @checkout="checkout"
      :certificates="certificates"
    />
  </div>
</template>

<script>
import HomeSlider from "../components/applayout/HomeSlider";
import * as easings from "vuetify/es5/services/goto/easing-patterns";
import ShoppingCartDialog from "../components/appcore/ShoppingCartDialog";
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
import ScrollAnimation from "../directives/scrollanimation";
Vue.directive("scrollanimation", ScrollAnimation);
export default {
  props: ["isLoggedIn"],
  filters: {
    dollars: (num) => `$${num}`,
  },
  data() {
    return {
      selection: 1,
      certificates: [],
      user: null,
      spinner: false,
      showShoppingCartDialog: false,
      monthNames: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      breakpoints: {
        1200: {
          slideRatio: 1 / 4,
        },
        900: {
          slideRatio: 1 / 4,
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
        },
      },
    };
  },
  components: { HomeSlider, ShoppingCartDialog, VueperSlides, VueperSlide },
  computed: {
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
    inWhishlist() {
      return this.$store.getters.inWhishlist;
    },
    numInWhishlist() {
      return this.inWhishlist.length;
    },
  },

  mounted() {
    this.spinner = true;
    axios
      .get("api/certificates/")
      .then((response) => {
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
      })
      .catch((error) => {
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
      this.$emit("showSingleCertificate", id, "home");
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
    alertShoppingCartDialog() {
      this.showShoppingCartDialog = true;
    },
    closeShoppingCartDialog() {
      this.showShoppingCartDialog = false;
    },
    checkout() {},
    scrollToCertificates() {
      this.$vuetify.goTo("#home_certifiacates", {
        offset: 100,
        duration: 2000,
        easing: "easeInOutQuad",
      });
    },
  },
};
</script>
<style scoped>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
.popupRight {
  position: absolute;
  /* bring your own prefixes */
  left: 102%;
  top: 0%;
  bottom: 0%;
  width: 300px;
  height: 700px !important;
  overflow-y: visible;
  box-shadow: 4px 8px 16px 4px rgba(0, 0, 0, 0.4);
  z-index: 999;
}
.popupLeft {
  position: absolute;
  /* bring your own prefixes */
  right: 102%;
  top: 0%;
  bottom: 0%;
  width: 300px;
  height: 700px !important;
  overflow-y: visible;
  box-shadow: 4px 8px 16px 4px rgba(0, 0, 0, 0.4);
  z-index: 999;
}
* .parent {
  position: relative;
}
.mouse-over {
  cursor: pointer;
}
.mouse-over:hover {
  text-decoration: underline;
}
.smallText {
  font-size: 11px;
}
.textover-1 {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.textover-2 {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.textover-3 {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.small-text {
  font-size: 11px;
}
.medium-text {
  font-size: 13px;
}
.large-text {
  font-size: 36px;
}
.hovered-button-black:hover {
  background-color: white !important;
  color: black !important;
  border: 1px solid black;
}
</style>