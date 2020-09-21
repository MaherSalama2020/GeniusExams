<template>
  <span>
    <v-row justify="center" align="start">
      <v-col class="col-12 col-sm-3 col-md-3 col-lg-3">
        <v-row no-gutters class="mb-2">
          <v-img :src="'/images/logo.png'" max-width="75" contain></v-img>
        </v-row>
        <v-row no-gutters>
          <span
            class="font-weight-black underline-from-top pl-2"
          >We take our mission of increasing global access to quality education seriously. We connect learners to the best universities and institutions from around the world.</span>
        </v-row>
      </v-col>
      <v-col class="col-12 col-sm-3 col-md-3 col-lg-3">
        <ul class="list-unstyled">
          <li class="mb-3">
            <span class="border-bottom pb-1 text--black font-weight-bold">Useful Links</span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToHome">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Certificates</span>
              </v-btn>
            </span>
          </li>
          <li v-if="isLoggedIn">
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToOrders">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Orders</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToCart">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Cart</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToReviews">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Students' Reviews</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToAllFAQS">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">FAQS</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="alertContactUsDialog">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Contact us</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToTerms">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">Privacy & Terms</span>
              </v-btn>
            </span>
          </li>
          <li>
            <span>
              <v-btn text small color="black" class="active-button" @click="linkToAbout">
                <v-icon small color="orange">mdi-menu-right</v-icon>
                <span class="text--black font-weight-black active-span">About us</span>
              </v-btn>
            </span>
          </li>
        </ul>
      </v-col>
      <v-col class="col-12 col-sm-3 col-md-3 col-lg-3">
        <ul class="list-unstyled">
          <li class="mb-3">
            <span class="border-bottom pb-1 text--black font-weight-bold">Statistics</span>
          </li>
          <li>
            <v-card max-width="344" tile flat color="transparent">
              <v-list-item three-line>
                <v-list-item-avatar tile color="transparent" left>
                  <v-icon x-large color="white" class="gradient_icon">mdi-book-open-variant</v-icon>
                </v-list-item-avatar>
                <!-- <v-divider vertical class="ml-0 mr-2" ></v-divider> -->
                <v-list-item-content class="underline-from-top">
                  <div
                    style="color:white;"
                    class="slide_title mb-1 font-weight-bold black--text ml-2"
                  >{{availableCertificatesLength}}+</div>
                  <div class="description ml-2">Online Available Certificates</div>
                </v-list-item-content>
              </v-list-item>
            </v-card>
          </li>
          <li>
            <v-card max-width="344" tile flat color="transparent">
              <v-list-item three-line>
                <v-list-item-avatar tile color="transparent" left>
                  <v-icon x-large color="white" class="gradient_icon">mdi-store-24-hour</v-icon>
                </v-list-item-avatar>
                <!-- <v-divider vertical class="ml-0 mr-2" ></v-divider> -->
                <v-list-item-content class="underline-from-top">
                  <div
                    style="color:white;"
                    class="slide_title mb-1 font-weight-bold black--text ml-2"
                  >24/7</div>
                  <div class="description ml-2">Support and Assistance</div>
                </v-list-item-content>
              </v-list-item>
            </v-card>
          </li>
          <li>
            <v-card max-width="344" tile flat color="transparent">
              <v-list-item three-line>
                <v-list-item-avatar tile color="transparent" left>
                  <v-icon x-large color="white" class="gradient_icon">mdi-account-multiple</v-icon>
                </v-list-item-avatar>
                <!-- <v-divider vertical class="ml-0 mr-2" ></v-divider> -->
                <v-list-item-content class="underline-from-top">
                  <div
                    style="color:white;"
                    class="slide_title mb-1 font-weight-bold black--text ml-2"
                  >2000+</div>
                  <div class="description ml-2">Passed Students</div>
                </v-list-item-content>
              </v-list-item>
            </v-card>
          </li>
        </ul>
      </v-col>
    </v-row>
    <ContactUsDialog
      :showContactUsDialog="showContactUsDialog"
      @closeContactUsDialog="closeContactUsDialog"
    />
  </span>
</template>
<script>
import ContactUsDialog from "../appcore/ContactUsDialog";
export default {
  props: ["isLoggedIn", "availableCertificatesLength"],
  components: { ContactUsDialog },
  data() {
    return {
      showContactUsDialog: false,
    };
  },
  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToOrders() {
      this.$emit("linkToOrders");
    },
    linkToCart() {
      this.$emit("linkToCart");
    },
    linkToReviews() {
      this.$emit("linkToReviews");
    },
    linkToAllFAQS() {
      this.$emit("linkToAllFAQS");
    },
    linkToTerms() {
      this.$emit("linkToTerms");
    },
    linkToAbout() {
      this.$emit("linkToAbout");
    },
    alertContactUsDialog() {
      this.showContactUsDialog = true;
    },
    closeContactUsDialog() {
      this.showContactUsDialog = false;
    },
  },
};
</script>
<style scoped>
.active-button:hover {
  transform: scaleY(1.2);
  transform: translateX(10px);
}
.active-span:hover {
  color: coral;
}
</style>