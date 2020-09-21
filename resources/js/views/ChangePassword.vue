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
              <h5>Change Password</h5>
            </v-card-title>
            <v-card-text>
              <v-row align="center" justify="center">
                <v-col>
                  <v-slide-y-transition>
                    <v-alert
                      type="success"
                      border="left"
                      colored-border
                      color="success accent-4"
                      elevation="2"
                      dismissible
                      v-if="responseReady&&responseMessageStatus"
                    >{{responseMessage}}</v-alert>
                    <v-alert
                      type="error"
                      border="left"
                      colored-border
                      color="error accent-4"
                      elevation="2"
                      dismissible
                      v-if="responseReady&&!responseMessageStatus"
                    >{{responseMessage}}</v-alert>
                  </v-slide-y-transition>
                </v-col>
              </v-row>
              <v-form
                v-model="isValid"
                @keyup.native.enter="isValid && handleSubmit($event)"
                ref="changeForm"
              >
                <v-text-field
                  id="old_password"
                  prepend-icon="mdi-lock"
                  label="Old Password"
                  :type="showOldPassword?'text': 'password'"
                  v-model="oldpassword"
                  :rules="passwordRules"
                  error-count="2"
                  :error-messages="errors"
                  @focus="clearerrors"
                  required
                  color="purple lightn-2"
                >
                  <template v-slot:append>
                    <v-btn icon @click="viewOldPassword" tabindex="-1" :disabled="showOldPassword">
                      <v-icon v-if="showOldPassword">mdi-eye</v-icon>
                      <v-icon v-if="!showOldPassword">mdi-eye-off</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
                <v-text-field
                  id="password"
                  prepend-icon="mdi-lock"
                  :append-icon="showPassword?'mdi-eye':'mdi-eye-off'"
                  label="New Password"
                  :type="showPassword?'text': 'password'"
                  @click:append="showPassword=!showPassword"
                  v-model="password"
                  :rules="passwordRules"
                  error-count="2"
                  required
                  color="purple lightn-2"
                >
                  <template v-slot:append>
                    <v-btn icon @click="viewPassword" tabindex="-1" :disabled="showPassword">
                      <v-icon v-if="showPassword">mdi-eye</v-icon>
                      <v-icon v-if="!showPassword">mdi-eye-off</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
                <v-text-field
                  id="password-confirm"
                  prepend-icon="mdi-lock"
                  label="Confirm Password"
                  :type="showCPassword?'text': 'password'"
                  v-model="password_confirmation"
                  :rules="cpasswordRules"
                  error-count="3"
                  required
                  color="purple lightn-2"
                >
                  <template v-slot:append>
                    <v-btn icon @click="viewCPassword" tabindex="-1" :disabled="showCPassword">
                      <v-icon v-if="showCPassword">mdi-eye</v-icon>
                      <v-icon v-if="!showCPassword">mdi-eye-off</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
                <!-- <v-divider></v-divider> -->
                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    :disabled="!isValid"
                    color="orange white--text"
                    @click="handleSubmit"
                    :loading="loading"
                    class="hovered-button px-10"
                  >
                    Change Password
                    <!-- <v-icon right>autorenew</v-icon> -->
                    <template v-slot:loader>
                      <span>Processing...</span>
                      <span class="custom-loader">
                        <v-icon light color="white">autorenew</v-icon>
                      </span>
                    </template>
                  </v-btn>
                </v-card-actions>
              </v-form>
            </v-card-text>
          </v-card>
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
  props: ["nextUrl"],
  components: { ContactUsDialog },
  data() {
    return {
      showContactUsDialog: false,
      loading: false,
      isValid: true,
      showOldPassword: false,
      showPassword: false,
      showCPassword: false,
      oldpassword: "",
      password: "",
      password_confirmation: "",
      errors: [],
      responseMessage: "",
      responseMessageStatus: false,
      responseReady: false,
      passwordRules: [
        (v) => !!v || "Password is required",
        (v) => (v && v.length >= 5) || "Password must have 5+ characters",
        // (v) => /(?=.*[A-Z])/.test(v) || "Must have one uppercase character",
        // (v) => /(?=.*\d)/.test(v) || "Must have one number",
        // (v) => /([!@$%])/.test(v) || "Must have one special character [!@#$%]",
      ],
      cpasswordRules: [
        (v) => !!v || "Password is required",
        (v) => v == this.password || "Passwords  dont match",
        (v) => (v && v.length >= 5) || "Password must have 5+ characters",
        // (v) => /(?=.*[A-Z])/.test(v) || "Must have one uppercase character",
        // (v) => /(?=.*\d)/.test(v) || "Must have one number",
        // (v) => /([!@$%])/.test(v) || "Must have one special character [!@#$%]",
      ],
    };
  },
  methods: {
    clearerrors() {
      this.errors = [];
    },
    handleSubmit(e) {
      e.preventDefault();
      this.responseReady = false;
      this.responseMessageStatus = false;
      this.responseMessage = "";
      let old_password = this.oldpassword;
      let new_password = this.password;
      let confirm_password = this.password_confirmation;
      this.loading = true;
      axios
        .post("api/change_password", {
          old_password,
          new_password,
          confirm_password,
        })
        .then((response) => {
          this.loading = false;
          this.isValid = false;
          // if (response.data.status) {
          console.log(response.data);
          // this.errors = [response.data.message];
          this.responseMessage = response.data.message;
          this.responseMessageStatus = response.data.status;
          this.responseReady = true;
          if (response.data.status) this.$refs.changeForm.reset();
          // return;
          // }
          // console.log(JSON.stringify(response.data));
          // this.$emit("passwordChanged");
        })
        .catch((error) => {
          this.loading = false;
          // this.isValid = false;
          this.responseMessage = "Something went wrong.";
          this.responseMessageStatus = false;
          this.responseReady = true;
        });
    },
    linkToTerms() {
      this.$emit("linkToTerms");
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
    viewOldPassword() {
      this.showOldPassword = true;
      setTimeout(() => (this.showOldPassword = false), 1000);
    },
    viewPassword() {
      this.showPassword = true;
      setTimeout(() => (this.showPassword = false), 1000);
    },
    viewCPassword() {
      this.showCPassword = true;
      setTimeout(() => (this.showCPassword = false), 1000);
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