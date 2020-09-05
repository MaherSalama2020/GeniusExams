  <template>
  <v-row justify="center">
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="primary" dark>
          <v-card-text>
            Please stand by
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="closeQuestionExams">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Exams which include this question</v-toolbar-title>
        </v-toolbar>
        <h2>{{data.name}}</h2>
        <v-form ref="examForm" v-model="isValid" @keyup.native.enter="isValid && joinExam($event)">
          <div class="form-row">
            <div class="col-md-4">
              <v-select
                dense
                :items="question_not_in_exams"
                item-text="name"
                item-value="id"
                v-model="exam_id"
                label="Exam"
                outlined
                bottom
                autocomplete
                required
                :rules="requiredRules"
              ></v-select>
            </div>
            <div class="col-md-4">
              <v-text-field
                dense
                prepend-inner-icon="mdi-counter"
                type="number"
                label="Sequence of question in exam"
                v-model="enteredsequence"
                step="1"
                min="1"
                required
                :rules="numberRules"
                error-count="2"
                outlined
              />
            </div>
            <div class="col-md-4">
              <v-btn :disabled="!isValid" color="info" @click="joinExam">Join Question</v-btn>
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
                    :color="active ? 'cyan' : 'white'"
                    rounded
                    class="ma-4"
                    height="300"
                    width="300"
                    @click="toggle"
                  >
                    <v-card-title
                      v-if="active"
                      class="white--text align-end"
                    >{{exam.pivot.sequence}}</v-card-title>
                    <v-card-title v-else>{{exam.pivot.sequence}}</v-card-title>
                    <v-card-text v-if="active" class="white--text align-end textover">{{exam.name}}</v-card-text>
                    <v-card-text v-else class="textover">{{exam.name}}</v-card-text>

                    <v-card-actions class="mb-0 p">
                      <v-spacer />
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            icon
                            medium
                            :color="active ? 'white' : 'black'"
                            dark
                            @click="showConfirmDialog(exam.id)"
                            v-bind="attrs"
                            v-on="on"
                          >
                            <v-icon right>mdi-delete</v-icon>
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
                <v-list-item-title class="headline mb-1">Not included in any exam yet</v-list-item-title>
                <v-list-item-subtitle>You can include in exams question from dropdown list.</v-list-item-subtitle>
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
  props: ["question", "dialog", "questions"],
  data() {
    return {
      spinner: false,
      examsLoaded: false,
      confirmdialog: false,
      exam_id: null,
      exams: [],
      question_not_in_exams: [],
      enteredsequence: null,
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v >= 1) || "Sequence must be more than 1.",
      ],
    };
  },
  components: { ConfirmDialog },
  watch: {
    data: function (newValue, oldValue) {
      if (this.question != null) {
        let id = this.question.id;
        axios
          .post("/api/questions/qexams/", { id })
          .then((response) => {
            //   alert(JSON.stringify(response.data));
            this.exams = response.data;
            this.examsLoaded = true;
          })
          .catch((error) => {
            this.examsLoaded = true;
          });
        axios.post("/api/questions/nqexams/", { id }).then((response) => {
          //   alert(JSON.stringify(response.data));
          this.question_not_in_exams = response.data;
        });
      }
    },
  },
  // beforeMount() {
  //   axios
  //     .get("/api/exams/")
  //     .then((response) => (this.exams = response.data));
  // },
  computed: {
    data: function () {
      if (this.question != null) {
        return this.question;
      }
      return {
        name: "",
      };
    },
  },
  methods: {
    fetchExams() {
      let index = this.questions.indexOf(this.question);

      if (this.question != null) {
        let id = this.question.id;
        axios
          .post("/api/questions/qexams/", { id })
          .then((response) => {
            //   alert(JSON.stringify(response.data));
            this.exams = response.data;
            this.questions[index].exams = response.data;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
        axios.post("/api/questions/nqexams/", { id }).then((response) => {
          //   alert(JSON.stringify(response.data));
          this.question_not_in_exams = response.data;
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
      let question_id = this.question.id;
      let exam_id = id;
      axios
        .post("/api/questions/unjoinexam/", { question_id, exam_id })
        .then((response) => {
          this.fetchExams();
          this.$refs.examForm.reset();
        })
        .catch((error) => {
          this.spinner = false;
          console.log(error);
        });
    },
    joinExam() {
      this.spinner = true;
      let question_id = this.question.id;
      // let exam_id = event.target.value;
      let exam_id = this.exam_id;
      let sequence = this.enteredsequence;
      axios
        .post("/api/questions/joinexam/", { question_id, exam_id, sequence })
        .then((response) => {
          this.$refs.examForm.reset();
          this.selectedQuestion = "";
          this.enteredsequence = "";
          this.isValid = true;
          this.fetchExams();
        })
        .catch((error) => {
          this.spinner = fasle;
          console.log(error);
        });
    },
    // editExam(exam) {
    //   this.edit = true;
    //   this.option.id = option.id;
    //   this.option.option_id = option.id;
    //   this.option.name = option.name;
    //   this.option.sequence = option.sequence;
    //   this.option.isCorrect = option.isCorrect;
    //   this.option.explaination = option.explaination;
    // },
    closeQuestionExams() {
      this.examsLoaded = false;
      this.exams = [];
      this.$emit("close");
    },
  },
};
</script>