<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn class="orange white--text" @click="showAddingDialog">Add New Certificate</v-btn>
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
              :items="certificates"
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
                    <v-img
                      :src="item.image"
                      max-height="125"
                      max-width="125"
                      contain
                      class="py-3 px-3"
                    ></v-img>
                  </td>
                  <td class="text-xs-right">
                    <a href="#" @click="openAddingExams(item)" class="text-primary">{{item.name}}</a>
                  </td>
                  <td class="text-xs-right">{{ item.price }}</td>
                  <td class="text-xs-right">
                    <v-chip :color="getBookedNOColor(item.bookedNO)" dark>{{ item.bookedNO }}</v-chip>
                  </td>
                  <td class="text-xs-right">
                    <!-- <v-icon class="mr-2">mdi-calendar</v-icon> -->
                    {{ item.available_for }}
                    <span class="ml-2" v-if="item.available_for>1">Days</span>
                    <span class="ml-2" v-else>Day</span>
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
                <tr :colspan="headers.length" v-if="item.exams.length >0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">Exams in {{item.name}}</span>
                  </td>
                </tr>
                <tr>
                  <th />
                  <th />
                  <th>Exam name</th>
                  <th>Exam type</th>
                  <th>Duration</th>
                  <th>Description</th>
                  <th></th>
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + exam.id"
                  v-for="exam in item.exams"
                >
                  <td></td>
                  <td></td>
                  <td>{{ exam.name }}</td>
                  <td>
                    <v-chip :color="getTypeColor(exam.type)" dark>{{ exam.type }}</v-chip>
                  </td>
                  <td>
                    <v-icon>mdi-clock</v-icon>
                    {{ exam.duration }}
                  </td>
                  <td colspan="3">{{ exam.description }}</td>
                </tr>
                <tr v-if="item.exams.length<1" :colspan="headers.length">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <v-alert
                      slot="no-results"
                      :value="true"
                      color="red lighten-4"
                      icon="warning"
                    >No exams in "{{ item.name }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="7" class="blue lighten-5" />
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
              @input="fetchCertificates"
              class="mt-3 mb-0"
              color="orange"
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
                    @change="fetchCertificates(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
          <CertificateModal
            :showCertificateDialog="showEditCertificateDialog"
            @close="closeEditing"
            :edit="true"
            :certificates="certificates"
            :certificate="editingCertificate"
            v-show="editingCertificate != null"
          ></CertificateModal>
          <CertificateModal
            :showCertificateDialog="showAddCertificateDialog"
            @close="closeAdding"
            :edit="false"
            :certificates="certificates"
            :certificate="addingCertificate"
            v-show="addingCertificate != null"
          ></CertificateModal>

          <CertificateExamsModal
            @close="closeAddingExams"
            :dialog="dialog"
            :certificates="certificates"
            :certificate="addingCertficates"
            v-show="addingCertficates != null"
          ></CertificateExamsModal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CertificateModal from "./CertificateModal";
import CertificateExamsModal from "./CertificateExamsModal";
// import CertificateExamsModal from "./CertificateModal";
export default {
  data() {
    return {
      showEditCertificateDialog: false,
      showAddCertificateDialog: false,
      dialog: false,
      certificates: [],
      exams: [],
      editingCertificate: null,
      addingCertificate: null,
      addingCertficates: null,
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
        { text: "image", value: "image" },
        { text: "Certificate Name", value: "name" },
        { text: "Price", value: "price" },
        { text: "Booked NO.", value: "bookedNO" },
        { text: "Available for", value: "available_for" },
        { text: "Description", value: "description" },
        { text: "", value: "data-table-expand" },
      ],
    };
  },
  components: { CertificateModal, CertificateExamsModal },
  beforeMount() {
    axios.get("/api/exams/").then((response) => (this.exams = response.data));
    this.fetchCertificates(1);
  },

  methods: {
    fetchCertificates(page_number) {
      let page_url = "/api/certificates/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.certificates = response.data.data;
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
    getBookedNOColor(bookedNO) {
      if (bookedNO > 0) return "green";
      else if (bookedNO > 100) return "orange";
      else return "red";
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },

    showEditingDialog(certificate) {
      this.editingCertificate = certificate;
      this.showEditCertificateDialog = true;
    },
    showAddingDialog(certificate) {
      this.addingCertificate = {
        name: null,
        price: "",
        bookedNO: 0,
        description: null,
        image: "",
      };
      this.showAddCertificateDialog = true;
    },
    closeEditing(certificate) {
      this.showEditCertificateDialog = false;
      this.editingCertificate = null;
    },
    closeAdding(certificate) {
      this.showAddCertificateDialog = false;
      this.addingCertificate = null;
    },
    openAddingExams(certificate) {
      this.dialog = true;
      this.addingCertficates = certificate;
    },
    closeAddingExams(certificate) {
      this.dialog = false;
      this.addingCertficates = null;
    },
  },
};
</script>
