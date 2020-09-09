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
                  >
                    Change Password
                    <v-icon right>autorenew</v-icon>
                    <template v-slot:loader>
                      <span>Change Password</span>
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