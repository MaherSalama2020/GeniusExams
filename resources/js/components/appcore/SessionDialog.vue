<template>
  <v-row justify="center">
    <v-dialog
      v-model="showSessionDialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar dark color="orange">
          <v-btn icon light @click="showConfirmDialog">
            <v-icon color="white" class="close">mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>"{{examName}}" {{type}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-title>{{currentStep}} of {{examQuestions.length}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-title v-if="type=='Exam'">Passing Rate {{passing_rate}}%</v-toolbar-title>
          <v-spacer v-if="type=='Exam'"></v-spacer>
          <v-toolbar-title>
            <Timer
              :duration="duration"
              @TimeIsUp="TimeIsUp"
              v-if="isLoading=='NO'&&showSessionDialog"
            />
          </v-toolbar-title>
          <!-- <v-toolbar-items>
            <v-btn dark text @click="closeSessionDialog">Save</v-btn>
          </v-toolbar-items>-->
        </v-toolbar>
        <v-stepper v-model="currentStep" non-linear alt-labels>
          <template>
            <!-- selectedOption:{{ selectedOption || 'null' }} -->
            <!-- <p>Exam Questions:{{ examQuestions || 'null' }}</p> -->
            <!-- <p>Current User ID:{{ user_id || 'null' }}</p> -->
            <!-- <p>Current User Name:{{ user_name || 'null' }}</p> -->
            <!-- <p>Current User Email:{{ user_email || 'null' }}</p> -->
            <!-- <p>Current User isAdmin?:{{ user_type }}</p> -->
            <!-- <p>Current Certificate ID:{{ certificate_id || 'null' }}</p> -->
            <!-- <p>Current Exam ID:{{ exam_id || 'null' }}</p> -->
            <!-- <p>Current Question ID:{{ currentQuestionID || 'null' }}</p> -->
            <!-- <p>Current Option ID:{{ currentOptionID || 'null' }}</p> -->
            <!-- <p>is it answered correctly:{{ answeredCorrectly || 'null' }}</p> -->
            <!-- <p>Answers Explaination:{{ answersExplaination || 'null' }}</p> -->
            <!-- <p>Marked Answers:{{ markedAnswers || 'null' }}</p> -->
            <!-- <p>Marked Steps:{{ markedSteps || 'null' }}</p> -->
            <!-- <p>Answers:{{ answers || 'null' }}</p> -->
            <!-- <v-stepper-header>
              <template v-for="(question, index) in examQuestions">
                <v-stepper-step
                  :key="`${index+1}-step`"
                  :complete="currentStep > index+1"
                  :step="index+1"
                  color="orange"
                  editable
                  edit-icon="check"
                >Step {{ index+1 }}</v-stepper-step>
                <v-divider v-if="index+1 !== examQuestions.length" :key="`${index+1}-divider`"></v-divider>
              </template>
            </v-stepper-header>-->
            <v-pagination
              light
              color="orange"
              v-model="currentStep"
              circle
              :length="examQuestions.length"
              total-visible="10"
              @input="handlePagination"
              :disabled="disablePagination"
            ></v-pagination>
            <v-stepper-items>
              <v-stepper-content
                v-for="(question, index) in examQuestions"
                :key="`${index+1}-content`"
                :step="index+1"
              >
                <v-card class="mb-12" color="orange lighten-5" height="auto">
                  <v-card-title>{{question.name}}</v-card-title>
                  <v-img
                    v-if="question.image"
                    max-height="500"
                    contain
                    :aspect-ratio="16/9"
                    :src="question.image"
                  ></v-img>
                  <v-radio-group
                    column
                    height="2vh"
                    v-model="selectedOption"
                    :mandatory="false"
                    v-for="option in question.options"
                    :key="option.id"
                    @change="checkSeclecedOption(option)"
                  >
                    <span>
                      <v-card-title>
                        <v-radio :value="option" name="selectedOption" color="orange">
                          <template v-slot:label>
                            <div>
                              <v-hover v-slot:default="{ hover }">
                                <span
                                  v-bind:class="{ 'success--text':option.isCorrect&&alertShowCorrectAnswer, 'error--text':!option.isCorrect&&alertShowCorrectAnswer&&selectedOption.id==option.id }"
                                >
                                  <!-- <strong class="info--text">{{optionsNames[index]}}.{{option.id}}.</strong> -->
                                  <strong>
                                    {{optionsNames[option.sequence-1]}}.
                                    {{option.name}}
                                  </strong>
                                  <v-icon
                                    v-if="option.isCorrect&&alertShowCorrectAnswer"
                                    color="success"
                                  >mdi-check-circle</v-icon>
                                  <v-icon
                                    v-if="!option.isCorrect&&alertShowCorrectAnswer&&selectedOption.id==option.id"
                                    color="error"
                                  >mdi-alert-circle</v-icon>
                                  <div
                                    v-if="hover && option.image"
                                    class="d-flex v-card--reveal display-3 white--text"
                                    style=" position: absolute; left: 50px; bottom: 15px;  1px;  width: 700px;"
                                  >
                                    <v-img
                                      max-height="500"
                                      contain
                                      :aspect-ratio="16/9"
                                      :src="option.image"
                                    ></v-img>
                                  </div>
                                </span>
                              </v-hover>
                            </div>
                          </template>
                        </v-radio>
                      </v-card-title>
                    </span>
                  </v-radio-group>
                </v-card>
                <!-- if type is pratical -->
                <v-row>
                  <v-tooltip top v-if="!arrivetostart">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        color="orange white--text"
                        @click="prevStep(index+1)"
                        class="mr-1 ml-5"
                        v-bind="attrs"
                        v-on="on"
                      >Previous</v-btn>
                    </template>
                    Previous question
                  </v-tooltip>
                  <v-tooltip top v-else>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        color="orange white--text"
                        disabled
                        @click="prevStep(index+1)"
                        class="mr-1 ml-5"
                        v-bind="attrs"
                        v-on="on"
                      >Previous</v-btn>
                    </template>
                    Previous question
                  </v-tooltip>
                  <v-tooltip top v-if="!arrivetoend">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        color="orange white--text"
                        @click="nextStep(index+1)"
                        class="mr-1 ml-5"
                        v-bind="attrs"
                        v-on="on"
                      >Next</v-btn>
                    </template>
                    Next question
                  </v-tooltip>
                  <v-tooltip top v-else>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        color="orange white--text"
                        disabled
                        @click="nextStep(index+1)"
                        class="mr-1 ml-5"
                        v-bind="attrs"
                        v-on="on"
                      >Next</v-btn>
                    </template>
                    Next question
                  </v-tooltip>
                </v-row>
                <v-row>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        @click="showCorrectAnswer"
                        v-if="type=='Practical Test'"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        Show Correct Answer
                        <v-icon v-if="!alertShowCorrectAnswer" color="grey" class="ml-2">mdi-eye-off</v-icon>
                        <v-icon v-else class="ml-2">mdi-eye</v-icon>
                      </v-btn>
                    </template>
                    Show Correct Answer
                  </v-tooltip>
                  <v-tooltip bottom v-if="!ViewMarkedAnswers && !alertShowMark">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        @click="markAnswer"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        Mark Question
                        <v-icon color="grey" class="ml-2">mdi-star</v-icon>
                      </v-btn>
                    </template>
                    Mark Question
                  </v-tooltip>
                  <v-tooltip bottom v-if="!ViewMarkedAnswers && alertShowMark">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        @click="markAnswer"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        Unmark Question
                        <v-icon class="ml-2">mdi-star</v-icon>
                      </v-btn>
                    </template>
                    Unmark Question
                  </v-tooltip>
                  <v-tooltip
                    bottom
                    v-if="ViewMarkedAnswers&&arrivetoend && answers.filter(function (answer) {
                      return answer.alertShowMark == true;
                    }).length>0"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        small
                        v-bind="attrs"
                        v-on="on"
                        @click="viewMarkedAnswers"
                      >
                        Return to start
                        <v-icon class="ml-2">replay</v-icon>
                      </v-btn>
                    </template>
                    Return to start
                  </v-tooltip>
                  <v-tooltip
                    bottom
                    v-if="!ViewMarkedAnswers&&arrivetoend && answers.filter(function (answer) {
                      return answer.alertShowMark == true;
                    }).length>0"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-2 mt-2 mb-2 text-center"
                        color="orange white--text"
                        small
                        v-bind="attrs"
                        v-on="on"
                        @click="viewMarkedAnswers"
                      >
                        View marked answers
                        <v-icon class="ml-2">fast_rewind</v-icon>
                      </v-btn>
                    </template>
                    View marked answers
                  </v-tooltip>
                  <v-spacer />
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mr-5 mt-2"
                        tile
                        outlined
                        color="orange"
                        @click="showPreviewDialog"
                        v-bind="attrs"
                        v-on="on"
                      >
                        Review
                        <v-icon right>preview</v-icon>
                      </v-btn>
                    </template>
                    Review your Answers
                  </v-tooltip>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mr-5 mt-2"
                        tile
                        outlined
                        color="orange"
                        @click="showSubmitDialog"
                        v-bind="attrs"
                        v-on="on"
                      >
                        Submit
                        <v-icon right>mdi-send</v-icon>
                      </v-btn>
                    </template>
                    Submit your Answers
                  </v-tooltip>
                </v-row>
              </v-stepper-content>
            </v-stepper-items>
          </template>
        </v-stepper>
        <v-alert
          v-if="alertShowCorrectAnswer"
          :value="true"
          color="green accent-1"
          icon="warning"
        >{{showCorrectAnswerOptionName}}. {{showCorrectAnswerExplaination}}</v-alert>

        <TimeUpDialog
          :showTimeUpDialog="showTimeUpDialog"
          @closeTimeUpDialog="closeTimeUpDialog"
          title="Time is up!"
          :type="type"
          :passing_rate="passing_rate"
          :text1="`Total questions: ${examQuestions.length}`"
          :text2="`Marked questions:${markedNO}`"
          :text3="`Correct answers:${correctNO}`"
          :text4="`Wrong answers:${wrongNO}`"
          :result="(correctNO/examQuestions.length)*100"
        />
        <SubmitDialog
          :optionChanged="optionChanged"
          :alertSubmitDialog="alertSubmitDialog"
          @closeSubmitDialog="closeSubmitDialog"
          @submit="submit"
          :examQuestions="examQuestions"
          :answers="answers"
          :certificate_id="certificate_id"
          :exam_id="exam_id"
          :result="(correctNO/examQuestions.length)*100"
        />
        <ResultDialog
          :alertResultDialog="alertResultDialog"
          @closeResultDialog="closeResultDialog"
          :result="(correctNO/examQuestions.length)*100"
          :passing_rate="passing_rate"
        />
        <PreviewDialog
          :optionChanged="optionChanged"
          :alertPreviewDialog="alertPreviewDialog"
          @closePreviewDialog="closePreviewDialog"
          :examQuestions="examQuestions"
          :answers="answers"
          :certificate_id="certificate_id"
          :exam_id="exam_id"
        />
        <ConfirmDialog
          :confirmdialog="confirmdialog"
          @OK="OK"
          @Cancel="Cancel"
          control="closeSession"
        />
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
import Timer from "./Timer";
import TimeUpDialog from "./TimeUpDialog";
import SubmitDialog from "./SubmitDialog";
import PreviewDialog from "./PreviewDialog";
import ConfirmDialog from "./ConfirmDialog";
import ResultDialog from "./ResultDialog";
export default {
  props: [
    "certificate_id",
    "exam_id",
    "type",
    "passing_rate",
    "isLoading",
    "showSessionDialog",
    "examQuestions",
    "examName",
    "duration",
  ],
  data() {
    return {
      notifications: false,
      sound: true,
      widgets: false,
      user_id: 0,
      user_name: "",
      user_email: "",
      user_type: -1,
      arrivetostart: true,
      arrivetoend: false,
      markedSteps: [],
      showTimeUpDialog: false,
      alertShowCorrectAnswer: false,
      alertShowMark: false,
      showCorrectAnswerExplaination: "",
      showCorrectAnswerOptionName: "",
      selectedOption: {},
      currentStep: 0,
      wrongNO: 0,
      correctNO: 0,
      markedNO: 0,
      optionsNames: ["A", "B", "C", "D", "E", "F"],
      answers: [],
      markedAnswers: [],
      answersExplaination: [],
      currentQuestionID: 0,
      ViewMarkedAnswers: false,
      clicksAfterRewind: 0,
      alertSubmitDialog: false,
      alertResultDialog: false,
      alertPreviewDialog: false,
      optionChanged: false,
      confirmdialog: false,
      disablePagination: false,
    };
  },
  components: {
    Timer,
    TimeUpDialog,
    SubmitDialog,
    PreviewDialog,
    ConfirmDialog,
    ResultDialog,
  },
  mounted() {
    this.getUser();
    // alert();
    // if (this.examQuestions) this.currentQuestionID = this.examQuestions[0].id;
  },
  watch: {
    isLoading: {
      handler() {
        if (this.isLoading == "NO")
          this.currentQuestionID = this.examQuestions[0].id;
        this.resetExam();
      },
    },
    // firstQuestionID: {
    //   handler() {

    //   },
    // },
  },
  methods: {
    getUser() {
      let user = JSON.parse(localStorage.getItem("genius.user"));
      this.user_id = user.id;
      this.user_name = user.name;
      this.user_email = user.email;
      this.user_type = user.is_admin;
    },
    nextStep(n) {
      this.arrivetostart = false;
      if (!this.ViewMarkedAnswers) {
        this.currentStep = n + 1;
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (n === this.examQuestions.length - 1) {
          this.arrivetoend = true;
        }
      } else {
        // alert(this.clicksAfterRewind);
        this.currentStep = n + this.markedSteps[this.clicksAfterRewind];
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (this.clicksAfterRewind === this.markedSteps.length - 1) {
          this.arrivetoend = true;
        }
        this.clicksAfterRewind++;
      }
      this.resetQuestion();
    },
    prevStep(n) {
      this.arrivetoend = false;
      if (!this.ViewMarkedAnswers) {
        this.currentStep = n - 1;
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (n === 2) {
          this.arrivetostart = true;
        }
      } else {
        this.clicksAfterRewind--;
        // alert(this.clicksAfterRewind);
        this.currentStep = n - this.markedSteps[this.clicksAfterRewind];
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (n === this.markedAnswers[1].currentStep) {
          this.arrivetostart = true;
        }
      }

      this.resetQuestion();
    },

    showCorrectAnswer() {
      this.alertShowCorrectAnswer = !this.alertShowCorrectAnswer;
      let options = this.examQuestions[this.currentStep - 1].options;
      options.forEach((o) => {
        if (o.isCorrect) {
          this.showCorrectAnswerOptionName = this.optionsNames[o.sequence - 1];
          this.showCorrectAnswerExplaination = o.explaination;
        }
      });
      let answerExplaination = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
        showCorrectAnswerOptionName: this.showCorrectAnswerOptionName,
        showCorrectAnswerExplaination: this.showCorrectAnswerExplaination,
      };
      this.answersExplaination[this.currentStep - 1] = answerExplaination;
    },
    resetQuestion() {
      this.alertShowCorrectAnswer = false;
      // this.showCorrectAnswer = false;
      this.showCorrectAnswerOptionName = "";
      this.showCorrectAnswerExplaination = "";
      if (typeof this.answers[this.currentStep - 1] === "undefined")
        this.selectedOption = {};
      else
        this.selectedOption = this.answers[this.currentStep - 1].selectedOption;
      //to get the marked question
      if (typeof this.answers[this.currentStep - 1] === "undefined")
        this.alertShowMark = false;
      else
        this.alertShowMark = this.answers[this.currentStep - 1].alertShowMark;
      // to get the state of explaination
      if (
        typeof this.answersExplaination[this.currentStep - 1] === "undefined"
      ) {
        this.alertShowCorrectAnswer = false;
      } else {
        this.alertShowCorrectAnswer = this.answersExplaination[
          this.currentStep - 1
        ].alertShowCorrectAnswer;
        this.showCorrectAnswerOptionName = this.answersExplaination[
          this.currentStep - 1
        ].showCorrectAnswerOptionName;
        this.showCorrectAnswerExplaination = this.answersExplaination[
          this.currentStep - 1
        ].showCorrectAnswerExplaination;
      }
    },
    TimeIsUp() {
      this.showTimeUpDialog = true;
    },
    resetExam() {
      this.currentStep = 1;
      this.wrongNO = 0;
      this.correctNO = 0;
      this.markedNO = 0;
      this.showTimeUpDialog = false;
      this.arrivetoend = false;
      this.arrivetostart = true;
      this.alertShowMark = false;
      this.answersExplaination = [];
      this.answers = [];
      this.selectedOption = {};
      this.alertShowCorrectAnswer = false;
      this.alertShowMark = false;
      // this.$emit("closeSessionDialog");
    },
    checkSeclecedOption(option) {
      this.optionChanged = !this.optionChanged;
      let answer = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
      };
      this.answers[this.currentStep - 1] = answer;
      let correct = 0;
      let wrong = 0;
      let marked = 0;
      this.answers.forEach((ans) => {
        if (ans.selectedOption.isCorrect) correct = correct + 1;
        if (!ans.selectedOption.isCorrect) wrong = wrong + 1;
        if (ans.alertShowMark) marked = marked + 1;
      });
      this.correctNO = correct;
      this.wrongNO = wrong;
      this.markedNO = marked;
      // if (
      //   this.answers.some((ans) => ans.question_id === answer.question_id) &&
      //   this.selectedOption.isCorrect
      // )
    },
    markAnswer(n) {
      this.alertShowMark = !this.alertShowMark;
      let answerMarked = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
      };
      this.answers[this.currentStep - 1] = answerMarked;
      // alert(JSON.stringify(this.answers[this.currentStep - 1]));
    },
    closeTimeUpDialog() {
      if (this.alertPreviewDialog) this.alertPreviewDialog = false;
      if (this.alertSubmitDialog) this.alertSubmitDialog = false;
      if (this.alertResultDialog) this.alertResultDialog = false;
      if (this.confirmdialog) this.confirmdialog = false;
      this.submit();
      this.showTimeUpDialog = false;
      this.resetExam();
      this.closeSessionDialog();
    },
    showSubmitDialog() {
      this.alertSubmitDialog = true;
    },
    closeSubmitDialog() {
      this.alertSubmitDialog = false;
      // this.$emit("closeSessionDialog");
    },
    showResultDialog() {
      this.alertResultDialog = true;
    },
    closeResultDialog() {
      this.alertResultDialog = false;
      this.closeSessionDialog();
    },
    showPreviewDialog() {
      this.alertPreviewDialog = true;
    },
    closePreviewDialog() {
      this.alertPreviewDialog = false;
      // this.$emit("closeSessionDialog");
    },
    closeSessionDialog() {
      this.resetExam();
      this.$emit("closeSessionDialog");
    },
    submit() {
      this.alertSubmitDialog = false;
      let user_id = this.user_id;
      let exam_id = this.exam_id;
      let certificate_id = this.certificate_id;
      let result = (this.correctNO / this.examQuestions.length) * 100;
      let answers = JSON.stringify(this.answers);

      axios
        .post("/api/sessions/", { user_id, certificate_id, exam_id, result })
        .then((response) => {
          let session_id = response.data.data.id;
          // alert(session_id);
          axios
            .post("/api/answers/", { answers, session_id })
            .then((res) => {
              this.alertSubmitDialog = false;
              this.alertResultDialog = true;
            })
            .catch((error) => console.log(error));
        })
        .catch((error) => console.log(error));
    },
    // preview() {
    //   this.alertpreviewDialog = false;
    // },
    viewMarkedAnswers() {
      this.ViewMarkedAnswers = !this.ViewMarkedAnswers;
      this.disablePagination = this.ViewMarkedAnswers;
      // alert(this.ViewMarkedAnswers);
      if (this.ViewMarkedAnswers) {
        this.markedAnswers = this.answers.filter(function (answer) {
          return answer.alertShowMark == true;
        });
        this.markedSteps = this.markedAnswers.map(
          (currentAnswer, index, array) => {
            // alert(index);
            // alert(JSON.stringify(array[index]));
            if (typeof array[index + 1] != "undefined")
              return array[index + 1].currentStep - array[index].currentStep;
          }
        );
        //to remove null entry
        this.markedSteps.pop();
        // alert(JSON.stringify(this.markedSteps));
        // alert(this.markedAnswers[0].currentStep);
        this.currentStep = this.markedAnswers[0].currentStep;
        this.selectedOption = this.markedAnswers[0].selectedOption;
        this.currentQuestionID = this.markedAnswers[0].question_id;
        if (this.markedAnswers.length > 1) {
          this.arrivetoend = false;
          this.arrivetostart = true;
        } else {
          this.arrivetoend = true;
          this.arrivetostart = true;
        }
      } else {
        this.alertShowMark = false;
        this.currentStep = 1;
        if (this.answers[0]) {
          this.alertShowMark = this.answers[0].alertShowMark;
          this.selectedOption = this.answers[0].selectedOption;
        } else {
          this.alertShowMark = false;
          this.selectedOption = null;
        }
        // this.markedAnswers = [];
        // this.answers.forEach((answer) => {
        //   answer.alertShowMark = false;
        // });
        this.currentQuestionID = this.examQuestions[0].id;
        this.arrivetostart = true;
        this.arrivetoend = false;
        this.clicksAfterRewind = 0;
      }
    },
    showConfirmDialog() {
      this.confirmdialog = true;
    },
    OK() {
      this.confirmdialog = false;
      this.closeSessionDialog();
    },
    Cancel() {
      this.confirmdialog = false;
    },
    handlePagination() {
      if (this.currentStep == 1 && this.examQuestions.length > 1) {
        this.arrivetostart = true;
        this.arrivetoend = false;
      } else if (this.currentStep == 1 && this.examQuestions.length == 1) {
        this.arrivetostart = true;
        this.arrivetoend = true;
      } else if (this.currentStep == this.examQuestions.length) {
        this.arrivetostart = false;
        this.arrivetoend = true;
      } else {
        this.arrivetostart = false;
        this.arrivetoend = false;
      }
      if (this.answers[this.currentStep - 1] == null)
        this.alertShowMark = false;
      else if (
        this.answers[this.currentStep - 1] != null &&
        this.answers[this.currentStep - 1].alertShowMark
      )
        this.alertShowMark = true;
      else if (
        this.answers[this.currentStep - 1] != null &&
        !this.answers[this.currentStep - 1].alertShowMark
      )
        this.alertShowMark = false;
    },
  },
};
</script>
<style scoped>
.close:hover {
  color: white;
  cursor: pointer;
  transform: rotate(90deg);
}
</style>