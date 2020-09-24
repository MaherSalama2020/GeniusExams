<template>
  <v-row justify="center">
    <v-dialog
      v-model="showSessionDialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
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
      <v-snackbar v-model="snackbar">
        Marked Question, you need to select the question number which you want to review
        <template
          v-slot:action="{ attrs }"
        >
          <v-btn color="orange" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
        </template>
      </v-snackbar>
      <v-card>
        <v-toolbar dark color="orange">
          <v-btn icon light @click="showConfirmDialog" class="hovered-button-close">
            <v-icon color="white" class="close">mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{examName}}&nbsp;{{type}}</v-toolbar-title>
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
          <v-spacer></v-spacer>
          <v-btn
            light
            @click="showConfirmDialog"
            class="white orange--text ml-8 mr-2 hovered-button-close"
          >Close</v-btn>

          <!-- <v-toolbar-items>
            <v-btn dark text @click="closeSessionDialog">Save</v-btn>
          </v-toolbar-items>-->
        </v-toolbar>
        <v-stepper v-model="currentStep" non-linear alt-labels>
          <template>
            <!-- correctNO:{{ correctNO || 'null' }} -->
            <!-- markedNO:{{ markedSteps.length-1 || 'null' }} -->
            <!-- wrongNO:{{ wrongNO || 'null' }} -->
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
            <!-- <p>Marked Diffrences Steps:{{ markedDifferencesSteps || 'null' }}</p> -->
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
              prev-icon="mdi-menu-left"
              next-icon="mdi-menu-right"
            ></v-pagination>
            <v-row align="center" justify="center" v-if="markedSteps.length>0">
              <v-btn-toggle
                v-model="currentMarkedStep"
                mandatory
                dense
                rounded
                background-color="orange"
                active-class="orange white--text"
              >
                <v-btn
                  small
                  v-for="(marked, index) in markedSteps"
                  :key="`${index}-step`"
                  @click="handleMarkedPagination(index)"
                >{{markedSteps[index]}}</v-btn>
              </v-btn-toggle>
            </v-row>
            <v-stepper-items>
              <v-stepper-content
                v-for="(question, index) in examQuestions"
                :key="`${index+1}-content`"
                :step="index+1"
              >
                <v-card class="mb-12" color="orange lighten-5" height="auto">
                  <v-card-title>{{question.name}}</v-card-title>
                  <v-row no-gutters justify="center">
                    <img
                      v-if="question.image"
                      height="300"
                      contain
                      :aspect-ratio="16/9"
                      :src="question.image"
                      @click="alertImageDialog(question.image)"
                    />
                  </v-row>
                  <v-radio-group
                    column
                    height="2vh"
                    v-model="selectedOption"
                    :mandatory="false"
                    v-for="option in question.options"
                    :key="option.id"
                    @change="checkSeclecedOption(option)"
                  >
                    <v-radio :value="option" name="selectedOption" color="orange">
                      <template v-slot:label>
                        <v-hover v-slot:default="{ hover }">
                          <span
                            v-bind:class="{ 'success--text':option.isCorrect&&alertShowCorrectAnswer, 'error--text':!option.isCorrect&&alertShowCorrectAnswer&&selectedOption.id==option.id }"
                          >
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
                            <img
                              height="35"
                              contain
                              :aspect-ratio="16/9"
                              :src="option.image"
                              v-if="option.image"
                            />
                            <v-row no-gutters>
                              <v-col cols="12" md="12">
                                <v-expand-transition>
                                  <v-card
                                    rounded
                                    v-if="hover && option.image"
                                    class="popup"
                                    max-width="700"
                                    color="white"
                                  >
                                    <v-card-text>
                                      <div>
                                        <v-img
                                          max-width="700"
                                          contain
                                          :aspect-ratio="16/9"
                                          :src="option.image"
                                          @click="alertImageDialog(option.image)"
                                        ></v-img>
                                        <div class="desc">{{option.name}}</div>
                                      </div>
                                    </v-card-text>
                                  </v-card>
                                </v-expand-transition>
                              </v-col>
                            </v-row>
                          </span>
                        </v-hover>
                        <span></span>
                      </template>
                    </v-radio>
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
                  <span v-if="type=='Practical Test'">
                    <v-tooltip top v-if="!alertShowCorrectAnswer">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          class="ml-5 mt-2 mb-2 text-center"
                          color="orange white--text"
                          @click="showCorrectAnswer"
                          small
                          v-bind="attrs"
                          v-on="on"
                        >
                          View Correct
                          <v-icon color="grey" class="ml-2">mdi-eye-off</v-icon>
                        </v-btn>
                      </template>
                      View Correct Answer
                    </v-tooltip>
                    <v-tooltip top v-else>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          class="ml-5 mt-2 mb-2 text-center"
                          color="orange white--text"
                          @click="hideCorrectAnswer"
                          small
                          v-bind="attrs"
                          v-on="on"
                        >
                          Hide Correct
                          <v-icon class="ml-2">mdi-eye</v-icon>
                        </v-btn>
                      </template>
                      Hide Correct Answer
                    </v-tooltip>
                  </span>
                  <!-- <v-tooltip bottom v-if="!ViewMarkedAnswers && !alertShowMark"> -->
                  <v-tooltip top v-if=" !alertShowMark">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        @click="markAnswer"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        Mark
                        <v-icon color="grey" class="ml-2">mdi-star</v-icon>
                      </v-btn>
                    </template>
                    Mark Question
                  </v-tooltip>
                  <!-- <v-tooltip bottom v-if="!ViewMarkedAnswers && alertShowMark"> -->
                  <v-tooltip top v-if="alertShowMark">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        @click="unMarkAnswer"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        Unmark
                        <v-icon class="ml-2">mdi-star</v-icon>
                      </v-btn>
                    </template>
                    Unmark Question
                  </v-tooltip>

                  <!-- <v-tooltip
                    bottom
                    v-if="!ViewMarkedAnswers&&arrivetoend && answers.filter(function (answer) {
                      return answer.alertShowMark == true;
                    }).length>0"
                  >-->
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-2 mt-2 mb-2 text-center"
                        color="orange white--text"
                        small
                        v-bind="attrs"
                        v-on="on"
                        @click="viewMarkedAnswers"
                        :disabled="ViewMarkedAnswers || markedSteps.length==1"
                      >
                        <v-icon class="ml-2">fast_rewind</v-icon>marked answers
                      </v-btn>
                    </template>
                    View marked answers
                  </v-tooltip>
                  <!-- <v-tooltip
                    bottom
                    v-if="ViewMarkedAnswers&&arrivetoend && answers.filter(function (answer) {
                      return answer.alertShowMark == true;
                    }).length>0"
                  >-->
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="ml-5 mt-2 mb-2 text-center"
                        color="orange white--text"
                        small
                        v-bind="attrs"
                        v-on="on"
                        @click="viewMarkedAnswers"
                        :disabled="!ViewMarkedAnswers || !arrivetoend"
                      >
                        <v-icon class="ml-2">replay</v-icon>Restart
                      </v-btn>
                    </template>
                    Go to first question
                  </v-tooltip>
                  <v-spacer />
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mr-5 mt-2"
                        tile
                        outlined
                        color="orange"
                        @click="showPreviewDialog"
                        v-bind="attrs"
                        v-on="on"
                        :disabled="answers.length==0"
                      >
                        Review
                        <v-icon right>preview</v-icon>
                      </v-btn>
                    </template>
                    Review your Answers
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mr-5 mt-2"
                        tile
                        outlined
                        color="orange"
                        @click="showSubmitDialog"
                        v-bind="attrs"
                        v-on="on"
                        :disabled="answers.length==0"
                        :loading="submitLoader"
                      >
                        Submit
                        <v-icon right>mdi-send</v-icon>
                        <template v-slot:loader>
                          <span>submit</span>
                          <span class="custom-loader">
                            <v-icon light color="orange">autorenew</v-icon>
                          </span>
                        </template>
                      </v-btn>
                    </template>
                    Submit your Answers
                  </v-tooltip>
                </v-row>
              </v-stepper-content>
            </v-stepper-items>
          </template>
        </v-stepper>
        <v-row no-gutters>
          <v-col cols="12" md="12">
            <v-expand-x-transition>
              <v-alert
                v-if="alertShowCorrectAnswer"
                :value="true"
                type="success"
                border="left"
                colored-border
                color="success accent-4"
                elevation="2"
                dismissible
                class="ma-4"
              >{{showCorrectAnswerOptionName}}. {{showCorrectAnswerExplaination}}</v-alert>
            </v-expand-x-transition>
          </v-col>
        </v-row>

        <TimeUpDialog
          :showTimeUpDialog="showTimeUpDialog"
          @closeTimeUpDialog="closeTimeUpDialog"
          title="Time is up!"
          :examName="examName"
          :type="type"
          :result="result"
          :passing_rate="passing_rate"
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
          :result="result"
        />
        <ResultDialog
          :examName="examName"
          :type="type"
          :alertResultDialog="alertResultDialog"
          @closeResultDialog="closeResultDialog"
          :result="result"
          :passing_rate="passing_rate"
          :text1="`Total questions: ${examQuestions.length}`"
          :text2="`Marked questions: ${markedSteps.length-1}`"
          :text3="`Correct answers: ${correctNO}`"
          :text4="`Wrong answers: ${wrongNO}`"
        />
        <PreviewDialog
          :optionChanged="optionChanged"
          :alertPreviewDialog="alertPreviewDialog"
          @closePreviewDialog="closePreviewDialog"
          :examQuestions="examQuestions"
          :answers="answers"
          :certificate_id="certificate_id"
          :exam_id="exam_id"
          :type="type"
          :examName="examName"
        />
        <ConfirmDialog
          :confirmdialog="confirmdialog"
          @OK="OK"
          @Cancel="Cancel"
          control="closeSession"
        />
        <ImageDialog
          :showImageDialog="showImageDialog"
          :image="selectedOptionImageToShow"
          @closeImageDialog="closeImageDialog"
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
import ImageDialog from "../appcore/ImageDialog";
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
      currentMarkedStep: 0,
      snackbar: false,
      showImageDialog: false,
      selectedOptionImageToShow: "",
      notifications: false,
      sound: true,
      widgets: false,
      user_id: 0,
      user_name: "",
      user_email: "",
      user_type: -1,
      arrivetostart: true,
      arrivetoend: false,
      markedDifferencesSteps: [],
      markedSteps: ["Marked"],
      showTimeUpDialog: false,
      alertShowCorrectAnswer: false,
      alertShowMark: false,
      showCorrectAnswerExplaination: "",
      showCorrectAnswerOptionName: "",
      selectedOption: {},
      currentStep: 0,
      wrongNO: 0,
      correctNO: 0,
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
      result: 0,
      submitLoader: false,
      spinner: false,
    };
  },
  components: {
    Timer,
    TimeUpDialog,
    SubmitDialog,
    PreviewDialog,
    ConfirmDialog,
    ResultDialog,
    ImageDialog,
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
    alertImageDialog(image) {
      this.selectedOptionImageToShow = image;
      this.showImageDialog = true;
    },
    closeImageDialog() {
      this.showImageDialog = false;
      this.selectedOptionImageToShow = "";
    },
    getUser() {
      let user = JSON.parse(localStorage.getItem("genius.user"));
      this.user_id = user.id;
      this.user_name = user.name;
      this.user_email = user.email;
      this.user_type = user.is_admin;
    },
    nextStep(n) {
      //if current Answer not answer we will insert an empty object
      if (!this.answers[this.currentStep - 1]) {
        let answer = {
          currentStep: this.currentStep,
          question_id: this.currentQuestionID,
          user_id: this.user_id,
          certificate_id: this.certificate_id,
          exam_id: this.exam_id,
          selectedOption: {},
          alertShowMark: this.alertShowMark,
          alertShowCorrectAnswer: this.alertShowCorrectAnswer,
        };
        this.answers[this.currentStep - 1] = answer;
      }
      ///////////////////
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
        this.currentStep =
          n + this.markedDifferencesSteps[this.clicksAfterRewind];
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (this.clicksAfterRewind === this.markedDifferencesSteps.length - 1) {
          this.arrivetoend = true;
        }
        this.clicksAfterRewind++;
      }
      this.resetQuestion();
    },
    prevStep(n) {
      //if current Answer not answer we will insert an empty object
      if (!this.answers[this.currentStep - 1]) {
        let answer = {
          currentStep: this.currentStep,
          question_id: this.currentQuestionID,
          user_id: this.user_id,
          certificate_id: this.certificate_id,
          exam_id: this.exam_id,
          selectedOption: {},
          alertShowMark: this.alertShowMark,
          alertShowCorrectAnswer: this.alertShowCorrectAnswer,
        };
        this.answers[this.currentStep - 1] = answer;
      }
      ///////////////////
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
        this.currentStep =
          n - this.markedDifferencesSteps[this.clicksAfterRewind];
        this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
        // alert(this.currentQuestionID);
        if (n === this.markedAnswers[1].currentStep) {
          this.arrivetostart = true;
        }
      }

      this.resetQuestion();
    },

    showCorrectAnswer() {
      this.alertShowCorrectAnswer = true;
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
      //to insert show correct answer
      let answerMarked = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
      };
      this.answers[this.currentStep - 1] = answerMarked;
    },
    hideCorrectAnswer() {
      this.alertShowCorrectAnswer = false;
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
      //to insert show correct answer
      let answerMarked = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
      };
      this.answers[this.currentStep - 1] = answerMarked;
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
      this.showTimeUpDialog = false;
      this.arrivetoend = false;
      this.arrivetostart = true;
      this.alertShowMark = false;
      this.answersExplaination = [];
      this.answers = [];
      this.selectedOption = {};
      this.alertShowCorrectAnswer = false;
      this.alertShowMark = false;
      this.markedDifferencesSteps = [];
      this.markedSteps = ["Marked"];
      this.ViewMarkedAnswers = false;
      this.disablePagination = false;
      this.markedAnswers = [];
      this.answers = [];
      this.clicksAfterRewind = 0;
      // this.$emit("closeSessionDialog");
      this.submitLoader = false;
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
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
      };
      this.answers[this.currentStep - 1] = answer;
      let correct = 0;
      let wrong = 0;
      let marked = 0;
      this.answers.forEach((ans) => {
        if (ans.selectedOption.isCorrect) correct = correct + 1;
        if (!ans.selectedOption.isCorrect) wrong = wrong + 1;
      });
      this.correctNO = correct;
      this.wrongNO = wrong;
      // if (
      //   this.answers.some((ans) => ans.question_id === answer.question_id) &&
      //   this.selectedOption.isCorrect
      // )
    },
    markAnswer(n) {
      this.alertShowMark = true;
      let answerMarked = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
      };
      this.answers[this.currentStep - 1] = answerMarked;
      if (
        this.alertShowMark &&
        this.markedSteps.indexOf(this.currentStep == -1)
      )
        this.markedSteps.push(this.currentStep);
      this.markedSteps.sort(function (a, b) {
        if (a == "Marked") return;
        if (a > b) return 1;
        if (a < b) return -1;
        return 0;
      });
      // alert(JSON.stringify(this.answers[this.currentStep - 1]));
    },
    unMarkAnswer() {
      this.alertShowMark = false;
      let answerMarked = {
        currentStep: this.currentStep,
        question_id: this.currentQuestionID,
        user_id: this.user_id,
        certificate_id: this.certificate_id,
        exam_id: this.exam_id,
        selectedOption: this.selectedOption,
        alertShowMark: this.alertShowMark,
        alertShowCorrectAnswer: this.alertShowCorrectAnswer,
      };
      this.answers[this.currentStep - 1] = answerMarked;
      let index = this.markedSteps.indexOf(this.currentStep);
      this.markedSteps.splice(index, 1);
      this.markedSteps.sort(function (a, b) {
        if (a == "Marked") return;
        if (a > b) return 1;
        if (a < b) return -1;
        return 0;
      });
      if (this.markedSteps.length == 0) {
        this.disablePagination = true;
      }
    },
    closeTimeUpDialog() {
      if (this.alertPreviewDialog) this.alertPreviewDialog = false;
      if (this.alertSubmitDialog) this.alertSubmitDialog = false;
      if (this.alertResultDialog) this.alertResultDialog = false;
      if (this.confirmdialog) this.confirmdialog = false;
      this.submit();
      this.showTimeUpDialog = false;
      this.resetExam();
      // this.closeSessionDialog();
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
      this.spinner = true;
      this.submitLoader = true;
      this.alertSubmitDialog = false;
      let user_id = this.user_id;
      let exam_id = this.exam_id;
      let certificate_id = this.certificate_id;
      let result = (this.correctNO / this.examQuestions.length) * 100;
      result = Number.parseFloat(result).toFixed(2);
      this.result = result;
      let answers = JSON.stringify(this.answers);

      axios
        .post("/api/sessions/", { user_id, certificate_id, exam_id, result })
        .then((response) => {
          let session_id = response.data.data.id;
          // alert(session_id);
          axios
            .post("/api/answers/", { answers, session_id })
            .then((res) => {
              this.spinner = false;
              this.submitLoader = false;
              this.alertSubmitDialog = false;
              this.alertResultDialog = true;
              this.resetExam();
            })
            .catch((error) => {
              console.log(error);
              this.spinner = false;
              this.submitLoader = false;
            });
        })
        .catch((error) => {
          console.log(error);
          this.submitLoader = false;
          this.spinner = false;
        });
    },
    // preview() {
    //   this.alertpreviewDialog = false;
    // },
    viewMarkedAnswers() {
      this.ViewMarkedAnswers = !this.ViewMarkedAnswers;
      this.disablePagination = this.ViewMarkedAnswers;
      if (this.ViewMarkedAnswers) {
        this.markedAnswers = this.answers.filter(function (answer) {
          return answer.alertShowMark == true;
        });
        this.markedDifferencesSteps = this.markedAnswers.map(
          (currentAnswer, index, array) => {
            if (typeof array[index + 1] != "undefined")
              return array[index + 1].currentStep - array[index].currentStep;
          }
        );
        //to remove null entry
        this.markedDifferencesSteps.pop();
        this.currentStep = this.markedAnswers[0].currentStep;
        this.alertShowMark = this.markedAnswers[0].alertShowMark;
        this.alertShowCorrectAnswer = this.markedAnswers[0].alertShowCorrectAnswer;
        this.selectedOption = this.markedAnswers[0].selectedOption;
        this.currentQuestionID = this.markedAnswers[0].question_id;
        if (this.alertShowCorrectAnswer) this.showCorrectAnswer();
        else this.hideCorrectAnswer();
        if (this.markedAnswers.length > 1) {
          this.arrivetoend = false;
          this.arrivetostart = true;
        } else {
          this.arrivetoend = true;
          this.arrivetostart = true;
        }
      } else {
        this.currentStep = this.answers[0].currentStep;
        this.alertShowMark = this.answers[0].alertShowMark;
        this.alertShowCorrectAnswer = this.answers[0].alertShowCorrectAnswer;
        this.selectedOption = this.answers[0].selectedOption;
        this.currentQuestionID = this.answers[0].question_id;
        if (this.alertShowCorrectAnswer) this.showCorrectAnswer();
        else this.hideCorrectAnswer();
        this.arrivetostart = true;
        this.arrivetoend = false;
        this.markedDifferencesSteps = [];
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
      this.currentMarkedStep = 0;
      this.currentQuestionID = this.examQuestions[this.currentStep - 1].id;
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
      this.resetQuestion();
      // if (this.answers[this.currentStep - 1] == null)
      //   this.alertShowMark = false;
      // else if (
      //   this.answers[this.currentStep - 1] != null &&
      //   this.answers[this.currentStep - 1].alertShowMark
      // )
      //   this.alertShowMark = true;
      // else if (
      //   this.answers[this.currentStep - 1] != null &&
      //   !this.answers[this.currentStep - 1].alertShowMark
      // )
      //   this.alertShowMark = false;

      // // to get the state of explaination
      // if (
      //   typeof this.answersExplaination[this.currentStep - 1] === "undefined"
      // ) {
      //   this.alertShowCorrectAnswer = false;
      // } else {
      //   this.alertShowCorrectAnswer = this.answersExplaination[
      //     this.currentStep - 1
      //   ].alertShowCorrectAnswer;
      //   this.showCorrectAnswerOptionName = this.answersExplaination[
      //     this.currentStep - 1
      //   ].showCorrectAnswerOptionName;
      //   this.showCorrectAnswerExplaination = this.answersExplaination[
      //     this.currentStep - 1
      //   ].showCorrectAnswerExplaination;
      // }
    },
    handleMarkedPagination(index) {
      // alert(index);
      if (index == 0) {
        this.snackbar = true;
        return;
      }
      this.currentStep = this.markedSteps[index];
      this.handlePagination();
    },
  },
};
</script>
<style >
.close:hover {
  color: white;
  cursor: pointer;
  transform: rotate(90deg);
}
.popup {
  background-color: orange;
  position: fixed;
  top: 50%;
  left: 50%;
  /* bring your own prefixes */
  transform: translate(-50%, -50%);
  width: 700px;
  box-shadow: 4px 8px 16px 4px rgba(0, 0, 0, 0.4);
  z-index: 1;
}
.desc {
  padding: 15px;
  text-align: center;
  background-color: white;
  color: black;
}
.custom-loader {
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
<style scoped>
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
.hovered-button-close:hover {
  background-color: transparent !important;
  color: white !important;
  border: 1px solid white;
}
</style>