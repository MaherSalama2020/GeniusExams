<template>
  <v-row>
    <v-col cols="12" md="9">
      <v-treeview
        open-all
        v-model="selection"
        dense
        hoverable
        :items="items"
        item-key="name"
        transition
        shaped
        activatable
        color="warning"
        selected-color="indigo"
        open-on-click
        expand-icon="mdi-chevron-down"
        selection-type="leaf"
        selectable
        return-object
        item-disabled="locked"
      >
        <template v-slot:prepend="{ item }">
          <v-icon v-if="item.children">question_answer</v-icon>
          <v-icon v-else>book</v-icon>
        </template>
        <template slot="label" slot-scope="{ item}">
          <v-row no-gutters align="center" justify="start">
            <span
              v-if="!item.children"
              v-bind:class="{ 'success--text':item.isCorrect, 'error--text text-decoration-line-through font-weight-bold':item.selectedOption==item.id && !item.isCorrect}"
            >{{headers[item.sequence-1]}}.&nbsp;{{item.name}}</span>
            <span>
              <v-icon color="success" v-if="item.selectedOption==item.id && item.isCorrect">check</v-icon>
              <v-icon color="error" v-if="item.selectedOption==item.id && !item.isCorrect">close</v-icon>
            </span>
            <v-img
              v-if="!item.children&&item.image"
              max-width="45"
              contain
              :aspect-ratio="16/9"
              :src="item.image"
              @click="alertImageDialog(item.image)"
            ></v-img>
          </v-row>
          <!-- <strong
            class="text-wrap succes--text"
            v-if="!item.children&&item.selectedOption==item.id&&item.isCorrect"
          >{{headers[item.sequence-1]}}.{{item.name}}</strong>
          <span
            class="text-wrap success--text"
            v-if="!item.children&&item.selectedOption!=item.id&&item.isCorrect"
          >{{headers[item.sequence-1]}}.{{item.name}}</span>
          <strong
            class="text-wrap error--text"
            v-if="!item.children&&item.selectedOption==item.id&&!item.isCorrect"
          >{{headers[item.sequence-1]}}.{{item.name}}</strong>
          <span
            class="text-wrap error--text"
            v-if="!item.children&&item.selectedOption!=item.id&&!item.isCorrect"
          >{{headers[item.sequence-1]}}.{{item.name}}</span>-->
          <v-row v-if="item.children" no-gutters align="center" justify="start">
            <span>
              <h6 class="text-wrap">{{item.sequence}}.&nbsp;{{item.name}}</h6>
            </span>
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
    </v-col>
    <v-divider vertical></v-divider>
    <!-- <v-col>{{selection}}</v-col> -->
    <v-col cols="12" md="2">
      <div
        v-if="!selection.length"
        class="title font-weight-light grey--text pa-4 text-center"
      >Explaination</div>
      <v-scroll-x-transition v-else group hide-on-leave>
        <div v-for="(node, i) in selection" :key="i">
          <div v-if="node.explaination" color="grey" dark small class="my-1">
            <v-icon left small>book</v-icon>
            Correct Answer: {{headers[node.sequence-1]}}.&nbsp;{{ node.name }}
          </div>
          <div v-if="node.explaination" color="grey" dark small class="my-1">
            <v-icon left small>book</v-icon>
            Explaination: {{ node.explaination }}
          </div>
        </div>
      </v-scroll-x-transition>
    </v-col>
    <ImageDialog
      :showImageDialog="showImageDialog"
      :image="selectedImageToShow"
      @closeImageDialog="closeImageDialog"
    />
  </v-row>
</template>

<script>
import ImageDialog from "./ImageDialog";
export default {
  props: ["certificate_id", "exam_id", "answers"],

  data() {
    return {
      selectedImageToShow: "",
      showImageDialog: false,
      selection: [],
      questions: [],
      headers: ["A", "B", "C", "D", "E", "F"],
    };
  },
  components: { ImageDialog },
  beforeMount() {
    let id = this.exam_id;
    axios.post("/api/exams/equestions", { id }).then((response) => {
      this.questions = response.data;
    });
  },
  computed: {
    items() {
      //   const Questions = this.questions.map((question) => {
      //     const container1 = {};

      //     container1.id = question.id;
      //     container1.name = question.name;
      //     container1.children = question.options.map((option) => {
      //       const container2 = {};
      //       container2.id = option.id;
      //       container2.name = option.name;
      //       container2.explaination = option.explaination;
      //       container2.selectedOption = this.answers.find(
      //         (answer) => answer.question_id == question.id
      //       ).option_id;
      //       return container2;
      //     });

      //     return container1;
      //   });
      const Questions = this.questions.map((question) => ({
        id: question.id,
        name: question.name,
        sequence: question.pivot.sequence,
        image: question.image,
        locked: this.answers.find((answer) => answer.question_id == question.id)
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
            (answer) => answer.question_id == question.id
          )
            ? this.answers.find((answer) => answer.question_id == question.id)
                .option_id
            : 0,
        })),
      }));
      //   alert(JSON.stringify(Questions));
      return Questions;
    },
  },
  methods: {
    alertImageDialog(image) {
      this.selectedImageToShow = image;
      this.showImageDialog = true;
    },
    closeImageDialog() {
      this.showImageDialog = false;
      this.selectedImageToShow = "";
    },
  },
};
</script>
