<template>
  <v-row justify="center" no-gutters>
    <v-dialog v-model="showContactUsDialog" width="600px">
      <v-card class="mx-auto mt-0" flat :loading="loading">
        <template slot="progress">
          <v-progress-linear color="#ff7700" indeterminate></v-progress-linear>
        </template>
        <v-card-title>
          <span class="headline">Contact Us</span>
          <v-spacer />
          <span @click="closeContactUsDialog">
            <v-icon class="close" medium>close</v-icon>
          </span>
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
                  class="ma-4"
                >{{response_message}}</v-alert>
                <v-alert
                  v-if="error_show"
                  type="error"
                  border="left"
                  colored-border
                  color="error accent-4"
                  elevation="2"
                  dismissible
                  class="ma-4"
                >{{response_message}}</v-alert>
              </v-slide-y-transition>
            </v-col>
          </v-row>
          <v-form
            ref="contactusForm"
            v-model="valid"
            @keyup.native.enter="valid && send($event)"
            class="pa-2"
          >
            <v-text-field
              dense
              prepend-inner-icon="mdi-email"
              color="purple"
              v-model="email"
              :rules="emailRules"
              placeholder="E-mail"
              outlined
              required
              autofocus
            ></v-text-field>
            <v-text-field
              dense
              prepend-inner-icon="subject"
              color="purple"
              v-model="subject"
              :counter="10"
              :rules="subjectRules"
              placeholder="Subject"
              outlined
              required
            ></v-text-field>
            <v-textarea
              dense
              no-resize
              color="purple"
              v-model="message"
              :rules="messageRules"
              placeholder="Message"
              outlined
              required
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            class="hovered-button"
            :disabled="!valid"
            color="orange white--text"
            @click="send"
            :loading="loading"
          >
            Send
            <v-icon small>send</v-icon>
            <template v-slot:loader>
              <span>Send</span>
              <span class="custom-loader">
                <v-icon light color="white" small>autorenew</v-icon>
              </span>
            </template>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showContactUsDialog"],
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
      this.response_show = false;
      this.error_show = false;
      this.response_message = "";
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
          this.$refs.contactusForm.reset();
        })
        .catch((error) => {
          this.loading = false;
          this.response_message = "Something went wrong";
          this.error_show = true;
        });
    },
    closeContactUsDialog() {
      this.response_message = "";
      this.error_show = false;
      this.response_show = false;
      this.$emit("closeContactUsDialog");
    },
  },
};
</script>
<style scoped>
.close {
  font-size: 2rem;
}
.close:hover {
  color: orange;
  cursor: pointer;
  transform: rotate(90deg);
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>