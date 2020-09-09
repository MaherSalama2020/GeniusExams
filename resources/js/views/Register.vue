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
                      color="success lighten-4"
                      icon="check"
                    >{{register_message}}</v-alert>
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
                  >
                    Sign up
                    <v-icon right>person_add_alt_1</v-icon>
                    <template v-slot:loader>
                      <span>Sign up</span>
                      <span class="custom-loader">
                        <v-icon light color="white" right>autorenew</v-icon>
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
              <v-btn text color="info" @click="linkToLogin">Sign in</v-btn>
            </h6>
          </v-row>
          <v-footer color="white" app padless>
            <v-col class="text-center" cols="12">
              <v-btn text @click="linkToHome">&copy;{{ new Date().getFullYear() }} —Genius</v-btn>
              <v-btn text>Contact</v-btn>
              <v-btn text>Privacy&terms</v-btn>
            </v-col>
          </v-footer>
        </v-col>
      </v-container>
    </v-main>
  </div>
</template>

<script>
export default {
  props: ["nextUrl"],
  data() {
    return {
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
          .post("api/register", { name, email, password, c_password })
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
            this.responseMessage = "Check your password";
            this.responseMessageStatus = false;
            this.responseReady = true;
          });
      });
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
  },
};
</script>
