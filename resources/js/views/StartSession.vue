<template>
  <v-container fluid>
    <!-- <p>SelectedExam:{{ selectedExam || 'null' }}</p> -->
    <!-- <p>SelectedExamName:{{ selectedExamName || 'null' }}</p> -->
    <!-- <p>Duration:{{ duration || 'null' }}</p> -->
    <!-- <p>isLoading:{{ isLoading || 'null' }}</p> -->
    <!-- <p>ExamQuestions:{{ examQuestions || 'null' }}</p> -->
    <!-- <p>CertificateExams:{{ exams || 'null' }}</p> -->
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
    <template>
      <v-sheet class="mx-auto" max-width="auto">
        <v-slide-group
          v-if="exams.length>0"
          class="pa-1"
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
                  :color="active ? 'orange lighten-5' : 'white'"
                  rounded
                  class="ma-1"
                  height="175"
                  width="300"
                  @click="toggle"
                >
                  <v-card-title>
                    <v-chip :color="getTypeColor(exam.type)" dark>{{ exam.type}}</v-chip>
                    <v-chip
                      color="orange"
                      dark
                      v-if="exam.passing_rate"
                      class="ml-1"
                    >Passing rate: {{ exam.passing_rate}}%</v-chip>
                  </v-card-title>
                  <!-- <v-card-text v-if="active" class="white--text align-end textover">{{exam.name}}</v-card-text> -->
                  <v-card-text class="textover">{{exam.name}}</v-card-text>
                  <!-- <v-card-subtitle
                    v-if="active"
                    class="white--text align-end"
                  >Passing rate: {{exam.passing_rate}} %</v-card-subtitle>-->

                  <v-card-actions class="mb-0 mt-0">
                    <v-spacer />
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          fab
                          small
                          :color="active ? 'orange' : 'orange'"
                          dark
                          @click="ExamSelected(exam.id)"
                          v-bind="attrs"
                          v-on="on"
                        >
                          <v-icon class="arrow">double_arrow</v-icon>
                        </v-btn>
                      </template>
                      Start Session
                    </v-tooltip>
                  </v-card-actions>
                </v-card>
              </v-row>
            </v-col>
          </v-slide-item>
        </v-slide-group>
      </v-sheet>
      <!-- <v-container fluid>
        <v-radio-group
          column
          height="1vh"
          @change="ExamSelected(exam.name)"
          v-model="selectedExam"
          v-for="exam in exams"
          :key="exam.id"
        >
          <v-radio :value="exam.id">
            <template v-slot:label>
              <div class="text-xs-right">
                {{exam.name}}
                <v-chip :color="getTypeColor(exam.type)" dark>{{ exam.type}}</v-chip>
                <span v-if="exam.type=='Exam'">
                  Passing Rate:
                  <v-chip color="orange" dark>{{ exam.passing_rate}}%</v-chip>
                </span>
              </div>
            </template>
          </v-radio>
        </v-radio-group>
      </v-container>-->
    </template>
    <!-- for exam session done -->
    <template>
      <v-card flat>
        <v-card-title>
          <v-btn @click="all" v-if="panel.length==0&&sessions.length>0">all</v-btn>
          <v-btn @click="none" v-if="panel.length>0&&sessions.length>0">none</v-btn>
        </v-card-title>
        <v-card-text>
          <v-expansion-panels ocusable color="warning" v-model="panel" multiple popout>
            <v-expansion-panel v-for="session in sessions" :key="session.id">
              <v-expansion-panel-header disable-icon-rotate>
                "{{session.exam.name}}" {{session.exam.type}}
                <v-spacer />
                {{new Date(session.created_at).toLocaleDateString()}}
                <template
                  v-slot:actions
                  v-if="session.exam.type=='Exam'"
                >
                  <v-icon
                    color="teal"
                    v-if="session.result>=session.exam.passing_rate && session.exam.type=='Exam'"
                  >mdi-check</v-icon>
                  <v-icon
                    color="error"
                    v-if="session.result<session.exam.passing_rate && session.exam.type=='Exam'"
                  >mdi-alert-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <!-- <v-chip :color="getTypeColor(session.exam.type)" dark>{{ session.exam.type}}</v-chip> -->
                <span>
                  Result:
                  <v-chip color="orange" dark>{{session.result}}%</v-chip>
                </span>
                <span v-if="session.exam.type=='Exam'">
                  Passing Rate:
                  <v-chip color="orange" dark>{{session.exam.passing_rate}}%</v-chip>
                </span>
                <SessionAnswers
                  :answers="session.answers"
                  :certificate_id="session.certificate_id"
                  :exam_id="session.exam_id"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-card>
    </template>
    <SessionDialog
      :certificate_id="certificate_id"
      :exam_id="selectedExam"
      :type="type"
      :passing_rate="passing_rate"
      :isLoading="isLoading"
      :showSessionDialog="showSessionDialog"
      :examQuestions="examQuestions"
      :examName="selectedExamName"
      :duration="duration"
      @closeSessionDialog="closeSessionDialog"
    />
  </v-container>
