<template>
  <v-row justify="center">
    <v-dialog v-model="showExamDialog" width="600px">
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Edit Exam</span>
          <span v-else class="headline">Add Exam</span>
          <v-spacer />
          <span @click="close" class="close">
            <v-icon x-large>close</v-icon>
          </span>
        </v-card-title>
        <hr class="hr mt-0" />
        <div v-if="edit" class="col-md-12 row">
          <h4>
            <v-icon medium color="cyan">help</v-icon>
            {{data.name}}
          </h4>
        </div>
        <v-card-text>
          <v-form v-model="isValid" ref="examForm">
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
                  @input="data.passing_rate=null"
                ></v-select>
              </div>
              <div class="col-md-3" v-if="data.type=='Exam'">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-percent"
                  type="number"
                  label="Passing Rate"
                  v-model="data.passing_rate"
                  required
                  :rules="rateRules"
                  error-count="2"
                  outlined
                />
              </div>
            </div>
            <div>
              <v-text-field
                dense
                prepend-inner-icon="mdi-pencil"
                label="Exam Name"
                class="col-md-6"
                v-model="data.name"
                :rules="requiredRules"
                error-count="2"
                required
                outlined
              />
              <v-text-field
                dense
                prepend-inner-icon="mdi-counter"
                type="number"
                label="Duration"
                class="col-md-6"
                v-model="data.duration"
                step="15"
                required
                :rules="durationRules"
                error-count="2"
                outlined
              />
            </div>
            <v-textarea
              dense
              label="Description"
              v-model="data.description"
              :rules="requiredRules"
              error-count="2"
              required
              outlined
              no-resize
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn v-if="edit" :disabled="!isValid" color="info" @click="saveExam">Save Exam</v-btn>
          <v-btn v-else :disabled="!isValid" color="info" @click="addExam">Add Exam</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
  <!-- <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-body">
          <slot name="body">
            <div class="mb-0">
              <div class="col-md-11">
                <h3 v-if="edit">Edit Exam</h3>
                <h3 v-else>New Exam</h3>
              </div>
              <div class="close p-3">
                <span @click="close">
                  <v-icon x-large>close</v-icon>
                </span>
              </div>
            </div>
            <hr class="hr mt-0" />
            <div v-if="edit" class="col-md-4 row">
              <v-icon medium color="cyan">help</v-icon>
              <h4>{{data.name}}</h4>
            </div>
         
          </slot>
        </div>
      </div>
    </div>
  </div>-->
</template>

<style scoped>
.close {
  font-size: 2rem;
}
.close:hover {
  color: crimson;
  cursor: pointer;
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

<script>
// import Datepicker from "vuejs-datepicker";
// Import required dependencies
import "bootstrap/dist/css/bootstrap.css";

// Import this component
import datePicker from "vue-bootstrap-datetimepicker";

// Import date picker css
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";

export default {
  components: {
    // Datepicker,
    datePicker,
  },
  props: ["exam", "certificates", "edit", "exams", "showExamDialog"],
  data() {
    return {
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      durationRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v >= 15) || "Duration must be more than 15.",
      ],
      rateRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v > 0) || "Rate must be more than 0.",
        (v) => (v && v <= 100) || "Rate must be less than 100.",
      ],
      date: new Date(),
      dateoptions: {
        format: "YYYY-MM-DD",
        useCurrent: false,
        showTodayButton: true,
        showClose: true,
      },

      starttimeoptions: {
        format: "HH:mm:ss",
        useCurrent: true,
      },
      endtimeoptions: {
        format: "HH:mm:ss",
        disabledTimeIntervals: [
          [moment({ h: 0 }), moment({ h: this.selectedStartHour })],
        ],
        useCurrent: true,
      },
      imagechanged: null,
      attachment: null,
      types: [
        { text: "Exam", value: "Exam" },
        { text: "Practical Test", value: "Practical Test" },
      ],
    };
  },
  computed: {
    // selectedStartHour: function () {
    //   return parseInt(moment(this.data.startTime, "HH").format("HH"));
    // },
    data: function () {
      if (this.exam != null) {
        return this.exam;
      }
      return {
        certificate_id: "",
        name: "",
        type: "",
        passing_rate: "",
        duration: "",
        description: "",
      };
    },
  },
  methods: {
    // changeDateFormat(date) {
    //   var d = new Date(date),
    //     month = "" + (d.getMonth() + 1),
    //     day = "" + d.getDate(),
    //     year = d.getFullYear();

    //   if (month.length < 2) month = "0" + month;
    //   if (day.length < 2) day = "0" + day;

    //   this.data.date = [year, month, day].join("-");
    // },
    // calculateDuration() {
    //   var diff = moment
    //     .utc(
    //       moment(this.data.endTime, "HH:mm:ss").diff(
    //         moment(this.data.startTime, "HH:mm:ss")
    //       )
    //     )
    //     .format("HH:mm:ss");
    //   var diff_parts = diff.split(":");
    //   var mins = parseInt(diff_parts[0]) * 60 + parseInt(diff_parts[1]);
    //   this.data.duration = mins;
    // },
    getCertificate() {
      let id = this.data.certificate_id;
      // alert(id);
      let url = `/api/certificates/${this.data.certificate_id}`;
      axios.get(url).then((response) => {
        this.data.certificate = response.data;
      });
    },
    close(event) {
      this.$emit("close", this.exam);
    },
    saveExam() {
      let certificate_id = this.data.certificate_id;
      // alert(certificate_id);
      let name = this.data.name;
      let type = this.data.type;
      let passing_rate = this.data.passing_rate;
      let duration = this.data.duration;
      let description = this.data.description;
      axios
        .put(`/api/exams/${this.data.id}`, {
          certificate_id,
          name,
          type,
          passing_rate,
          duration,
          description,
        })
        .then((response) => {
          let index = this.exams.indexOf(this.exam);
          this.exams[index] = this.exam;
          // this.$refs.examForm.reset();
          this.$emit("close", this.exam);
        });
    },
    addExam(e) {
      // alert(JSON.stringify(this.exam));
      let certificate_id = this.data.certificate_id;
      let name = this.data.name;
      let type = this.data.type;
      let passing_rate = this.data.passing_rate;
      let duration = this.data.duration;
      let description = this.data.description;

      axios
        .post("/api/exams/", {
          certificate_id,
          name,
          type,
          passing_rate,
          duration,
          description,
        })
        .then((res) => {
          this.exam.id = res.data.id;
          this.exam.questions = [];
          this.exams.unshift(this.exam);
          // this.$refs.examForm.reset();
          this.$emit("close", this.exam);
        });
    },
  },
};
</script>