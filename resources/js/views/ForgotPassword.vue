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
                  >
                    Send Email
                    <v-icon right medium>mdi-send</v-icon>
                    <template v-slot:loader>
                      <span>Send Email</span>
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
              <v-btn text @click="linkToHome">&copy;{{ new Date().getFullYear() }} —Genius</v-btn>
              <v-btn text @click="alertContactUsDialog">Contact</v-btn>
              <v-btn text>Privacy&terms</v-btn>
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
