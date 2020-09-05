<template>
  <v-row justify="center">
    <v-dialog v-model="alertPreviewDialog" width="auto" persistent>
      <v-card class="mx-auto">
        <v-card-title>
          <span>Review your answers</span>
          <v-spacer />
          <span @click="closePreviewDialog">
            <v-icon class="close" medium>close</v-icon>
          </span>
        </v-card-title>
        <v-card-title>
          <v-btn @click="toggle">toggle</v-btn>
        </v-card-title>
        <v-card-text>
          <v-treeview
            :open-all="openall"
            v-model="selection"
            dense
            activatable
            hoverable
            :items="items"
            item-key="name"
            transition
            shaped
            color="warning"
            selected-color="indigo"
            open-on-click
            expand-icon="mdi-chevron-down"
            selection-type="leaf"
            return-object
            item-disabled="locked"
          >
            <template v-slot:prepend="{ item }">
              <v-icon v-if="item.children">question_answer</v-icon>
              <v-icon v-else>book</v-icon>
            </template>
            <template slot="label" slot-scope="{ item}">
              <!-- <span
                v-bind:class="{ 'success--text':item.isCorrect, 'orange--text':item.selectedOption==item.id}"
              >{{item.name}}</span>-->
              <strong
                class="text-wrap"
                v-if="!item.children&&item.selectedOption==item.id"
              >{{headers[item.sequence-1]}}.{{item.name}}</strong>
              <span
                class="text-wrap"
                v-if="!item.children&&item.selectedOption!=item.id"
              >{{headers[item.sequence-1]}}.{{item.name}}</span>
              <span v-if="item.children">
                <h6 class="text-wrap">{{item.sequence}}.{{item.name}}</h6>
              </span>
            </template>
          </v-treeview>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange white--text" @click="closePreviewDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: [
    "alertPreviewDialog",
    "certificate_id",
    "exam_id",
    "examQuestions",
    "answers",
    "optionChanged",
  ],
  data() {
    return {
      selection: [],
      items: [],
      headers: ["A", "B", "C", "D", "E", "F"],
      openall: true,
    };
  },
  methods: {
    closePreviewDialog() {
      this.$emit("closePreviewDialog");
    },
    toggle() {
      this.openall = !this.openall;
    },
  },
  watch: {
    optionChanged: {
      handler: function (newValue) {
        // alert("Person with ID: modified");
        // console.log(JSON.stringify(this.answers));
        const Questions = this.examQuestions.map((question) => ({
          id: question.id,
          name: question.name,
          sequence: question.pivot.sequence,
          locked: this.answers.find(
            (answer) => answer.question_id == question.id
          )
            ? false
            : true,
          children: question.options.map((option) => ({
            id: option.id,
            name: option.name,
            isCorrect: option.isCorrect,
            explaination: option.explaination,
            sequence: option.sequence,
            selectedOption: this.answers.find(
              (answer) => answer.question_id == question.id
            )
              ? this.answers.find((answer) => answer.question_id == question.id)
                  .selectedOption.id
              : 0,
          })),
        }));
        // console.log(JSON.stringify(Questions));
        this.items = Questions;
      },
      deep: true,
      immediate: true,
    },
  },
  // computed: {
  //   items() {
  //     const Questions = this.questions.map((question) => ({
  //       id: question.id,
  //       name: question.name,
  //       locked: this.answers.find((answer) => answer.question_id == question.id)
  //         ? false
  //         : true,
  //       children: question.options.map((option) => ({
  //         id: option.id,
  //         name: option.name,
  //         isCorrect: option.isCorrect,
  //         explaination: option.explaination,
  //         selectedOption: this.answers.find(
  //           (answer) => answer.question_id == question.id
  //         )
  //           ? this.answers.find((answer) => answer.question_id == question.id)
  //               .option_id
  //           : 0,
  //       })),
  //     }));
  //     console.log(JSON.stringify(Questions));
  //     return Questions;
  //   },
  // },
};
</script>
<style scoped>
.close {
  font-size: 2rem;
}
.close:hover {
  color: orange;
  cursor: pointer;
  transform: rotate(90deg);
}
</style>