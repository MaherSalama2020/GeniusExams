<template>
  <v-parallax dark src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg">
    <!-- <v-overlay value="true" absolute></v-overlay> -->
    <v-row>
      <v-col cols="12" md="6" class="ml-15 mt-10">
        <v-row justify="center">
          <h5>
            <i class="fa fa-road"></i> Genius CO INC.
          </h5>
        </v-row>
        <v-row justify="center">
          <div class="col-3">
            <ul class="list-unstyled">
              <li>
                <div>
                  <v-btn text small color="white">Products</v-btn>
                </div>
              </li>
              <li>
                <v-btn text small color="white">Benefits</v-btn>
              </li>
              <li>
                <v-btn text small color="white">Partners</v-btn>
              </li>
              <li>
                <v-btn text small color="white">Team</v-btn>
              </li>
            </ul>
          </div>
          <div class="col-3">
            <ul class="list-unstyled">
              <li>
                <v-btn text small color="white">Documentation</v-btn>
              </li>
              <li>
                <v-btn text small color="white">Support</v-btn>
              </li>
              <li>
                <v-btn text small color="white">Legal Terms</v-btn>
              </li>
              <li>
                <v-btn text small color="white">About</v-btn>
              </li>
            </ul>
          </div>
        </v-row>
      </v-col>
      <v-col cols="12" md="5">
        <!-- <h5>
            <i class="fa fa-road"></i> Contact Us
        </h5>-->
        <v-card class="mx-auto mt-5 transparent" flat :loading="loading">
          <v-card-title>
            <h5 class="white--text">
              <i class="fa fa-road white--text"></i> Contact Us
            </h5>
          </v-card-title>
          <v-card-text>
            <v-row justify="center" align="center">
              <v-col>
                <v-slide-y-transition>
                  <v-alert
                    v-if="response_show"
                    type="success"
                    border="left"
                    colored-border
                    color="success accent-4"
                    elevation="2"
                    dismissible
                  >{{response_message}}</v-alert>
                  <v-alert
                    v-if="error_show"
                    type="error"
                    border="left"
                    colored-border
                    color="error accent-4"
                    elevation="2"
                    dismissible
                  >{{response_message}}</v-alert>
                </v-slide-y-transition>
              </v-col>
            </v-row>
            <v-form ref="contactusForm" v-model="valid" class="pa-2">
              <v-text-field
                prepend-inner-icon="mdi-email"
                background-color="white"
                color="purple"
                v-model="email"
                :rules="emailRules"
                placeholder="E-mail"
                solo
                shaped
                required
              ></v-text-field>
              <v-text-field
                prepend-inner-icon="subject"
                background-color="white"
                color="purple"
                v-model="subject"
                :counter="10"
                :rules="subjectRules"
                placeholder="Subject"
                solo
                shaped
                required
              ></v-text-field>
              <v-textarea
                background-color="white"
                no-resize
                color="purple"
                v-model="message"
                :rules="messageRules"
                placeholder="Message"
                solo
                shaped
                required
              ></v-textarea>
              <v-row>
                <v-spacer></v-spacer>
                <v-btn
                  :disabled="!valid"
                  color="orange white--text"
                  @click="send"
                  :loading="loading"
                >
                  Send
                  <v-icon right>send</v-icon>
                  <template v-slot:loader>
                    <span>Send</span>
                    <span class="custom-loader">
                      <v-icon light color="white">autorenew</v-icon>
                    </span>
                  </template>
                </v-btn>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-parallax>
</template>
<style scoped>
.transparent {
  background-color: white !important;
  opacity: 0.65;
  border-color: transparent !important;
}
.back {
  background-image: url(https://images.pexels.com/photos/186077/pexels-photo-186077.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260);
  background-size: cover;
}
</style>
<script>
export default {
  data: () => ({
    loading: false,
    response_message: "",
    response_show: false,
    error_show: false,
    valid: true,
    subject: "",
    subjectRules: [
      (v) => !!v || "Name is required",
      (v) => (v && v.length <= 10) || "Name must be less than 10 characters",
    ],
    message: "",
    messageRules: [
      (v) => !!v || "Message is required",
      (v) => (v && v.length > 10) || "Message must be more than 10 characters",
    ],
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid",
    ],
  }),

  methods: {
    send(e) {
      e.preventDefault();
      this.loading = true;
      let email = this.email;
      let subject = this.subject;
      let message = this.message;
      axios
        .post("/api/contact-us", { email, subject, message })
        .then((response) => {
          this.loading = false;
          this.response_message = response.data.message;
          this.response_show = true;
          this.email = "";
          this.subject = "";
          this.message = "";
          this.$refs.contactusForm.reset();
        })
        .catch((error) => {
          this.loading = false;
          this.response_message = "Something went wrong";
          this.error_show = true;
        });
      // this.$refs.form.validate();
    },
  },
};
</script>
