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
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <!-- <template v-slot:activator="{ on, attrs }">
        <v-btn color="primary" dark v-bind="attrs" v-on="on">Open Dialog</v-btn>
      </template>-->
      <v-card>
        <v-toolbar dark color="orange">
          <v-btn icon dark @click="close" class="hovered-button-close">
            <v-icon class="close">mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Exams in {{data.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn dark @click="close" class="white orange--text ml-8 mr-2 hovered-button-close">Close</v-btn>
        </v-toolbar>
        <v-form v-model="isValid" class="ml-2">
          <div class="form-row">
            <div class="col-md-4">
              <v-select
                dense
                :items="exam_not_in_certificates"
                item-text="name"
                item-value="id"
                v-model="exam_id"
                label="Exam"
                outlined
                bottom
                autocomplete
                required
                :rules="requiredRules"
                color="purple"
              ></v-select>
            </div>
            <div class="col-md-4">
              <v-btn
                :disabled="!isValid"
                color="orange white--text"
                @click="joinExam"
                class="hovered-button"
              >Join Exam</v-btn>
            </div>
          </div>
        </v-form>

        <v-sheet class="mx-auto" max-width="auto">
          <v-slide-group
            v-if="exams.length>0"
            class="pa-4"
            :mandatory="true"
            :show-arrows="true"
            :center-active="true"
            prev-icon="mdi-arrow-left-thick mdi-48px"
            next-icon="mdi-arrow-right-thick mdi-48px"
          >
            <v-slide-item v-for="exam in exams " :key="exam.id" v-slot:default="{ active, toggle}">
              <v-col>
                <v-row class="mb-0" no-gutters>
                  <v-card
                    :color="active ? 'orange lighten-4' : 'white'"
                    rounded
                    class="ma-4"
                    height="300"
                    width="300"
                    @click="toggle"
                  >
                    <v-card-title v-if="active" class="black--text align-end">{{exam.name}}</v-card-title>
                    <v-card-title v-else>{{exam.name}}</v-card-title>
                    <v-card-text
                      v-if="active"
                      class="black--text align-end textover"
                    >{{exam.description}}</v-card-text>
                    <v-card-text v-else class="textover">{{exam.description}}</v-card-text>

                    <v-card-actions class="mb-0 p">
                      <v-spacer />

                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            fab
                            x-small
                            :color="active ? 'orange' : 'orange'"
                            dark
                            @click="showConfirmDialog(exam.id)"
                            v-bind="attrs"
                            v-on="on"
                            class="hovered-button-scale hovered-button"
                          >
                            <v-icon>mdi-delete</v-icon>
                          </v-btn>
                        </template>
                        Unjoin Exam
                      </v-tooltip>
                    </v-card-actions>
                  </v-card>
                </v-row>
              </v-col>
            </v-slide-item>
          </v-slide-group>
          <v-card v-if="exams.length==0 && examsLoaded" class="mx-auto" raised max-width="340px">
            <v-list-item three-line>
              <v-list-item-content>
                <div class="overline mb-4">{{data.name}}</div>
                <v-list-item-title class="headline mb-1">Has no exams yet</v-list-item-title>
                <v-list-item-subtitle>You can add a new question, or include a new question. if you add a new question you then need to add options ti it.</v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-avatar tile size="80">
                <v-icon x-large color="red darken-1">warning</v-icon>
              </v-list-item-avatar>
            </v-list-item>
          </v-card>
        </v-sheet>
      </v-card>
      <ConfirmDialog :confirmdialog="confirmdialog" @OK="OK" @Cancel="Cancel" />
    </v-dialog>
  </v-row>
</template>



<script>
import ConfirmDialog from "../appcore/ConfirmDialog";

export default {
  props: ["certificate", "dialog", "certificates"],
  data() {
    return {
      spinner: false,
      examsLoaded: false,
      confirmdialog: false,
      exam_id: null,
      exams: [],
      exam_not_in_certificates: [],
      exam: {
        id: "",
        exam_id: "",
        certificate_id: "",
      },
      certificate_id: "",
      edit: false,
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
    };
  },
  components: { ConfirmDialog },
  watch: {
    data: function (newValue, oldValue) {
      if (this.certificate != null) {
        let id = this.certificate.id;
        axios
          .post("/api/certificates/cexams/", { id })
          .then((response) => {
            this.exams = response.data;
            this.examsLoaded = true;
          })
          .catch((error) => {
            this.examsLoaded = true;
          });
        axios.post("/api/certificates/ncexams/", { id }).then((response) => {
          this.exam_not_in_certificates = response.data;
        });
      }
    },
  },
  beforeMount() {
    // axios.get("/api/exams/").then((response) => (this.exams = response.data));
  },
  computed: {
    data: function () {
      if (this.certificate != null) {
        return this.certificate;
      }
      return {};
    },
  },
  methods: {
    close(event) {
      this.examsLoaded = false;
      this.exams = [];
      this.$emit("close", this.certificate);
    },

    fetchExams() {
      let index = this.certificates.indexOf(this.certificate);
      if (this.certificate != null) {
        let id = this.certificate.id;
        axios
          .post("/api/certificates/cexams/", { id })
          .then((response) => {
            this.exams = response.data;
            this.certificates[index].exams = response.data;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
        axios.post("/api/certificates/ncexams/", { id }).then((response) => {
          this.exam_not_in_certificates = response.data;
        });
      }
    },
    showConfirmDialog(id) {
      this.exam_id = id;
      this.confirmdialog = true;
    },
    OK() {
      this.confirmdialog = false;
      this.unjoinExam(this.exam_id);
    },
    Cancel() {
      this.confirmdialog = false;
    },
    unjoinExam(id) {
      this.spinner = true;
      let certificate_id = this.certificate.id;
      let exam_id = id;
      axios
        .post("/api/certificates/unjoinexam/", {
          certificate_id,
          exam_id,
        })
        .then((response) => {
          this.fetchExams();
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
        });
    },
    joinExam(event) {
      this.spinner = true;
      let certificate_id = this.certificate.id;
      let exam_id = this.exam_id;
      axios
        .post("/api/certificates/joinexam/", {
          certificate_id,
          exam_id,
        })
        .then((response) => {
          this.exam_id = "";
          this.fetchExams();
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
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
.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 5; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
.hovered-button-scale:hover {
  transform: scale(1.5);
}
.hovered-button-close:hover {
  background-color: transparent !important;
  color: white !important;
  border: 1px solid white;
}
</style>