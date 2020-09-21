<template>
  <v-row justify="center">
    <v-dialog v-model="alertPreviewDialog" width="auto" persistent>
      <v-card class="mx-auto">
        <v-card-title>
          <span>Review your answers</span>
          <v-spacer />
          <span>{{examName}}</span>&nbsp;
          <span>
            <v-chip :color="getTypeColor(type)" dark>{{ type}}</v-chip>
          </span>
          <v-spacer />
          <span @click="closePreviewDialog">
            <v-icon class="close" medium>close</v-icon>
          </span>
        </v-card-title>
        <hr class="hr mt-0 mb-0" />
        <!-- <v-card-title>
          <v-btn fab @click="all" v-if="!openall" dark small color="orange white--text">
            <v-icon>add</v-icon>
          </v-btn>
          <v-btn fab @click="none" v-if="openall" dark small color="orange white--text">
            <v-icon>mdi-minus</v-icon>
          </v-btn>
        </v-card-title>-->
        <v-card-actions>
          <v-row no-gutters align="center" justify="start">
            <v-col col2="12" md="1">
              <v-tooltip top v-if="open.length==0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="ml-4"
                    fab
                    small
                    dark
                    @click="all"
                    color="black white--text"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>add</v-icon>
                  </v-btn>
                </template>
                Expand All
              </v-tooltip>
              <v-tooltip top v-if="open.length>0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="ml-4"
                    fab
                    small
                    dark
                    @click="none"
                    color="black  white--text"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>mdi-minus</v-icon>
                  </v-btn>
                </template>
                Collapse All
              </v-tooltip>
            </v-col>
          </v-row>
        </v-card-actions>
        <v-card-text>
          <v-treeview
            :open-all="openall"
            v-model="selection"
            :open.sync="open"
            dense
            hoverable
            :items="items"
            item-key="name"
            transition
            shaped
            color="orange"
            open-on-click
            expand-icon="mdi-arrow-down-drop-circle-outline"
            return-object
            item-disabled="locked"
          >
            <template v-slot:prepend="{ item }">
              <v-icon v-if="item.children">question_answer</v-icon>
              <v-icon
                v-else-if="!item.children&&item.selectedOption==item.id"
                v-bind:class="{'orange--text':!item.children&&item.selectedOption==item.id}"
              >book</v-icon>
              <v-icon v-else>book</v-icon>
            </template>
            <template slot="label" slot-scope="{ item}">
              <!-- <span
                v-bind:class="{ 'success--text':item.isCorrect, 'orange--text':item.selectedOption==item.id}"
              >{{item.name}}</span>-->
              <v-row
                no-gutters
                v-if="!item.children&&item.selectedOption==item.id"
                align="center"
                justify="start"
              >
                <u class="orange--text expansion-panel-header font-weight-bold">
                  <strong class="text-wrap">{{headers[item.sequence-1]}}.&nbsp;{{item.name}}</strong>
                </u>
                <v-img
                  v-if="!item.children&&item.image"
                  max-width="45"
                  contain
                  :aspect-ratio="16/9"
                  :src="item.image"
                  @click="alertImageDialog(item.image)"
                ></v-img>
              </v-row>
              <v-row
                no-gutters
                v-if="!item.children&&item.selectedOption!=item.id"
                align="center"
                justify="start"
              >
                <span
                  class="text-wrap expansion-panel-header font-weight-bold"
                >{{headers[item.sequence-1]}}.&nbsp;{{item.name}}</span>
                <v-img
                  v-if="!item.children&&item.image"
                  max-width="45"
                  contain
                  :aspect-ratio="16/9"
                  :src="item.image"
                  @click="alertImageDialog(item.image)"
                ></v-img>
              </v-row>
              <v-row
                no-gutters
                v-if="item.children"
                align="center"
                justify="start"
                @click="popLastOpened(item)"
              >
                <span
                  class="text-wrap expansion-panel-header font-weight-bold"
                >{{item.sequence}}.&nbsp;{{item.name}}</span>
                <v-img
                  v-if="item.children&&item.image"
                  max-width="45"
                  contain
                  :aspect-ratio="16/9"
                  :src="item.image"
                  @click="alertImageDialog(item.image)"
                ></v-img>
              </v-row>
            </template>
          </v-treeview>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange white--text" @click="closePreviewDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
      <ImageDialog
        :showImageDialog="showImageDialog"
        :image="selectedImageToShow"
        @closeImageDialog="closeImageDialog"
      />
    </v-dialog>
  </v-row>
</template>
<script>
import ImageDialog from "./ImageDialog";
export default {
  props: [
    "alertPreviewDialog",
    "certificate_id",
    "exam_id",
    "type",
    "examName",
    "examQuestions",
    "answers",
    "optionChanged",
  ],
  components: { ImageDialog },
  data() {
    return {
      open: [],
      selectedImageToShow: "",
      showImageDialog: false,
      selection: [],
      items: [],
      headers: ["A", "B", "C", "D", "E", "F"],
      openall: false,
    };
  },
  methods: {
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    alertImageDialog(image) {
      this.selectedImageToShow = image;
      this.showImageDialog = true;
    },
    closeImageDialog() {
      this.showImageDialog = false;
      this.selectedImageToShow = "";
    },
    closePreviewDialog() {
      this.$emit("closePreviewDialog");
    },
    all() {
      this.open = this.items;
    },
    none() {
      this.open = [];
    },
    popLastOpened(item) {
      this.open = [];
      this.open[0] = JSON.stringify(item);
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
          image: question.image,
          locked: this.answers.find(
            (answer) =>
              typeof answer != "undefined" && answer.question_id == question.id
          )
            ? false
            : true,
          children: question.options.map((option) => ({
            id: option.id,
            name: option.name,
            isCorrect: option.isCorrect,
            explaination: option.explaination,
            sequence: option.sequence,
            image: option.image,
            selectedOption: this.answers.find(
              (answer) =>
                typeof answer != "undefined" &&
                answer.question_id == question.id
            )
              ? this.answers.find(
                  (answer) =>
                    typeof answer != "undefined" &&
                    answer.question_id == question.id
                ).selectedOption.id
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