<template>
  <v-row justify="center">
    <v-dialog v-model="showOrderDialog" width="600px">
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Edit Order</span>
          <span v-else class="headline">Add Order</span>
          <v-spacer />
          <span @click="close">
            <v-icon medium class="close">close</v-icon>
          </span>
        </v-card-title>
        <hr class="hr mt-0" />
        <div v-if="edit" class="col-md-12 row">
          <h4>
            <v-icon medium color="cyan">help</v-icon>
            <span v-if="data.user!=null">{{data.user.name}}</span>
          </h4>
        </div>
        <v-card-text>
          <v-form v-model="isValid">
            <div class="form-row">
              <div class="col-md-4 mb-2 md-form">
                <v-select
                  dense
                  :items="certificates"
                  item-text="name"
                  item-value="id"
                  v-model="data.certificate_id"
                  label="Certificate"
                  outlined
                  bottom
                  autocomplete
                  required
                  :rules="requiredRules"
                  @input="getCertificate"
                  color="purple"
                ></v-select>
              </div>
              <div class="col-md-4">
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
                ></v-select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <date-picker v-model="data.date" :config="dateoptions" required color="purole"></date-picker>
              </div>
              <div class="col-md-4">
                <date-picker v-model="data.time" :config="timeoptions" required color="purple"></date-picker>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <v-checkbox
                  v-model="data.is_finished"
                  label="is Finished?"
                  :value="data.is_finished"
                  color="purple"
                ></v-checkbox>
              </div>
            </div>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            v-if="edit"
            :disabled="!isValid"
            color="orange white--text"
            @click="saveOrder"
          >Save Order</v-btn>
          <v-btn v-else :disabled="!isValid" color="orange white--text" @click="addOrder">Add Order</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
// import "../../plugins/vuetify-datetime.js";
import "bootstrap/dist/css/bootstrap.css";
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
// import Datepicker from "vuejs-datepicker";
// Import required dependencies
// import "bootstrap/dist/css/bootstrap.css";

// Import this component
// import datePicker from "vue-bootstrap-datetimepicker";

// Import date picker css
// import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";

export default {
  props: [
    "order",
    "certificates",
    "edit",
    "users",
    "orders",
    "showOrderDialog",
  ],
  data() {
    return {
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v >= 15) || "Duration must be more than 15.",
      ],
      date: new Date(),
      time: "",
      dateoptions: {
        format: "YYYY-MM-DD",
        useCurrent: false,
        showTodayButton: true,
        showClose: true,
      },

      timeoptions: {
        format: "HH:mm:ss",
        useCurrent: true,
      },
    };
  },
  computed: {
    data: function () {
      if (this.order != null) {
        return this.order;
      }
      return {
        certificate_id: null,
        user_id: null,
        date: null,
        time: null,
        is_finished: null,
      };
    },
  },
  components: {
    datePicker,
  },
  methods: {
    getCertificate() {
      let id = this.data.certificate_id;
      //   alert(id);
      let url = `/api/certificates/${this.data.certificate_id}`;
      axios.get(url).then((response) => {
        this.data.certificate = response.data;
      });
    },
    getUser() {
      let id = this.data.user_id;
      //   alert(id);
      let url = `/api/users/${this.data.user_id}`;
      axios.get(url).then((response) => {
        this.data.user = response.data;
      });
    },
    close(event) {
      this.$emit("close", this.order);
    },
    saveOrder() {
      let certificate_id = this.data.certificate_id;
      let user_id = this.data.user_id;
      let time = this.data.time;
      let date = this.data.date;
      let is_finished = this.data.is_finished;
      axios
        .put(`/api/orders/${this.data.id}`, {
          certificate_id,
          user_id,
          date,
          time,
          is_finished,
        })
        .then((response) => {
          let index = this.orders.indexOf(this.order);
          this.orders[index] = this.order;
          this.$emit("close", this.order);
        });
    },
    addOrder(e) {
      let certificate_id = this.data.certificate_id;
      let user_id = this.data.user_id;
      let time = this.data.time;
      let date = this.data.date;
      let is_finished = this.data.is_finished;
      axios
        .post("/api/orders/adminstore", {
          certificate_id,
          user_id,
          date,
          time,
          is_finished,
        })
        .then((res) => {
          this.order.id = res.data.data.id;
          //   alert(JSON.stringify(res.data));
          this.order.created_at = res.data.data.created_at;
          //   alert(res.data.data.created_at);
          this.orders.unshift(this.order);
          this.$emit("close", this.order);
        });
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