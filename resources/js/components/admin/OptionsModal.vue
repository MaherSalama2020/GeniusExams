<template>
  <v-row justify="center">
    <v-dialog v-model="showOptionsDialog" width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Options of question</span>
          <v-spacer />
          <span class="close" @click="closeOptionsDialog">
            <v-icon x-large>close</v-icon>
          </span>
        </v-card-title>
        <v-card-text>
          <hr class="hr mt-0" />
          <h6>{{data.name}}</h6>
          <v-form dense v-model="isValid" ref="optionsForm">
            <v-row>
              <v-col cols="12">
                <v-container class="inputs-container">
                  <v-row>
                    <v-col cols="12" md="12">
                      <v-text-field
                        dense
                        prepend-inner-icon="mdi-pencil"
                        label="Option text"
                        v-model="option.name"
                        :rules="requiredRules"
                        required
                        outlined
                        autofocus
                        class="pa-0 ma-0"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
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
                      />
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-checkbox
                        class="mx-auto"
                        hide-details
                        v-model="option.isCorrect"
                        label="Correct?"
                        :value="option.isCorrect"
                      ></v-checkbox>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-btn block :disabled="!isValid" color="info" @click="addOption($event)">Save</v-btn>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" md="12">
                      <v-textarea
                        dense
                        v-if="option.isCorrect"
                        label="Explaination"
                        v-model="option.explaination"
                        outlined
                        no-resize
                        rows="2"
                      />
                    </v-col>
                  </v-row>
                </v-container>
              </v-col>
            </v-row>
          </v-form>

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
    </v-dialog>
  </v-row>
</template>
<style scoped>
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
.close {
  font-size: 2rem;
}
.close:hover {
  color: crimson;
  cursor: pointer;
}
</style>
<script>
import draggable from "vuedraggable";
import OptionCard from "./OptionCard";
import ConfirmDialog from "../appcore/ConfirmDialog";
export default {
  props: ["question", "showOptionsDialog"],
  components: {
    draggable,
    OptionCard,
    ConfirmDialog,
  },
  data() {
    return {
      confirmdialog: false,
      errors: [], //for check not repeated sequence
      option: {
        id: "",
        question_id: "",
        name: "",
        sequence: "",
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
        return this.question;
      }
      return {
        name: "",
        sequence: "",
        isCorrect: "",
        explaination: "",
        options: [],
      };
    },
  },
  methods: {
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
      if (this.edit == false) {
        axios
          .post("/api/options/", {
            question_id,
            name,
            sequence,
            isCorrect,
            explaination,
          })
          .then((response) => {
            this.edit = false;
            this.option.name = "";
            this.option.sequence = "";
            this.option.isCorrect = null;
            this.option.explaination = "";
            this.$refs.optionsForm.reset();
            this.fetchOptions();
            // this.data.options.push(this.option);
          })
          .catch((error) => alert(JSON.stringify(error)));
      } else {
        axios
          .put(`/api/options/${this.option.id}`, {
            question_id,
            name,
            sequence,
            isCorrect,
            explaination,
          })
          .then((response) => {
            console.log(response.data.message);
            // this.question.options[index] = this.option;
            this.edit = false;
            this.option.name = "";
            this.option.sequence = "";
            this.option.isCorrect = null;
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
      this.errors = [];
      this.edit = true;
      this.option.id = option.id;
      this.option.option_id = option.id;
      this.option.name = option.name;
      this.option.sequence = option.sequence;
      this.option.isCorrect = option.isCorrect;
      this.option.explaination = option.explaination;
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
      this.$refs.optionsForm.reset();
      this.$emit("closeOptionsDialog");
    },
  },
};
</script>