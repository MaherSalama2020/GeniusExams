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
              <h5>Create your Genius account</h5>
            </v-card-title>
            <v-card-text>
              <v-row align="center" justify="center">
                <v-col>
                  <v-slide-y-transition>
                    <v-alert
                      v-if="register_result"
                      type="success"
                      border="left"
                      colored-border
                      color="success accent-4"
                      elevation="2"
                      dismissible
                    >{{register_message}}</v-alert>
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
                ref="registerForm"
                v-model="isValid"
                @keyup.native.enter="isValid && handleSubmit($event)"
              >
                <v-text-field
                  id="name"
                  prepend-icon="mdi-account-circle"
                  label="Name"
                  v-model="name"
                  :rules="nameRules"
                  error-count="2"
                  required
                  autofocus
                  color="purple lightn-2"
                />
                <v-text-field
                  id="email"
                  prepend-icon="mdi-email"
                  label="Email"
                  v-model="email"
                  :error-messages="errors"
                  @focus="clearerrors"
                  :rules="emailRules"
                  error-count="2"
                  required
                  color="purple lightn-2"
                />
                <v-select
                  v-model="selectedCountry"
                  :items="countries"
                  item-text="name"
                  item-value="name"
                  label="Select Country"
                  required
                  menu-props="auto"
                  prepend-icon="map"
                  bottom
                  autocomplete
                  :rules="requiredRules"
                  color="purple"
                />
                <v-text-field
                  id="password"
                  prepend-icon="mdi-lock"
                  label="Password"
                  :type="showPassword?'text': 'password'"
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
                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    :disabled="!isValid"
                    color="orange white--text"
                    @click="handleSubmit"
                    :loading="loading"
                    class="hovered-button px-10"
                  >
                    Sign up
                    <!-- <v-icon right>person_add_alt_1</v-icon> -->
                    <template v-slot:loader>
                      <span>Processing..</span>
                      <span class="custom-loader">
                        <v-icon light color="white">autorenew</v-icon>
                      </span>
                    </template>
                  </v-btn>
                </v-card-actions>
              </v-form>
            </v-card-text>
          </v-card>
          <v-row>
            <h6 class="mx-auto mt-5">
              Have an account?
              <v-btn
                text
                color="info"
                @click="linkToLogin"
                :ripple="false"
                class="hovered-button-undeline"
              >Sign in</v-btn>
            </h6>
          </v-row>
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
      selectedCountry: {},
      showContactUsDialog: false,
      loading: false,
      register_result: false,
      register_message: "",
      isValid: true,
      showPassword: false,
      showCPassword: false,
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      errors: [],
      responseMessage: "",
      responseMessageStatus: false,
      responseReady: false,
      countries: [],
      requiredRules: [(v) => !!v || "required!!"],
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => (v && v.length >= 5) || "Name must have 5+ characters",
      ],
      emailRules: [
        (v) => !!v || "Email is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
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
  beforeMount() {
    $.get("https://restcountries.eu/rest/v2/all?fields=name;alpha3Code;flag")
      .then((response) => {
        // this.countries = response.map((country) => country.name);
        this.countries = response;
      })
      .catch((error) => console.log(error));
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
      this.register_result = false;
      // if (
      //   this.password !== this.password_confirmation ||
      //   this.password.length <= 0
      // ) {
      //   this.password = "";
      //   this.password_confirmation = "";
      //   return alert("Passwords do not match");
      // }
      let name = this.name;
      let email = this.email;
      let country = this.selectedCountry;
      let password = this.password;
      let c_password = this.password_confirmation;
      this.loading = true;
      axios.post("api/checkemail", { email }).then((response) => {
        // console.log(JSON.stringify(response));
        if (response.data === "yes") {
          this.responseMessage = "Email in use.";
          this.responseMessageStatus = false;
          this.responseReady = true;
          this.loading = false;
          this.isValid = true;
          return;
        }
        axios
          .post("api/register", { name, email, country, password, c_password })
          .then((response) => {
            this.loading = false;
            let data = response.data;
            this.register_result = true;
            this.register_message = response.data.message;
            this.loading = false;
            // this.isValid = false;
            this.$refs.registerForm.reset();

            //localStorage.setItem("genius.user", JSON.stringify(data.user));
            //localStorage.setItem("genius.jwt", data.token);
            // if (localStorage.getItem("genius.jwt") != null) {
            //   this.$emit("loggedIn");
            //   if (this.$route.params.nextUrl != null) {
            //     this.$emit("hasNext", this.$route.params.nextUrl);
            //   } else {
            //     this.$emit("noNext");
            //   }
            // }
          })
          .catch((error) => {
            this.loading = false;
            // this.isValid = true;
            console.log(error);
            this.responseMessage = "Something went wrong.";
            this.responseMessageStatus = false;
            this.responseReady = true;
          });
      });
    },
    linkToTerms() {
      this.$emit("linkToTerms");
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToLogin() {
      this.$emit("linkToLogin");
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