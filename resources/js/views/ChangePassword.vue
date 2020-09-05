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
              <h5>Change Password</h5>
            </v-card-title>
            <v-card-text>
              <v-form v-model="isValid" @keyup.native.enter="isValid && handleSubmit($event)">
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
                    <v-btn icon @click="showOldPassword=!showOldPassword" tabindex="-1">
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
                    <v-btn icon @click="showPassword=!showPassword" tabindex="-1">
                      <v-icon v-if="showPassword">mdi-eye</v-icon>
                      <v-icon v-if="!showPassword">mdi-eye-off</v-icon>
                    </v-btn>
                  </template>
                </v-text-field>
                <v-divider></v-divider>
                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    :disabled="!isValid"
                    color="orange white--text"
                    @click="handleSubmit"
                  >Reset Password</v-btn>
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
  props: ["nextUrl"],
  data() {
    return {
      loading: false,
      isValid: true,
      showOldPassword: false,
      showPassword: false,
      showCPassword: false,
      oldpassword: "",
      password: "",
      password_confirmation: "",
      errors: [],
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
          if (response.data.status === 400) {
            this.errors = [response.data.message];
            return;
          }
          console.log(JSON.stringify(response.data));
          this.$emit("passwordChanged");
        })
        .catch((error) => {
          this.loading = false;
          this.isValid = false;
        });
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
  },
};
</script>