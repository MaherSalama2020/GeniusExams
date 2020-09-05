<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn color="info" @click="showAddingDialog">Add New Exam</v-btn>
              <v-spacer />
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
                clearable
              ></v-text-field>
            </v-card-title>
            <v-data-table
              dense
              :headers="headers"
              :items="exams"
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
                <tr @dblclick="showEditingDialog(item)">
                  <td>{{ item.id }}</td>
                  <td class="text-xs-right">
                    <a
                      href="#"
                      @click="addingQuestions = item; dialog=true;"
                      class="text-primary"
                    >{{item.name}}</a>
                  </td>
                  <td class="text-xs-right">
                    <span v-if="item.certificate !=null">{{ item.certificate.name }}</span>
                    <span v-else>Null</span>
                  </td>
                  <td class="text-xs-right">
                    <v-chip :color="getTypeColor(item.type)" dark>{{ item.type }}</v-chip>
                  </td>
                  <td class="text-xs-right">
                    <span v-if="item.passing_rate">{{ item.passing_rate }}%</span>
                  </td>
                  <td class="text-xs-right">
                    <v-icon>mdi-clock</v-icon>
                    {{ item.duration }}
                  </td>
                  <td class="text-xs-right">{{ item.description }}</td>
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
                <tr :colspan="headers.length" v-if="item.questions.length >0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">Questiions in {{item.name}}</span>
                  </td>
                </tr>
                <tr>
                  <th />
                  <th />
                  <th>id</th>
                  <th>sequence</th>
                  <th colspan="4">Question text</th>
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + question.id"
                  v-for="question in item.questions"
                >
                  <td></td>
                  <td></td>
                  <td>{{ question.id }}</td>
                  <td>{{ question.pivot.sequence }}</td>
                  <td colspan="4">{{ question.name }}</td>
                </tr>
                <tr v-if="item.questions.length<1" :colspan="headers.length">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <v-alert
                      slot="no-results"
                      :value="true"
                      color="red lighten-4"
                      icon="warning"
                    >No questions in "{{ item.name }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="8" class="blue lighten-5" />
                </tr>
              </template>

              <v-alert
                slot="no-results"
                :value="true"
                color="red lighten-4"
                icon="warning"
              >Your search for "{{ search }}" found no results.</v-alert>
            </v-data-table>
            <v-pagination
              v-model="pagination.current_page"
              :length="pagination.last_page"
              @input="fetchExams"
              class="mt-3 mb-0"
            ></v-pagination>
            <div class="text-center mt-0">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3 row justify-content-end">
                  <v-subheader>Exams per page:</v-subheader>
                </div>
                <div class="col-md-1 row justify-content-start">
                  <v-select
                    dense
                    :items="pagescount"
                    name="pagescount"
                    v-model="selected_per_page"
                    @change="fetchExams(1)"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
          <ExamModal
            :showExamDialog="showEditExamDialog"
            @close="closeEditing"
            :exams="exams"
            :edit="true"
            :certificates="certificates"
            :exam="editingExam"
            v-show="editingExam != null"
          ></ExamModal>
          <ExamModal
            :showExamDialog="showAddExamDialog"
            @close="closeAdding"
            :exams="exams"
            :edit="false"
            :certificates="certificates"
            :exam="addingExam"
            v-show="addingExam != null"
          ></ExamModal>
          <ExamQuestionsModal
            :dialog="dialog"
            @closeAddingQuestions="closeAddingQuestions"
            :exam="addingQuestions"
            :exams="exams"
            v-show="addingQuestions != null"
          ></ExamQuestionsModal>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import ExamModal from "./ExamModal";
import ExamQuestionsModal from "./ExamQuestionsModal";

export default {
  data() {
    return {
      showAddExamDialog: false,
      showEditExamDialog: false,
      dialog: false,
      exams: [],
      questions: [],
      certificates: [],
      editingExam: null,
      addingExam: null,
      addingQuestions: null,
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
          filterable: false,
          value: "id",
        },
        { text: "Exam name", value: "name" },
        { text: "Certificate name", value: "certificate.name" },
        { text: "Type", value: "type" },
        { text: "Passing Rate", value: "passing_rate" },
        { text: "Duration", value: "duration" },
        { text: "Description", value: "description" },
        { text: "", value: "data-table-expand" },
      ],
    };
  },
  components: { ExamModal, ExamQuestionsModal },
  beforeMount() {
    axios.get("/api/exams/").then((response) => (this.exams = response.data));
    axios
      .get("/api/certificates/")
      .then((response) => (this.certificates = response.data));
    this.fetchExams(1);
  },
  methods: {
    fetchExams(page_number) {
      let page_url = "/api/exams/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;
      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.exams = response.data.data;
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
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    showEditingDialog(exam) {
      this.editingExam = exam;
      this.showEditExamDialog = true;
    },
    showAddingDialog(exam) {
      this.addingExam = {
        certificate_id: null,
        name: null,
        type: null,
        duration: null,
        description: null,
      };
      this.showAddExamDialog = true;
    },
    closeEditing(exam) {
      this.showEditExamDialog = false;
      this.editingExam = null;
    },
    closeAdding(exam) {
      this.showAddExamDialog = false;
      this.addingExam = null;
    },
    closeAddingQuestions() {
      this.dialog = false;
      this.addingQuestions = null;
    },
  },
};
</script>