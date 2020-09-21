<template>
  <v-container fluid class="home-hero">
    <!-- <v-overlay value="true" absolute></v-overlay> -->
    <v-row no-gutters justify="center" align="start">
      <v-col cols="12" md="6">
        <v-row no-gutters justify="center" align="start">
          <v-col cols="12" sm="auto" md="auto" lg="auto" class="underline-from-center">
            <span class="big-dot mr-2">.</span>
            <span class="big-dot mr-2">.</span>
            <span class="medium-title mr-6 ml-4">Contact us</span>
            <span class="big-dot mr-2">.</span>
            <span class="big-dot">.</span>
          </v-col>
        </v-row>
        <v-row no-gutters justify="center" align="start">
          <v-col cols="12" sm="auto" md="auto" lg="auto">
            <span class="big-title font-weight-bold">Get in Touch</span>
          </v-col>
        </v-row>
        <v-row no-gutters justify="center" align="center">
          <v-col cols="12" sm="1" md="1" lg="1" class="mr-5">
            <v-icon x-large color="white" class="gradient_icon gradient-borders">mdi-email</v-icon>
          </v-col>
          <v-col cols="12" sm="auto" md="auto" lg="auto">
            <v-row>
              <span class="small-title font-weight-bold">Primary: info@k-waves.com</span>
            </v-row>
            <v-row>
              <span class="small-title font-weight-bold">Technical support: support@k-waves.com</span>
            </v-row>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="12" md="5">
        <v-row no-gutters justify="center" align="start">
          <v-col cols="12" sm="auto" md="auto" lg="auto" class="underline-from-center">
            <span class="big-dot mr-2">.</span>
            <span class="big-dot mr-2">.</span>
            <span class="medium-title mr-6 ml-4">Send us A Message</span>
            <span class="big-dot mr-2">.</span>
            <span class="big-dot">.</span>
          </v-col>
        </v-row>
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
        <v-form ref="contactusForm" v-model="valid" class="pa-2" @submit.prevent="send">
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
            <v-btn
              block
              color="orange white--text"
              @click="send"
              :loading="loading"
              class="hovered-button"
            >
              Send
              <!-- <v-icon right>send</v-icon> -->
              <template v-slot:loader>
                <span>Sending...</span>
                <span class="custom-loader">
                  <v-icon light color="white">autorenew</v-icon>
                </span>
              </template>
            </v-btn>
          </v-row>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
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
<style scoped>
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>