</template>
<script>
import SessionDialog from "../components/appcore/SessionDialog";
import SessionAnswers from "../components/appcore/SessionAnswers";
export default {
  data() {
    return {
      panel: [],
      sessions: [],
      exams: [],
      examQuestions: [],
      selectedExam: 0,
      selectedExamName: "",
      isLoading: "",
      spinner: false,
      showSessionDialog: false,
      duration: -1,
      type: "",
      passing_rate: "",
      certificate_id: 0,
    };
  },
  components: { SessionDialog, SessionAnswers },
  beforeMount() {
    let id = this.$route.params.id;
    let certificate_id = id;
    this.certificate_id = id;
    axios.post("/api/certificates/cexams/", { id }).then((response) => {
      this.exams = response.data;
    });
    axios
      .post("/api/sessions/csessions/", { certificate_id })
      .then((response) => {
        this.sessions = response.data;
      });
  },
  // watch: {
  //   selectedExam: {
  //     handler() {

  //     },
  //   },
  // },

  methods: {
    fetchSessions() {
      let certificate_id = this.certificate_id;

      axios
        .post("/api/sessions/csessions/", { certificate_id })
        .then((response) => {
          this.sessions = response.data;
        });
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    resetExam() {},
    ExamSelected(id) {
      this.selectedExam = id;
      // alert(id);
      this.isLoading = "YES";
      this.spinner = true;
      this.exams.forEach((e) => {
        if (e.id == this.selectedExam) {
          this.duration = e.duration;
          this.type = e.type;
          this.passing_rate = e.passing_rate;
          this.selectedExamName = e.name;
        }
      });
      axios
        .post("/api/exams/equestions", { id })
        .then((response) => {
          this.isLoading = "NO";
          this.spinner = false;
          this.examQuestions = response.data;
          this.showSessionDialog = true;
        })
        .catch((err) => {
          this.isLoading = "NO";
          this.spinner = false;
        });
    },
    closeSessionDialog() {
      this.fetchSessions();
      // this.examQuestions = [];
      // this.selectedExam = 0;
      // this.selectedExamName = "";
      // this.examQuestions = [];
      this.isLoading = "NO";
      this.spinner = false;
      this.duration = -1;
      this.showSessionDialog = false;
    },
    // Create an array the length of our items
    // with all values as true
    all() {
      this.panel = Object.keys(this.sessions).map((k, i) => i);
    },
    // Reset the panel
    none() {
      this.panel = [];
    },
  },
};
</script>
<style scoped>
.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 5; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.arrow:hover {
  animation: moveRightToLeft -2s 900ms infinite linear;
}
@keyframes moveRightToLeft {
  0% {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(4px, 0);
  }
  50.1% {
    transform: translate(-2px, 0);
  }
  100% {
    transform: translate(4px, 0);
  }
}
</style>