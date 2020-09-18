<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn color="orange white--text" @click="showAddingDialog">Add New Question</v-btn>
              <v-spacer />
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
                clearable
                color="purple"
              ></v-text-field>
            </v-card-title>
            <v-data-table
              dense
              :headers="headers"
              :items="questions"
              :search="search"
              item-key="id"
              :single-expand="singleExpand"
              :expanded.sync="expanded"
              show-expand
              :page.sync="pagination.current_page"
              :items-per-page="pagination.per_page"
              class="elevation-1"
              @page-count="parseInt(pagination.last_page,10)"
              hide-default-footer
            >
              <template v-slot:item="{item,expand, isExpanded}">
                <tr @dblclick="showEditinDialog(item)">
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td class="text-xs-right" v-if="item.image">
                    <v-img
                      :src="item.image"
                      max-height="125"
                      max-width="125"
                      contain
                      class="py-3 px-3"
                    ></v-img>
                  </td>
                  <td v-else>
                    <v-icon>mdi-image</v-icon>No Image
                  </td>
                  <td>
                    <v-chip
                      v-if="item.options"
                      :color="getOptionsColor(item.options.length)"
                      dark
                    >{{item.options.length >0 ? item.options.length+" Options" : "No Options" }}</v-chip>
                    <v-chip v-else :color="getOptionsColor(0)" dark>No Options</v-chip>
                  </td>
                  <td>
                    <v-chip
                      v-if="item.exams"
                      :color="getOptionsColor(item.exams.length)"
                      dark
                    >{{item.exams.length >0 ? item.exams.length+" Exams" : "No Exams" }}</v-chip>
                    <v-chip v-else :color="getOptionsColor(0)" dark>No Exams</v-chip>
                  </td>
                  <td>
                    <a href="#" @click="showOptions(item)" class="text-primary">Show Options</a>
                  </td>
                  <td>
                    <a href="#" @click="showQuestionExams(item)" class="text-primary">Show Exams</a>
                  </td>
                  <td>
                    <v-icon
                      medium
                      color="cyan darken-2"
                      @click="expand(!isExpanded)"
                      v-if="!isExpanded"
                    >mdi-arrow-down-bold-box-outline</v-icon>
                    <v-icon
                      medium
                      color="cyan darken-2"
                      @click="expand(!isExpanded)"
                      v-else
                    >mdi-arrow-up-bold-box-outline</v-icon>
                  </td>
                </tr>
              </template>
              <template v-slot:expanded-item="{ headers, item }">
                <tr :colspan="headers.length" v-if="item.options.length>0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">{{item.name}} Options</span>
                  </td>
                </tr>
                <tr v-if="item.options.length>0">
                  <th />
                  <th>id</th>
                  <th>Option Text</th>
                  <th>Sequence</th>
                  <th>Correct?</th>
                  <th colspan="3">Explaination</th>
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + option.id+'options'"
                  v-for="option in item.options"
                >
                  <td></td>
                  <td>{{option.id}}</td>
                  <td>{{option.name}}</td>
                  <td>
                    <v-icon>mdi-counter</v-icon>
                    {{ option.sequence }}
                  </td>
                  <td>
                    <v-chip
                      :color="getIsCorrectColor(option.isCorrect)"
                      dark
                    >{{option.isCorrect == 1? "Yes" : "No" }}</v-chip>
                  </td>
                  <td colspan="3">{{option.explaination}}</td>
                </tr>

                <tr v-if="item.options.length==0" :colspan="headers.length">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <v-alert
                      slot="no-results"
                      :value="true"
                      type="error"
                      border="left"
                      colored-border
                      color="error accent-4"
                      elevation="2"
                      dismissible
                      class="ma-4"
                    >No options yet for "{{ item.name }}".</v-alert>
                  </td>
                </tr>

                <tr :colspan="headers.length" v-if="item.options.length>0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">{{item.name}} Exams</span>
                  </td>
                </tr>
                <tr v-if="item.exams.length>0">
                  <th />
                  <th>id</th>
                  <th>Exam Name</th>
                  <th colspan="4">Description</th>
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + exam.id+'-exams'"
                  v-for="exam in item.exams"
                >
                  <td></td>
                  <td>{{exam.id}}</td>
                  <td>{{exam.name}}</td>
                  <td colspan="4">{{exam.description}}</td>
                </tr>

                <tr v-if="item.exams.length==0" :colspan="headers.length">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <v-alert
                      slot="no-results"
                      :value="true"
                      type="error"
                      border="left"
                      colored-border
                      color="error accent-4"
                      elevation="2"
                      dismissible
                      class="ma-4"
                    >No Exams yet for "{{ item.name }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="7" class="blue lighten-5" />
                </tr>
              </template>

              <v-alert
                slot="no-results"
                :value="true"
                type="error"
                border="left"
                colored-border
                color="error accent-4"
                elevation="2"
                dismissible
                class="ma-4"
              >Your search for "{{ search }}" found no results.</v-alert>
            </v-data-table>
            <v-pagination
              v-model="pagination.current_page"
              :length="pagination.last_page"
              @input="fetchQuestions"
              class="mt-3 mb-0"
              color="orange"
            ></v-pagination>
            <div class="text-center mt-0">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3 row justify-content-end">
                  <v-subheader>Questions per page:</v-subheader>
                </div>
                <div class="col-md-1 row justify-content-start">
                  <v-select
                    dense
                    :items="pagescount"
                    name="pagescount"
                    v-model="selected_per_page"
                    @change="fetchQuestions(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
          <QuestionModal
            :showQuestionDialog="showEditQuestionDialog"
            @close="closeEditing"
            :edit="true"
            :questions="questions"
            :question="editingQuestion"
            v-show="editingQuestion != null"
          ></QuestionModal>
          <QuestionModal
            :showQuestionDialog="showAddQuestionDialog"
            @close="closeAdding"
            :edit="false"
            :questions="questions"
            :question="addingQuestion"
            v-show="addingQuestion != null"
          ></QuestionModal>
          <OptionsModal
            @closeOptionsDialog="closeOptionsDialog"
            :showOptionsDialog="showOptionsDialog"
            :question="addingOptions"
            v-show="addingOptions != null"
          ></OptionsModal>
          <QuestionExamsModal
            @close="closeQuestionExams"
            :dialog="dialog"
            :question="addingExams"
            :questions="questions"
            v-show="addingExams != null"
          ></QuestionExamsModal>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import QuestionModal from "./QuestionModal";
