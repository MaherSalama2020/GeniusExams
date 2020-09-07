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
    <v-dialog
      v-model="showOptionsDialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar dark color="orange">
          <v-btn icon dark @click="closeOptionsDialog">
            <v-icon class="close">mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Options of question</v-toolbar-title>
          <v-spacer />
          <span v-if="data.pivot" class="mr-1">{{data.pivot.sequence}}</span>
          of
          <span v-if="examQuestions" class="ml-1">{{examQuestions.length}}</span>
          <v-spacer />
          <v-btn
            v-if="data.pivot"
            icon
            dark
            @click="previousQuestion"
            class="mr-10 arrowprevious"
            :disabled="data.pivot.sequence==1"
          >
            <v-icon class>skip_previous</v-icon>Prevoius
          </v-btn>
          <v-btn
            v-if="data.pivot"
            icon
            dark
            @click="nextQuestion"
            class="mr-2 arrownext"
            :disabled="data.pivot.sequence==examQuestions.length"
          >
            Next
            <v-icon class>skip_next</v-icon>
          </v-btn>
          <v-btn dark @click="closeOptionsDialog" class="white orange--text ml-8 mr-2">Close</v-btn>
        </v-toolbar>
        <v-card-text>
          <h5 class="mt-3 ml-3">
            <span v-if="data.pivot">{{data.pivot.sequence}}.</span>
            {{data.name}}
          </h5>
          <v-row no-gutters>
            <v-col v-if="data.image">
              <v-row no-gutters>Question Image</v-row>
              <v-row no-gutters>
                <img
                  height="75px"
                  width="75px"
                  :src="data.image"
                  class="mb-2"
                  @click="alertQuestionImageDialog"
                />
              </v-row>
            </v-col>
            <v-col v-if="option.image">
              <v-row no-gutters>Option Image</v-row>
              <v-row no-gutters>
                <img
                  id="cImage"
                  height="75px"
                  width="75px"
                  :src="option.image"
                  @click="alertImageDialog"
                  class="mb-2"
                />
              </v-row>
            </v-col>
          </v-row>
          <v-form dense v-model="isValid" ref="optionsForm" class="ml-3 mb-3">
            <v-row no-gutters>
              <v-col cols="12" md="9">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-help-circle"
                  label="Option text"
                  v-model="option.name"
                  :rules="requiredRules"
                  required
                  outlined
                  autofocus
                  class="pa-0 ma-0"
                  color="purple"
                />
              </v-col>
              <v-col cols="12" md="2" class="ml-2">
                <v-text-field
                  dense
                  prepend-inner-icon="mdi-counter"
                  type="number"
                  min="1"
                  max="4"
                  label="Sequence"
                  v-model="option.sequence"
                  :rules="numberRules"
                  :error-messages="errors"
                  required
                  outlined
                  @input="checkIfSequenceisExist"
                  color="purple"
                  tabindex="-1"
                />
              </v-col>
            </v-row>
            <v-row no-gutters>
              <v-col cols="12" md="12">
                <v-expand-transition>
                  <v-text-field
                    label="Select Image"
                    @click="pickImage"
                    v-model="option.image"
                    prepend-inner-icon="image"
                    color="purple"
                    tabindex="-1"
                    outlined
                    dense
                    v-if="isImageOption"
                  ></v-text-field>
                </v-expand-transition>
              </v-col>
            </v-row>
            <v-row no-gutters style="display: none">
              <v-col cols="12" md="12">
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
            </v-row>
            <!-- <v-row no-gutters>
                    <v-col cols="12" md="4">
                      <v-text-field
                        dense
                        prepend-inner-icon="mdi-counter"
                        type="number"
                        min="1"
                        max="4"
                        label="Sequence"
                        v-model="option.sequence"
                        :rules="numberRules"
                        :error-messages="errors"
                        required
                        outlined
                        @input="checkIfSequenceisExist"
                        color="purple"
                        tabindex="-1"
                      />
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-checkbox
                        dense
                        class="mx-auto"
                        hide-details
                        v-model="option.isCorrect"
                        label="Correct?"
                        :value="option.isCorrect"
                        color="purple"
                      ></v-checkbox>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-checkbox
                        dense
                        class="mx-auto"
                        hide-details
                        v-model="isImageOption"
                        label="Image?"
                        color="purple"
                      ></v-checkbox>
                    </v-col>
            </v-row>-->
            <v-row no-gutters>
              <v-col cols="12" md="12">
                <v-expand-transition>
                  <v-textarea
                    dense
                    v-if="option.isCorrect"
                    label="Explaination"
                    v-model="option.explaination"
                    outlined
                    no-resize
                    rows="2"
                    color="purple"
                  />
                </v-expand-transition>
              </v-col>
            </v-row>
            <v-row no-gutters>
              <v-col cols="12" md="4">
                <v-btn
                  v-if="!edit"
                  block
                  :disabled="!isValid"
                  color="orange white--text"
                  @click="addOption($event)"
                >Add</v-btn>
                <v-btn
                  v-else
                  block
                  :disabled="!isValid"
                  color="orange white--text"
                  @click="addOption($event)"
                >Save</v-btn>
              </v-col>
              <v-col cols="12" md="3">
                <v-btn-toggle
                  multiple
                  dense
                  class="ml-2"
                  rounded
                  background-color="orange"
                  active-class="orange white--text"
                >
                  <v-btn v-model="option.isCorrect">
                    Correct?
                    <v-icon v-if="option.isCorrect" color="success">mdi-check</v-icon>
                    <v-icon v-else color="error">mdi-close</v-icon>
                  </v-btn>
                  <v-btn v-model="isImageOption">
                    Image?
                    <v-icon v-if="isImageOption" color="white">mdi-image</v-icon>
                    <v-icon v-else>mdi-image</v-icon>
                  </v-btn>
                </v-btn-toggle>
              </v-col>
              <v-col cols="12" md="2">
                <v-btn color="orange white--text" @click="cancelEditOption">Reload</v-btn>
              </v-col>
            </v-row>
          </v-form>
          <hr class="hr mt-0" />
          <draggable
            :animation="200"
            ghost-class="moving-card"
            v-model="data.options"
            group="data.options"
            filter=".action-button"
            tag="div"
            @change="changesequences"
          >
            <option-card
              v-for="(option,index) in data.options"
              :index="index+1"
              :key="option.id"
              :option="option"
              @on-edit="editOption(option)"
              @on-delete="showConfirmDialog(option.id)"
            ></option-card>
          </draggable>
        </v-card-text>
      </v-card>
      <ConfirmDialog :confirmdialog="confirmdialog" @OK="OK" @Cancel="Cancel" />
      <ImageDialog
        :showImageDialog="showImageDialog"
        :image="option.image"
        @closeImageDialog="closeImageDialog"
      />
      <v-snackbar v-model="snackbar">
        There is another option in editing, save it an try again.
        <template
          v-slot:action="{ attrs }"
        >
          <v-btn color="orange" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
        </template>
      </v-snackbar>
    </v-dialog>
  </v-row>
