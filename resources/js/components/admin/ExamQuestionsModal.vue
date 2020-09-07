<template>
  <v-row justify="center" no-gutters>
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
      <v-card>
        <v-toolbar dark color="orange">
          <v-btn icon dark @click="closeAddingQuestions">
            <v-icon class="close">mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{data.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-chip :color="getColor(data.type)" dark>{{ data.type }}</v-chip>
          <v-spacer></v-spacer>
          <v-icon>mdi-clock</v-icon>
          <span class="ml-1">{{data.duration}} Minutes</span>
          <v-spacer></v-spacer>
          <v-btn dark @click="closeAddingQuestions" class="white orange--text ml-8 mr-2">Close</v-btn>

          <!-- <v-toolbar-items>
            <v-btn dark text @click="close">Save</v-btn>
          </v-toolbar-items>-->
        </v-toolbar>
        <!-- <div class="col-md-4 row ml-2">
          <v-chip :color="getColor(data.type)" dark>{{ data.type }}</v-chip>
        </div>-->
        <v-form
          ref="questionsForm"
          v-model="isValid"
          @keyup.native.enter="(isValid && isNew=='New')? addQuestion($event):(isValid && isNew=='Exist')?joinQuestion($event):saveQuestion($event)"
          class="ml-3"
        >
          <v-row>
            <v-btn-toggle
              mandatory
              dense
              class="mt-3 ml-3 mb-3"
              v-model="isNew"
              background-color="orange"
              active-class="orange white--text"
              rounded
              @change="changeRadioButoon"
            >
              <v-btn value="New">
                New Question
                <v-icon v-if="isNew=='New'" color="white">add</v-icon>
                <v-icon v-else>add</v-icon>
              </v-btn>
              <v-btn value="Exist">
                Exisiting Question
                <v-icon v-if="isNew=='Exist'" color="white">add_box</v-icon>
                <v-icon v-else>add_box</v-icon>
              </v-btn>
              <v-btn value="Edit" v-if="isNew=='Edit'">
                Edit Question
                <v-icon v-if="isNew=='Edit'" color="white">edit</v-icon>
                <v-icon v-else>edit</v-icon>
              </v-btn>
            </v-btn-toggle>
          </v-row>
          <!-- <v-radio-group dese v-model="isNew" row @change="changeRadioButoon">
            <v-radio
              dense
              label="Edit Question"
              color="orange darken-2"
              value="Edit"
              v-if="isNew=='Edit'"
            ></v-radio>
            <v-radio dense label="New Question" color="orange darken-2" value="New"></v-radio>
            <v-radio dense label="Exisiting Question" color="orange darken-2" value="Exist"></v-radio>
          </v-radio-group>-->
          <v-row no-gutters>
            <v-col cols="12" md="9">
              <v-select
                v-if="isNew=='Exist'"
                dense
                prepend-inner-icon="mdi-help-circle"
                :items="question_not_in_exams"
                item-text="name"
                item-value="id"
                v-model="selectedQuestion"
                label="Question"
                outlined
                bottom
                autocomplete
                required
                :rules="requiredRules"
                color="purple"
              ></v-select>
              <v-text-field
                v-else
                dense
                prepend-inner-icon="mdi-help-circle"
                v-model="enteredQuestion"
                label="Question"
                outlined
                bottom
                required
                :rules="requiredRules"
                color="purple"
              />
            </v-col>
            <v-col cols="12" md="2" class="ml-2">
              <v-text-field
                dense
                prepend-inner-icon="mdi-counter"
                type="number"
                label="Sequence of question in exam"
                v-model="enteredsequence"
                min="1"
                step="1"
                required
                :error-messages="errors"
                :rules="numberRules"
                outlined
                @input="checkIfSequenceisExist"
                color="purple"
                tabindex="-1"
              />
            </v-col>
          </v-row>
          <!-- <v-row no-gutters v-if="enteredImage" class="mb-2">
            <img
              id="cImage"
              height="75px"
              width="75px"
              :src="enteredImage"
              @click="alertImageDialog"
            />
          </v-row>-->
          <v-row no-gutters>
            <v-col cols="12" md="9">
              <v-text-field
                label="Select Image"
                @click="pickImage"
                v-model="enteredImage"
                prepend-inner-icon="image"
                color="purple"
                tabindex="-1"
                outlined
                dense
                :disabled="isNew=='Exist'"
              ></v-text-field>

              <input
                class="form-control"
                type="file"
                id="file"
                @change="attachFile"
                style="display: none"
                accept="image/*"
                ref="image"
              />
            </v-col>
            <v-col cols="12" md="2" class="ml-2">
              <v-btn
                block
                v-if="isNew=='Exist'"
                :disabled="!isValid"
                color="orange white--text"
                @click="joinQuestion"
              >Join Question</v-btn>
              <v-btn
                block
                v-if="isNew=='New'"
                :disabled="!isValid"
                color="orange white--text"
                @click="addQuestion"
              >Add Question</v-btn>
              <v-btn
                block
                v-if="isNew=='Edit'"
                :disabled="!isValid"
                color="orange white--text"
                @click="saveQuestion"
              >Save Question</v-btn>
            </v-col>
          </v-row>
        </v-form>
        <v-sheet class="mx-auto" max-width="auto">
          <v-slide-group
            v-if="questions.length>0"
            class="pa-4"
            :mandatory="true"
            :show-arrows="true"
            :center-active="true"
            prev-icon="mdi-arrow-left-thick mdi-48px"
            next-icon="mdi-arrow-right-thick mdi-48px"
          >
            <v-slide-item
              v-for="question in questions "
              :key="question.id"
              v-slot:default="{ active, toggle}"
            >
              <v-col>
                <v-row class="mb-0" no-gutters>
                  <v-card
                    :color="active ? 'orange lighten-4' : 'white'"
                    rounded
                    class="ma-4"
                    height="300"
                    width="300"
                    @click="editQuestion(toggle, question)"
                  >
                    <v-card-title v-if="active" class="black--text align-end">
                      {{question.pivot.sequence}}
                      <v-spacer />
                      <v-icon color="success" v-if="question.options.length==4">check</v-icon>
                      <v-icon
                        color="error"
                        v-if="question.options.length>0 && question.options.length<4"
                      >warning</v-icon>
                    </v-card-title>
                    <v-card-title v-else>
                      {{question.pivot.sequence}}
                      <v-spacer />
                      <v-icon color="success" v-if="question.options.length==4">check</v-icon>
                      <v-icon
                        color="error"
                        v-if="question.options.length>0 && question.options.length<4"
                      >warning</v-icon>
                    </v-card-title>
                    <v-card-text
                      v-if="active"
                      class="black--text align-end textover"
                    >{{question.name}}</v-card-text>
                    <v-card-text v-else class="textover">{{question.name}}</v-card-text>
                    <v-card-subtitle
                      v-if="active"
                      class="black--text align-end"
                    >{{question.options.length}} Options</v-card-subtitle>
                    <v-card-subtitle v-else>{{question.options.length}} Options</v-card-subtitle>
                    <v-card-actions class="mb-0 p">
                      <v-spacer />
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            fab
                            x-small
                            :color="active ? 'orange' : 'orange'"
                            dark
                            @click="editQuestion(toggle, question)"
                            v-bind="attrs"
                            v-on="on"
                          >
                            <v-icon right>edit</v-icon>
                          </v-btn>
                        </template>
                        Edit Question
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            class="ml-1"
                            fab
                            x-small
                            :color="active ? 'orange' : 'orange'"
                            dark
                            @click="showConfirmDialog(question.id)"
                            v-bind="attrs"
                            v-on="on"
                          >
                            <v-icon right>mdi-delete</v-icon>
                          </v-btn>
                        </template>
                        Delete Question
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            class="ml-1"
                            x-small
                            fab
                            :color="active ? 'orange' : 'orange'"
                            dark
                            @click="showOptions(question)"
                            v-bind="attrs"
                            v-on="on"
                          >
                            <v-icon right>subject</v-icon>
                          </v-btn>
                        </template>
                        Options
                      </v-tooltip>
                    </v-card-actions>
                    <img
                      v-if="question.image"
                      height="75px"
                      width="75px"
                      :src="question.image"
                      v-show="question.image"
                      class="ml-1"
                      @click="alertImageDialog"
                    />
                  </v-card>
                </v-row>
              </v-col>
            </v-slide-item>
          </v-slide-group>
          <v-card
            v-if="questions.length==0 && questionsLoaded"
            class="mx-auto"
            raised
            max-width="340px"
          >
            <v-list-item three-line>
              <v-list-item-content>
                <div class="overline mb-4">{{data.name}}</div>
                <v-list-item-title class="headline mb-1">Has no questions yet</v-list-item-title>
                <v-list-item-subtitle>You can add a new question, or include a new question. if you add a new question you then need to add options ti it.</v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-avatar tile size="80">
                <v-icon x-large color="red darken-1">warning</v-icon>
              </v-list-item-avatar>
            </v-list-item>
          </v-card>
        </v-sheet>
      </v-card>
      <OptionsModal
        @closeOptionsDialog="closeOptionsDialog"
        @targetQuestion="targetQuestion"
        @alertImageDialog="alertImageDialog"
        :showOptionsDialog="showOptionsDialog"
        :examQuestions="questions"
        :question="addingOptions"
        v-show="addingOptions != null"
      ></OptionsModal>
      <ConfirmDialog :confirmdialog="confirmdialog" @OK="OK" @Cancel="Cancel" />
      <ImageDialog
        :showImageDialog="showImageDialog"
        :image="enteredImage"
        @closeImageDialog="closeImageDialog"
      />
    </v-dialog>
  </v-row>
</template>

<script>
import OptionsModal from "./OptionsModal";
import ConfirmDialog from "../appcore/ConfirmDialog";
import ImageDialog from "../appcore/ImageDialog";

export default {
  props: ["dialog", "exam", "exams"],
  data() {
    return {
      isImageQuestion: false,
      showImageDialog: false,
      spinner: false,
      imageChanged: false,
      attachment: null,
      questionsLoaded: false,
      confirmdialog: false,
      question_id: "",
      errors: [], //for check not repeated sequence for question in exam
      isNew: "New",
      editingQuestion: null,
      selectedQuestion: null,
      enteredQuestion: null,
      addingOptions: null,
      showOptionsDialog: false,
      questions: [],
      question_not_in_exams: null,
      enteredsequence: null,
      enteredImage: null,
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v >= 1) || "Sequence must be more than 1.",
      ],
    };
  },
  watch: {
    data: function (newValue, oldValue) {
      if (this.exam != null) {
        let id = this.exam.id;
        axios
          .post("/api/exams/equestions/", { id })
          .then((response) => {
            this.questions = response.data;
            this.questionsLoaded = true;
          })
          .catch((error) => {
            this.questionsLoaded = true;
          });
        axios.post("/api/exams/nequestions/", { id }).then((response) => {
          this.question_not_in_exams = response.data;
        });
      }
    },
  },
  components: { OptionsModal, ConfirmDialog, ImageDialog },
  computed: {
    data: function () {
      if (this.exam != null) {
        if (this.isNew == "Exist" || this.isNew == "New")
          this.enteredsequence = this.exam.questions.length + 1;
        return this.exam;
      }
      return {};
    },
  },
  methods: {
    alertImageDialog() {
      this.showImageDialog = true;
    },
    closeImageDialog() {
      this.showImageDialog = false;
    },
    pickImage() {
      this.$refs.image.click();
    },
    attachFile(event) {
      if (event.target.files[0]) {
        this.spinner = true;
        var formData = new FormData();
        this.attachment = event.target.files[0];
        formData.append("image", this.attachment);
        let headers = { "Content-Type": "multipart/form-data" };
        axios
          .post("/api/upload-file", formData, { headers })
          .then((response) => {
            this.enteredImage = response.data;
            this.imageChanged = true;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
      }
    },
    getColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    fetchQuestions() {
      let index = this.exams.indexOf(this.exam);
      if (this.exam != null) {
        let id = this.exam.id;
        axios
          .post("/api/exams/equestions/", { id })
          .then((response) => {
            this.questions = response.data;
            this.enteredsequence = this.questions.length + 1;
            this.exams[index].questions = response.data;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
        axios.post("/api/exams/nequestions/", { id }).then((response) => {
          this.question_not_in_exams = response.data;
        });
      }
    },
    showConfirmDialog(id) {
      this.question_id = id;
      this.confirmdialog = true;
    },
    OK() {
      this.confirmdialog = false;
      this.unjoinQuestion(this.question_id);
    },
    Cancel() {
      this.confirmdialog = false;
    },
    unjoinQuestion(id) {
      this.spinner = true;
      let question_id = id;
      let exam_id = this.exam.id;
      axios
        .post("/api/exams/unjoinquestion/", { question_id, exam_id })
        .then((response) => {
          this.fetchQuestions();
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
        });
    },
    joinQuestion() {
      this.spinner = true;
      let exam_id = this.exam.id;
      let question_id = this.selectedQuestion;
      let sequence = this.enteredsequence;
      // alert(exam_id);
      // alert(question_id);
      // alert(sequence);
      axios
        .post("/api/exams/joinquestion/", {
          question_id,
          exam_id,
          sequence,
        })
        .then((response) => {
          // this.$refs.questionsForm.reset();
          this.isNew = "Exist";
          this.fetchQuestions();
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
        });
    },
    addQuestion() {
      this.spinner = true;
      let name = this.enteredQuestion;
      let image = this.enteredImage;
      axios
        .post("/api/questions/", {
          name,
          image,
        })
        .then((response) => {
          let question_id = response.data.data.id;
          let exam_id = this.exam.id;
          let sequence = this.enteredsequence;
          axios
            .post("/api/exams/joinquestion/", {
              question_id,
              exam_id,
              sequence,
            })
            .then((res) => {
              // this.$refs.questionsForm.reset();
              this.enteredQuestion = "";
              this.enteredImage = null;
              this.isNew = "New";
              this.fetchQuestions();
            })
            .catch((error) => {
              console.log(error);
              this.spinner = false;
            });
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
        });
    },
    editQuestion(toggle, question) {
      this.errors = [];
      toggle();
      this.isNew = "Edit";
      this.editingQuestion = question;
      this.enteredQuestion = question.name;
      this.enteredsequence = question.pivot.sequence;
      this.enteredImage = question.image;
    },
    saveQuestion() {
      this.spinner = true;
      let index = this.questions.indexOf(this.editingQuestion);

      let name = this.enteredQuestion;
      let sequence = this.enteredsequence;
      let image = this.enteredImage;
      let exam_id = this.exam.id;
      let question_id = this.editingQuestion.id;
      // alert(exam_id);

      axios
        .post(`/api/questions/updatepivot`, {
          question_id,
          exam_id,
          name,
          sequence,
          image,
        })
        .then((response) => {
          this.$refs.questionsForm.reset();
          this.isNew = "New";
          // this.questions[index].pivot.sequence = sequence;
          // this.questions[index].name = name;
          this.fetchQuestions();
        })
        .catch((error) => {
          this.spinner = false;
          console.log(error);
        });
    },
    changeRadioButoon() {
      if (this.isNew == "New" || this.isNew == "Exist") {
        this.editingQuestion = null;
        this.enteredQuestion = "";
        this.enteredsequence = "";
        this.enteredImage = "";
      }
      // alert(JSON.stringify(this.editingQuestion));
    },
    closeAddingOptions(question) {
      this.addingOptions = null;
    },
    closeAddingQuestions() {
      this.questionsLoaded = false;
      this.questions = [];
      this.$refs.questionsForm.reset();
      this.isNew = "New";
      this.$emit("closeAddingQuestions");
    },
    checkIfSequenceisExist() {
      this.errors = [];
      this.questions.forEach((question) => {
        // alert(JSON.stringify(question));
        // alert(JSON.stringify(this.editingQuestion));

        if (
          this.isNew == "Edit" &&
          question.id != this.editingQuestion.id &&
          question.pivot.sequence == this.enteredsequence
        ) {
          this.errors = ["Sequence exists, change it"];
        } else if (
          (this.isNew == "New" || this.isNew == "Exist") &&
          question.pivot.sequence == this.enteredsequence
        )
          this.errors = ["Sequence exists, change it"];
      });
    },
    showOptions(item) {
      this.showOptionsDialog = true;
      this.addingOptions = item;
    },
    closeOptionsDialog(question) {
      this.showOptionsDialog = false;
      this.addingOptions = null;
    },
    targetQuestion(question) {
      this.addingOptions = question;
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
.close:hover {
  color: white;
  cursor: pointer;
  transform: rotate(90deg);
}
</style>



