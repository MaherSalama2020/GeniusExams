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
    <v-dialog v-model="showQuestionDialog" width="600px">
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Edit Question</span>
          <span v-else class="headline">Add Question</span>
          <v-spacer />
          <span @click="close">
            <v-icon class="close" medium>close</v-icon>
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
          <v-form v-model="isValid" @keyup.native.enter="isValid && addQuestion($event)">
            <div class="form-row">
              <v-textarea
                autofocus
                prepend-inner-icon="mdi-help-circle"
                label="Questien Text"
                v-model="data.name"
                step="15"
                required
                :rules="requiredRules"
                outlined
                no-resize
                color="purple"
              />
            </div>
            <div class="form-group">
              <img
                id="cImage"
                class="col-md-3"
                :src="data.image"
                v-show="data.image"
                @dblclick="alertImageDialog"
              />
              <v-text-field
                label="Select Image"
                @click="pickImage"
                v-model="data.image"
                prepend-icon="image"
                color="purple"
                tabindex="-1"
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
            </div>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="edit"
            :disabled="!isValid"
            color="orange white--text"
            @click="saveQuestion"
          >Save Question</v-btn>
          <v-btn
            v-else
            :disabled="!isValid"
            color="orange white--text"
            @click="addQuestion"
          >Add Question</v-btn>
        </v-card-actions>
      </v-card>
      <ImageDialog
        :showImageDialog="showImageDialog"
        :image="data.image"
        @closeImageDialog="closeImageDialog"
      />
    </v-dialog>
  </v-row>
</template>


<script>
import ImageDialog from "../appcore/ImageDialog";
export default {
  props: ["question", "edit", "questions", "showQuestionDialog"],
  data() {
    return {
      showImageDialog: false,
      spinner: false,
      imageChanged: false,
      attachment: null,
      options: [],
      option: {
        id: "",
        question_id: "",
        name: "",
        sequence: 1,
        isCorrect: "",
        explaination: "",
      },
      option_id: "",
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
    };
  },
  components: { ImageDialog },
  computed: {
    data: function () {
      if (this.question != null) {
        return this.question;
      }
      return {
        name: null,
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
    close(event) {
      this.$emit("close", this.question);
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
            this.data.image = response.data;
            let image = response.data;
            this.imageChanged = true;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
      }
    },
    saveQuestion(e) {
      e.preventDefault();
      let index = this.questions.indexOf(this.data);
      let name = this.question.name;
      let image = this.question.image;
      axios
        .put(`/api/questions/${this.question.id}`, {
          name,
          image,
        })
        .then((response) => {
          this.questions[index] = this.question;
          this.$emit("close", this.question);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addQuestion(e) {
      e.preventDefault();
      let name = this.data.name;
      let image = "";
      if (!this.imageChanged) image = "";
      else image = this.question.image;
      axios
        .post("/api/questions/", {
          name,
          image,
        })
        .then((response) => {
          this.question.id = response.data.data.id;
          this.question.name = response.data.data.name;
          this.question.options = [];
          this.question.exams = [];
          this.questions.unshift(this.question);
          this.$emit("close", this.question);
        })
        .catch((error) => {
          console.log(error);
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