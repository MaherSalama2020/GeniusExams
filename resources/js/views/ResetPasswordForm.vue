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
          <v-card width="500" class="mx-auto mt-5" :loading="loading">
            <template slot="progress">
              <v-progress-linear color="#ff7700" indeterminate></v-progress-linear>
            </template>
            <v-card-title>
              <h5>Reset Password</h5>
            </v-card-title>
            <v-card-text>
              <v-form v-model="isValid" @keyup.native.enter="isValid && handleSubmit($event)">
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
                  disabled
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
                  autofocus
                  color="purple lightn-2"
                >
                  <template v-slot:append>
                    <v-btn icon @click="showPassword=!showPassword" tabindex="-1">
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
                    <v-btn icon @click="showCPassword=!showCPassword" tabindex="-1">
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
                    Reset Password
                    <template v-slot:loader>
                      <span class="custom-loader">
                        <v-icon light class="white--text">cached</v-icon>
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
  data() {
    return {
      loading: false,
      token: null,
      isValid: true,
      showPassword: false,
      showCPassword: false,
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      errors: [],
      nameRules: [
        (v) => !!v || "Password is required",
        (v) => (v && v.length >= 5) || "Password must have 6+ characters",
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
  mounted() {
    let params = this.$route.params.token;
    var parts = params.split("@@");
    this.token = parts[0];
    this.email = parts[1];
  },
  methods: {
    clearerrors() {
      this.errors = [];
    },
    handleSubmit(e) {
      //   alert(this.$route.params.token);
      this.loading = true;
      axios
        .post("api/reset/password/", {
          token: this.token,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then(
          (result) => {
            this.loading = false;
            this.isValid = true;
            this.$emit("PasswordWasReset");
            // console.log(result.data);
            //   this.$router.push({ name: "login" });
          },
          (error) => {
            this.loading = false;
            this.isValid = true;
            console.error(error);
          }
        );
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
  },
};
</script>