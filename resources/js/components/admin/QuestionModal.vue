<template>
  <v-row justify="center">
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
    </v-dialog>
  </v-row>
</template>


<script>
export default {
  props: ["question", "edit", "questions", "showQuestionDialog"],
  data() {
    return {
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
    close(event) {
      this.$emit("close", this.question);
    },
    saveQuestion() {
      let index = this.questions.indexOf(this.data);
      let name = this.question.name;

      axios
        .put(`/api/questions/${this.question.id}`, {
          name,
        })
        .then((response) => {
          this.questions[index] = this.question;
          this.$emit("close", this.question);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addQuestion() {
      let name = this.data.name;
      axios
        .post("/api/questions/", {
          name,
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