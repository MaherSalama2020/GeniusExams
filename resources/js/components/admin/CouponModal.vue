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
        <div v-if="edit" class="col-md-12 row">
          <h4>
            <v-icon medium color="cyan">help</v-icon>
            {{data.code}}
          </h4>
        </div>
        <v-card-text>
          <v-form v-model="isValid">
            <div class="form-row">
              <div class="col-md-4 mb-2 md-form">
                <v-select
                  dense
                  :items="users"
                  item-text="name"
                  item-value="id"
                  v-model="data.user_id"
                  label="User"
                  outlined
                  bottom
                  autocomplete
                  required
                  :rules="requiredRules"
                  @input="getUser"
                  color="purple"
                  :disabled="edit==true"
                ></v-select>
              </div>
              <div class="col-md-4">
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
              </div>
              <div class="col-md-3" v-if="data.type=='percent_off'">
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
              </div>
              <div class="col-md-3" v-if="data.type=='fixed'">
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
              </div>
            </div>
            <div class="form-row">
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
            </div>
            <div class="form-row">
              <v-btn-toggle
                dense
                class="ml-2"
                rounded
                background-color="orange"
                active-class="orange white--text"
              >
                <v-btn v-model="data.is_used">
                  Used?
                  <v-icon v-if="data.is_used" color="success">mdi-check</v-icon>
                  <v-icon v-else color="error">mdi-close</v-icon>
                </v-btn>
              </v-btn-toggle>
            </div>
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
                <v-icon light color="white" right>autorenew</v-icon>
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
      return {
        code: null,
        user_id: null,
        type: null,
        value: null,
        percent_off: null,
        is_used: null,
        errors: [],
      };
    },
  },
  methods: {
    close(event) {
      this.$emit("close", this.coupon);
    },
    getUser() {
      let id = this.data.user_id;
      // alert(id);
      let url = `/api/users/${this.data.user_id}`;
      axios.get(url).then((response) => {
        this.data.user = response.data;
      });
    },
    clearerrors() {
      this.errors = [];
    },
    saveCoupon(e) {
      e.preventDefault();
      let index = this.coupons.indexOf(this.coupon);
      let user_id = this.coupon.user_id;
      let code = this.coupon.code;
      let type = this.coupon.type;
      let value = this.coupon.value;
      let percent_off = this.coupon.percent_off;
      let is_used = this.coupon.is_used;
      axios
        .put(`/api/coupons/${this.coupon.id}`, {
          code,
          user_id,
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
      let user_id = this.coupon.user_id;
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
              user_id,
              type,
              value,
              percent_off,
              is_used,
            })
            .then((res) => {
              this.$emit("close", this.coupon);
              this.coupon.id = res.data.id;
              this.coupon.user = res.data.data.user;
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
</style>