</template>

<script>
import draggable from "vuedraggable";
import OptionCard from "./OptionCard";
import ConfirmDialog from "../appcore/ConfirmDialog";
import ImageDialog from "../appcore/ImageDialog";
export default {
  props: ["question", "showOptionsDialog", "examQuestions"],
  components: {
    draggable,
    OptionCard,
    ConfirmDialog,
    ImageDialog,
  },
  data() {
    return {
      snackbar: false,
      isImageOption: false,
      showImageDialog: false,
      spinner: false,
      imageChanged: false,
      attachment: null,
      confirmdialog: false,
      errors: [], //for check not repeated sequence
      option: {
        id: "",
        question_id: "",
        name: "",
        sequence: 1,
        isCorrect: "",
        explaination: "",
      },
      option_id: "",
      edit: false,
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v >= 1) || "Sequence must be more than 1.",
        (v) => (v && v <= 4) || "Sequence must be less than 4.",
      ],
    };
  },
  computed: {
    data: function () {
      // alert(JSON.stringify(this.question));
      if (this.question != null) {
        if (this.question.options.length < 4)
          this.option.sequence = this.question.options.length + 1;
        else this.option.sequence = "";
        return this.question;
      }
      // if (this.next && !this.previous) {
      //   let currentSequence = this.question.pivot.sequence - 1;
      //   let targetQuestion = this.examQuestions[currentSequence + 1];
      //   if (targetQuestion.options.length < 4)
      //     this.option.sequence = targetQuestion.options.length + 1;
      //   else this.option.sequence = "";
      //   this.next = false;
      //   return targetQuestion;
      // }
      // if (!this.next && this.previous) {
      //   let currentSequence = this.question.pivot.sequence - 1;
      //   let targetQuestion = this.examQuestions[currentSequence - 1];
      //   if (targetQuestion.options.length < 4)
      //     this.option.sequence = targetQuestion.options.length + 1;
      //   else this.option.sequence = "";
      //   this.next = false;
      //   return targetQuestion;
      // }
      return {
        name: "",
        sequence: 1,
        isCorrect: "",
        explaination: "",
        options: [],
      };
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
            this.option.image = response.data;
            let image = response.data;
            this.imageChanged = true;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
      }
    },
    // closeAddingOptions(event) {
    //   this.$emit("closeAddingOptions");
    // },
    fetchOptions() {
      let id = this.data.id;
      // alert(id);
      axios
        .post("/api/questions/qoptions/", { id })
        .then((response) => {
          this.data.options = response.data.options;
          if (this.data.options.length == 0) this.option.sequence = 1;
          if (this.data.options.length == 1) this.option.sequence = 2;
          if (this.data.options.length == 2) this.option.sequence = 3;
          if (this.data.options.length == 3) this.option.sequence = 4;
          this.$forceUpdate();
          // alert(JSON.stringify(this.data.options));
          // alert(this.question.options);
        })
        .catch((error) => alert(JSON.stringify(error)));
    },

    addOption(e) {
      e.preventDefault();
      let question_id = this.data.id;
      let name = this.option.name;
      let sequence = this.option.sequence;
      let isCorrect = null;
      if (!this.option.isCorrect || this.option.isCorrect == "") isCorrect = 0;
      else isCorrect = 1;
      let explaination = "";
      if (this.option.explaination) explaination = this.option.explaination;
      let image = "";
      if (this.edit == false) {
        if (!this.imageChanged) image = "";
        else image = this.option.image;
        axios
          .post("/api/options/", {
            question_id,
            name,
            sequence,
            isCorrect,
            explaination,
            image,
          })
          .then((response) => {
            this.edit = false;
            this.imageChanged = false;
            this.option.name = "";
            this.option.sequence = "";
            this.option.isCorrect = false;
            this.isImageOption = false;
            this.option.explaination = "";
            this.$refs.optionsForm.reset();
            this.fetchOptions();
            // this.data.options.push(this.option);
          })
          .catch((error) => alert(JSON.stringify(error)));
      } else {
        image = this.option.image;
        axios
          .put(`/api/options/${this.option.id}`, {
            question_id,
            name,
            sequence,
            isCorrect,
            explaination,
            image,
          })
          .then((response) => {
            console.log(response.data.message);
            // this.question.options[index] = this.option;
            this.edit = false;
            this.imageChanged = false;
            this.option.name = "";
            this.option.sequence = "";
            this.option.isCorrect = false;
            this.isImageOption = false;
            this.option.explaination = "";
            this.$refs.optionsForm.reset();
            this.fetchOptions();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    editOption(option) {
      if (this.edit) {
        this.snackbar = true;
        return;
      }
      this.errors = [];
      this.edit = true;
      this.option.id = option.id;
      this.option.option_id = option.id;
      this.option.name = option.name;
      this.option.sequence = option.sequence;
      this.option.isCorrect = option.isCorrect;
      this.option.explaination = option.explaination;
      this.option.image = option.image;
      if (option.image) this.isImageOption = true;
      else this.isImageOption = false;
    },
    cancelEditOption(option) {
      this.edit = false;
      this.imageChanged = false;
      this.option.name = "";
      this.option.sequence = "";
      this.option.isCorrect = false;
      this.isImageOption = false;
      this.option.explaination = "";
      this.$refs.optionsForm.reset();
      this.fetchOptions();
    },
    showConfirmDialog(id) {
      this.option_id = id;
      this.confirmdialog = true;
    },
    OK() {
      this.confirmdialog = false;
      this.deleteOption(this.option_id);
    },
    Cancel() {
      this.confirmdialog = false;
    },
    deleteOption(id) {
      axios
        .delete(`/api/options/${id}`)
        .then(() => {
          this.fetchOptions();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changesequences(event) {
      // alert(JSON.stringify(event));
      const option = event.moved.element;
      const id = event.moved.element.id;
      const oldSequence = event.moved.oldIndex + 1;
      const sequence = event.moved.newIndex + 1;
      // alert(option_id + ": " + oldSequence + "--->" + newSequence);
      axios
        .post(`/api/options/updatesequences/`, {
          id,
          sequence,
        })
        .then((response) => {
          this.fetchOptions();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    checkIfSequenceisExist() {
      this.errors = [];
      this.data.options.forEach((option) => {
        if (
          this.edit &&
          option.id != this.option.id &&
          option.sequence == this.option.sequence
        ) {
          this.errors = ["Sequence exists, change it"];
        } else if (!this.edit && option.sequence == this.option.sequence)
          this.errors = ["Sequence exists, change it"];
      });
    },
    closeOptionsDialog() {
      this.edit = false;
      this.imageChanged = false;
      this.option.name = "";
      this.option.sequence = "";
      this.option.isCorrect = false;
      this.isImageOption = false;
      this.option.explaination = "";
      this.$refs.optionsForm.reset();
      this.$emit("closeOptionsDialog");
    },
    nextQuestion() {
      let currentSequence = this.question.pivot.sequence - 1;
      let targetQuestion = this.examQuestions[currentSequence + 1];
      this.$emit("targetQuestion", targetQuestion);
    },
    previousQuestion() {
      let currentSequence = this.question.pivot.sequence - 1;
      let targetQuestion = this.examQuestions[currentSequence - 1];
      this.$emit("targetQuestion", targetQuestion);
    },
    alertQuestionImageDialog() {
      this.$emit("alertImageDialog");
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
.arrownext:hover {
  animation: moveRightToLeft -2s 1s infinite linear;
}
@keyframes moveRightToLeft {
  0% {
    transform: translate(4px, 0);
  }
  50% {
    transform: translate(-4px, 0);
  }
  50.1% {
    transform: translate(4px, 0);
  }
  100% {
    transform: translate(-4px, 0);
  }
}
.arrowprevious:hover {
  animation: moveLeftToRight -2s 1s infinite linear;
}
@keyframes moveLeftToRight {
  0% {
    transform: translate(-4px, 0);
  }
  50% {
    transform: translate(4px, 0);
  }
  50.1% {
    transform: translate(-4px, 0);
  }
  100% {
    transform: translate(4px, 0);
  }
}
</style>