<template>
  <v-row justify="center">
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-dialog v-model="showCouponDialog" width="600px" persistent>
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Edit Coupon</span>
          <span v-else class="headline">Add Coupon</span>
          <v-spacer />
          <span @click="close">
            <v-icon class="close" medium>close</v-icon>
          </span>
        </v-card-title>
        <hr class="hr mt-0" />
        <div v-if="edit" class="col-md-10 row ml-2">
          <h4>
            <v-icon medium color="cyan">help</v-icon>
            {{data.code}}
          </h4>
        </div>
        <v-card-text>
          <v-form v-model="isValid">
            <v-row no-gutters>
              <v-col>
                <v-btn-toggle
                  mandatory
                  dense
                  class="mt-3 mb-3"
                  v-model="isRegistered"
                  background-color="orange"
                  active-class="orange white--text"
                  rounded
                >
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn value="Registered" v-bind="attrs" v-on="on">
                        <v-icon>mdi-account-circle</v-icon>
                      </v-btn>
                    </template>
                    Registered User
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn value="NotRegistered" v-bind="attrs" v-on="on">
                        <v-icon>mdi-account-alert</v-icon>
                      </v-btn>
                    </template>
                    Unregistered User
                  </v-tooltip>
                </v-btn-toggle>
              </v-col>
            </v-row>
            <v-row no-gutters>
              <v-col cols="12" md="4">
                <v-select
                  v-if="isRegistered=='Registered'"
                  dense
                  :items="users"
                  item-text="email"
                  item-value="email"
                  v-model="data.email"
                  label="User"
                  outlined
                  bottom
                  autocomplete
                  required
                  :rules="requiredRules"
                  color="purple"
                  :disabled="edit==true"
                  @input="changeUser"
                ></v-select>
                <v-text-field
                  v-if="isRegistered=='NotRegistered'"
                  dense
                  prepend-inner-icon="mdi-email"
                  label="Email"
                  v-model="email"
                  :rules="emailRules"
                  error-count="2"
                  required
                  color="purple lightn-2"
                  outlined
                />
              </v-col>
              <v-col cols="12" md="4" class="ml-1">
                <v-select
                  dense
                  :items="types"
                  v-model="data.type"
                  label="Type"
                  outlined
                  required
                  :rules="requiredRules"
                  @input="changeType"
                  color="purple"
                  :disabled="edit==true"
                ></v-select>
              </v-col>
              <v-col cols="12" md="3" v-if="data.type=='percent_off'" class="ml-1">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-percent"
                  type="number"
                  label="Percent Off"
                  v-model="data.percent_off"
                  required
                  :rules="percentRules"
                  error-count="2"
                  outlined
                  color="purple"
                  :disabled="edit==true"
                />
              </v-col>
              <v-col cols="12" md="3" v-if="data.type=='fixed'" class="ml-1">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-counter"
                  type="number"
                  label="value"
                  v-model="data.value"
                  required
                  :rules="numberRules"
                  error-count="2"
                  outlined
                  color="purple"
                  :disabled="edit==true"
                />
              </v-col>
            </v-row>
            <div class="form-row">
              <v-col cols="12" md="9">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-pencil"
                  label="Coupon Code"
                  v-model="data.code"
                  :rules="requiredRules"
                  error-count="2"
                  :error-messages="errors"
                  @focus="clearerrors"
                  :disabled="edit==true"
                  required
                  outlined
                  autofocus
                />
              </v-col>
              <v-col cols="12" md="3" v-if="!edit">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      icon
                      @click="generateCode(6)"
                      color="orange white--text"
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-icon color="orange" class="settings-loader">settings</v-icon>
                    </v-btn>
                  </template>
                  Generate Code
                </v-tooltip>
              </v-col>
            </div>
            <v-row no-gutters>
              <v-btn-toggle
                dense
                class="ml-2"
                rounded
                background-color="orange"
                active-class="orange white--text"
              >
                <v-btn v-model="data.is_used">
                  <span class="font-weight-bold" style="color:black;" v-if="data.is_used">Expired</span>
                  <span v-if="!data.is_used">Available</span>
                  <v-icon v-if="data.is_used" color="success">mdi-check</v-icon>
                  <v-icon v-else color="error">mdi-close</v-icon>
                </v-btn>
              </v-btn-toggle>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            v-if="edit"
            :disabled="!isValid"
            color="orange white--text"
            @click="saveCoupon"
          >Save Coupon</v-btn>
          <v-btn
            v-else
            :disabled="!isValid"
            color="orange white--text"
            @click="addCoupon"
            :loading="submitLoader"
          >
            Add Coupon
            <template v-slot:loader>
              <span>Adding...</span>
              <span class="custom-loader">
                <v-icon light color="white">autorenew</v-icon>
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
  props: ["coupon", "edit", "coupons", "showCouponDialog", "users"],
  data() {
    return {
      email: "",
      isRegistered: "",
      spinner: false,
      isValid: true,
      errors: [],
      submitLoader: false,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v > 0) || "Price must be more than 0.",
      ],
      percentRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v > 0) || "Rate must be more than 0.",
        (v) => (v && v <= 100) || "Rate must be less than 100.",
      ],
      emailRules: [
        (v) => !!v || "Email is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
      types: [
        { text: "Fixed", value: "fixed" },
        { text: "Percent Off", value: "percent_off" },
      ],
    };
  },
  components: {},

  computed: {
    data: function () {
      if (this.coupon != null) {
        return this.coupon;
      }
      let generatedCode = null;
      return {
        code: null,
        email: null,
        type: null,
        value: null,
        percent_off: null,
        is_used: null,
        errors: [],
      };
    },
  },
  methods: {
    generateCode(len) {
      let text = " ";
      let chars =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOQPRSTUVWXYZ1234567890";

      for (let i = 0; i < len; i++) {
        text += chars.charAt(Math.floor(Math.random() * chars.length));
      }

      this.coupon.code = text;
    },
    close(event) {
      this.$emit("close", this.coupon);
    },
    // getUser() {
    //   let id = this.data.email;
    //   // alert(id);
    //   let url = `/api/users/${this.data.email}`;
    //   axios.get(url).then((response) => {
    //     this.user = response.data;
    //   });
    // },
    clearerrors() {
      this.errors = [];
    },
    saveCoupon(e) {
      e.preventDefault();
      let index = this.coupons.indexOf(this.coupon);
      let email = this.coupon.email;
      let code = this.coupon.code;
      let type = this.coupon.type;
      let value = this.coupon.value;
      let percent_off = this.coupon.percent_off;
      let is_used = this.coupon.is_used;
      axios
        .put(`/api/coupons/${this.coupon.id}`, {
          code,
          email,
          type,
          value,
          percent_off,
          is_used,
        })
        .then((response) => {
          this.coupons[index] = this.coupon;
          this.$emit("close", this.coupon);
          this.submitLoader = false;
        })
        .catch((error) => {
          console.log(error);
          this.submitLoader = false;
        });
    },
    addCoupon(e) {
      e.preventDefault();
      this.submitLoader = true;
      let email = "";
      // alert(this.coupon.email);
      if (this.isRegistered == "NotRegistered") email = this.email;
      else if (this.isRegistered == "Registered") this.coupon.email;
      let code = this.coupon.code;
      let type = this.coupon.type;
      let value = this.coupon.value;
      let percent_off = this.coupon.percent_off;
      let is_used = this.coupon.is_used;
      axios
        .post("/api/coupons/checkcode", { code })
        .then((response) => {
          if (response.data === "yes") {
            this.errors = ["Code in use"];
            this.responseMessage = "Code in use.";
            this.responseMessageStatus = false;
            this.responseReady = true;
            this.loading = false;
            this.isValid = true;
            this.submitLoader = false;
            return;
          }
          axios
            .post("/api/coupons/", {
              code,
              email,
              type,
              value,
              percent_off,
              is_used,
            })
            .then((res) => {
              this.$emit("close", this.coupon);
              this.coupon.id = res.data.id;
              // this.coupon.user = res.data.data.user;
              this.coupons.unshift(this.coupon);
              this.submitLoader = false;
            })
            .catch((error) => {
              console.log(error);
              this.submitLoader = false;
            });
        })
        .catch((error) => {
          console.log(error);
          this.submitLoader = false;
        });
    },
    changeType() {
      if (this.data.type == "fixed") this.data.percent_off = null;
      else if (this.data.type == "percent_off") this.data.value = null;
    },
    changeUser() {
      // alert(this.coupon.email);
    },
    // changeUserType() {
    //   alert(this.isRegistered);
    // },
  },
};
</script>
<style scoped>
.close:hover {
  color: orange;
  cursor: pointer;
  transform: rotate(90deg);
}
.hr {
  border: 0;
  height: 3px;
  background-image: linear-gradient(
    to right,
    rgba(0, 0, 0, 0),
    rgba(0, 0, 0, 0.75),
    rgba(0, 0, 0, 0)
  );
}

.settings-loader:hover {
  animation: loader 1s infinite;
  display: inline-flex;
}
@-moz-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-o-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>