import OptionsModal from "./OptionsModal";
import QuestionExamsModal from "./QuestionExamsModal";

export default {
  data() {
    return {
      showEditQuestionDialog: false,
      showAddQuestionDialog: false,
      showOptionsDialog: false,
      dialog: false,
      questions: [],
      editingQuestion: null,
      addingQuestion: null,
      addingOptions: null,
      addingExams: null,
      edit: false,

      pagination: {},
      pagescount: [1, 2, 3, 4, 5, 10, 25, 50, 100],
      selected_per_page: 5,
      expanded: [],
      singleExpand: false,
      search: "",
      headers: [
        {
          text: "id",
          align: "start",
          value: "id",
          groupable: false,
          sortable: true,
        },
        {
          text: "Question Text",
          value: "name",
          groupable: false,
          sortable: true,
        },
        {
          text: "Question Image",
          value: "image",
          groupable: false,
          sortable: true,
        },
        {
          text: "Exams",
          value: "exams",
          groupable: false,
          sortable: false,
        },
        {
          text: "Options",
          value: "options",
          groupable: false,
          sortable: false,
        },
        { text: "", value: "exams", groupable: false, sortable: false },
        { text: "", value: "options", groupable: false, sortable: false },
        {
          text: "",
          value: "data-table-expand",
          groupable: false,
          sortable: false,
        },
      ],
    };
  },
  components: { QuestionModal, OptionsModal, QuestionExamsModal },
  beforeMount() {
    // axios
    //   .get("/api/questions/")
    //   .then((response) => (this.questions = response.data));
    this.fetchQuestions(1);
  },
  methods: {
    fetchQuestions(page_number) {
      let page_url = "/api/questions/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.questions = response.data.data;
          this.makePagination(response.data.meta, response.data.links);
        })
        .catch((error) => console.log(error));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        per_page: meta.per_page,
        first: links.first,
        last: links.last,
        next: links.next,
        prev: links.prev,
      };
      this.pagination = pagination;
    },
    getIsCorrectColor(isCorrect) {
      if (isCorrect == 1) return "green";
      else return "red";
    },
    getOptionsColor(length) {
      if (length > 0) return "green";
      else return "red";
    },
    showEditinDialog(question) {
      this.editingQuestion = question;
      this.showEditQuestionDialog = true;
    },
    showAddingDialog() {
      this.addingQuestion = {
        name: null,
      };
      this.showAddQuestionDialog = true;
    },
    closeEditing(question) {
      this.showEditQuestionDialog = false;
      this.editingQuestion = null;
    },
    closeAdding(question) {
      this.showAddQuestionDialog = false;
      this.addingQuestion = null;
    },
    showOptions(item) {
      this.showOptionsDialog = true;
      this.addingOptions = item;
    },
    closeOptionsDialog(question) {
      this.showOptionsDialog = false;
      this.addingOptions = null;
    },
    closeQuestionExams(question) {
      this.dialog = false;
      this.addingExams = null;
    },
    showQuestionExams(item) {
      this.dialog = true;
      this.addingExams = item;
    },
  },
};
</script>