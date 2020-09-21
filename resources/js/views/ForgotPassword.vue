<template>
  <div>
    <v-main fluid fill-height class="geniusback">
      <v-container class="geniuscontainer">
        <v-col>
          <v-row>
            <v-btn class="mx-auto mb-5 orange--text genius" text @click="linkToHome">
              <h4>Genius</h4>
            </v-btn>
          </v-row>
          <v-card width="500" class="mx-auto mt-0" :loading="loading">
            <template slot="progress">
              <v-progress-linear color="#ff7700" indeterminate></v-progress-linear>
            </template>
            <v-card-title>
              <h5>Forgot your password</h5>
            </v-card-title>
            <v-card-text>
              <v-row align="center" justify="center">
                <v-col>
                  <v-slide-y-transition>
                    <v-alert
                      v-if="responseReady&&responseMessageStatus"
                      type="success"
                      border="left"
                      colored-border
                      color="success accent-4"
                      elevation="2"
                      dismissible
                    >{{responseMessage}}</v-alert>
                    <v-alert
                      v-if="responseReady&&!responseMessageStatus"
                      type="error"
                      border="left"
                      colored-border
                      color="error accent-4"
                      elevation="2"
                      dismissible
                    >{{responseMessage}}</v-alert>
                  </v-slide-y-transition>
                </v-col>
              </v-row>
              <v-form
                ref="forgotForm"
                v-model="isValid"
                v-on:submit.prevent="isValid && handleSubmit"
                @keyup.native.enter="isValid && handleSubmit($event)"
              >
                <v-text-field
                  prepend-icon="mdi-email"
                  label="Email"
                  v-model="email"
                  :rules="emailRules"
                  error-count="2"
                  required
                  :error-messages="errors"
                  @focus="clearerrors"
                  autofocus
                  color="purple lightn-2"
                />
                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    :disabled="!isValid"
                    color="orange white--text"
                    @click="handleSubmit"
                    :loading="loading"
                    class="hovered-button px-10"
                  >
                    Send Email
                    <!-- <v-icon right medium>mdi-send</v-icon> -->
                    <template v-slot:loader>
                      <span>Sending...</span>
                      <span class="custom-loader">
                        <v-icon light color="white">autorenew</v-icon>
                      </span>
                    </template>
                  </v-btn>
                </v-card-actions>
              </v-form>
            </v-card-text>
          </v-card>
          <!-- <v-row>
            <h6 class="mx-auto mt-5">
              Have an account?
              <v-btn text color="info" @click="linkToLogin">Sign in</v-btn>
            </h6>
          </v-row>-->
          <v-footer color="white" app padless>
            <v-col class="text-center" cols="12">
              <v-btn
                text
                @click="linkToHome"
                :ripple="false"
                class="hovered-button-undeline"
              >&copy;{{ new Date().getFullYear() }} —Genius</v-btn>
              <v-btn
                text
                @click="alertContactUsDialog"
                :ripple="false"
                class="hovered-button-undeline"
              >Contact</v-btn>
              <v-btn
                text
                @click="linkToTerms"
                :ripple="false"
                class="hovered-button-undeline"
              >Privacy&terms</v-btn>
            </v-col>
          </v-footer>
        </v-col>
        <ContactUsDialog
          :showContactUsDialog="showContactUsDialog"
          @closeContactUsDialog="closeContactUsDialog"
        />
      </v-container>
    </v-main>
  </div>
</template>
<script>
import ContactUsDialog from "../components/appcore/ContactUsDialog";
export default {
  components: { ContactUsDialog },
  data() {
    return {
      showContactUsDialog: false,
      loading: false,
      isValid: true,
      email: "",
      errors: [],
      responseMessage: "",
      responseMessageStatus: false,
      responseReady: false,
      emailRules: [
        (v) => !!v || "Email is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
    };
  },
  methods: {
    forgotPassword() {
      this.$emit("forgotPassword");
    },
    // resetPasswordForm() {
    //   this.$emit("resetPasswordForm");
    // },
    clearerrors() {
      this.errors = [];
    },
    handleSubmit(e) {
      e.preventDefault();
      this.responseReady = false;
      this.responseMessageStatus = false;
      this.responseMessage = "";
      this.loading = true;
      let email = this.email;
      let password = this.password;
      axios
        .post("api/checkemail", { email })
        .then((response) => {
          // console.log(JSON.stringify(response));
          if (response.data === "no") {
            // this.errors = ["Email not in use"];
            this.responseMessage = "Email not in use";
            this.responseMessageStatus = false;
            this.responseReady = true;
            this.loading = false;
            return;
          }
          axios.post("/api/reset-password", { email: this.email }).then(
            (response) => {
              console.log(response.data);
              this.loading = false;
              this.$refs.forgotForm.reset();
              this.responseMessage = response.data.message;
              this.responseMessageStatus = response.data.status;
              this.responseReady = true;
            },
            (error) => {
              console.error(error);
              this.loading = false;
              this.isValid = false;
              this.responseMessage = "Something went wrong.";
              this.responseMessageStatus = false;
              this.responseReady = true;
            }
          );
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    linkToTerms() {
      this.$emit("linkToTerms");
    },
    linkToHome() {
      this.$emit("linkToHome");
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
.genius {
  font-family: "Krona One", sans-serif !important;
}
.geniuscontainer {
  margin-top: 135px;
}
.geniusback {
  margin-top: -135px;
  background-color: #ffffff;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='614' height='614' viewBox='0 0 200 200'%3E%3Cdefs%3E%3ClinearGradient id='a' gradientUnits='userSpaceOnUse' x1='88' y1='88' x2='0' y2='0'%3E%3Cstop offset='0' stop-color='%238f3f00'/%3E%3Cstop offset='1' stop-color='%23e36500'/%3E%3C/linearGradient%3E%3ClinearGradient id='b' gradientUnits='userSpaceOnUse' x1='75' y1='76' x2='168' y2='160'%3E%3Cstop offset='0' stop-color='%238f8f8f'/%3E%3Cstop offset='0.09' stop-color='%23b3b3b3'/%3E%3Cstop offset='0.18' stop-color='%23c9c9c9'/%3E%3Cstop offset='0.31' stop-color='%23dbdbdb'/%3E%3Cstop offset='0.44' stop-color='%23e8e8e8'/%3E%3Cstop offset='0.59' stop-color='%23f2f2f2'/%3E%3Cstop offset='0.75' stop-color='%23fafafa'/%3E%3Cstop offset='1' stop-color='%23FFFFFF'/%3E%3C/linearGradient%3E%3Cfilter id='c' x='0' y='0' width='200%25' height='200%25'%3E%3CfeGaussianBlur in='SourceGraphic' stdDeviation='12' /%3E%3C/filter%3E%3C/defs%3E%3Cpolygon fill='url(%23a)' points='0 174 0 0 174 0'/%3E%3Cpath fill='%23000' fill-opacity='.5' filter='url(%23c)' d='M121.8 174C59.2 153.1 0 174 0 174s63.5-73.8 87-94c24.4-20.9 87-80 87-80S107.9 104.4 121.8 174z'/%3E%3Cpath fill='url(%23b)' d='M142.7 142.7C59.2 142.7 0 174 0 174s42-66.3 74.9-99.3S174 0 174 0S142.7 62.6 142.7 142.7z'/%3E%3C/svg%3E");
  background-attachment: fixed;
  background-repeat: no-repeat;
  background-position: top left;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
.hovered-button-undeline:hover {
  text-decoration: underline;
  text-decoration-color: blue;
}
</style>