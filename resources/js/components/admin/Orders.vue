<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn color="orange white--text" @click="showAddingDialog">Add New Order</v-btn>
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
              :items="orders"
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
                  <td>{{ item.certificate.name }}</td>
                  <td>
                    <v-img
                      :src="item.certificate.image"
                      max-height="125"
                      max-width="125"
                      contain
                      class="py-3 px-3"
                    ></v-img>
                  </td>
                  <td>{{ item.user.name }}</td>
                  <td>{{ item.user.email }}</td>
                  <!-- <td>{{ item.date }}</td>
                  <td>{{ item.time }}</td>-->
                  <td>{{ item.certificate.price }}</td>
                  <td>
                    <v-chip
                      :color="getIsFinishedColor(item.is_finished)"
                      dark
                    >{{item.is_finished == 1? "Yes" : "No" }}</v-chip>
                  </td>
                  <td>{{new Date(item.created_at).toLocaleDateString()}}</td>
                  <td v-if="item.is_finished == 0">
                    <button
                      class="btn btn-success"
                      @click="finish(index = orders.findIndex(x => x.id ===item.id))"
                    >Finish</button>
                  </td>
                  <td v-else>
                    <button
                      disabled
                      class="btn btn-success"
                      @click="finish(index = orders.findIndex(x => x.id ===item.id))"
                    >Finish</button>
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
                <tr :colspan="headers.length" v-if="item.certificate">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">{{item.certificate.name}}</span>
                  </td>
                </tr>
                <tr>
                  <th />
                  <th />
                  <th>Booked NO.</th>
                  <th colspan="7">Description</th>
                </tr>
                <tr :colspan="headers.length">
                  <td></td>
                  <td></td>
                  <td>
                    <v-icon class="jsutify-center">mdi-counter</v-icon>
                    {{ item.certificate.bookedNO }}
                  </td>
                  <td colspan="7">{{ item.certificate.description }}</td>
                </tr>
                <tr v-if="!item.certificate" :colspan="headers.length">
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
                    >No Certificates in "{{ item.name }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="10" class="blue lighten-5" />
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
              @input="fetchOrders"
              class="mt-3 mb-0"
              color="orange"
            ></v-pagination>
            <div class="text-center mt-0">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3 row justify-content-end">
                  <v-subheader>Orders per page:</v-subheader>
                </div>
                <div class="col-md-1 row justify-content-start">
                  <v-select
                    dense
                    :items="pagescount"
                    name="pagescount"
                    v-model="selected_per_page"
                    @change="fetchOrders(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
          <OrderModal
            :showOrderDialog="showEditOrderDialog"
            @close="closeEditing"
            :orders="orders"
            :edit="true"
            :certificates="certificates"
            :users="users"
            :order="editingOrder"
            v-show="editingOrder != null"
          ></OrderModal>
          <OrderModal
            :showOrderDialog="showAddOrderDialog"
            @close="closeAdding"
            :orders="orders"
            :edit="false"
            :certificates="certificates"
            :users="users"
            :order="addingOrder"
            v-show="addingOrder != null"
          ></OrderModal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OrderModal from "./OrderModal";

export default {
  data() {
    return {
      showEditOrderDialog: false,
      showAddOrderDialog: false,
      orders: [],
      users: [],
      certificates: [],
      editingOrder: null,
      addingOrder: null,
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
          text: "Exam Name",
          value: "certificate.name",
          groupable: false,
          sortable: true,
        },
        {
          text: "Image",
          value: "certificate.image",
          groupable: false,
          sortable: true,
        },
        {
          text: "User Name",
          value: "user.name",
          groupable: false,
          sortable: true,
        },
        {
          text: "User Email",
          value: "user.email",
          groupable: false,
          sortable: true,
        },
        // { text: "Date", value: "date", groupable: true, sortable: true },
        // { text: "Time", value: "time", groupable: false, sortable: true },
        {
          text: "Cost",
          value: "certificate.price",
          groupable: false,
          sortable: true,
        },
        { text: "Finished?", value: "is_finished", groupable: false },
        {
          text: "Date of Order",
          value: "created_at",
          groupable: false,
          sortable: true,
        },
        {
          text: "",
          value: "data-table-finish",
          groupable: false,
          sortable: false,
        },
        {
          text: "",
          value: "data-table-expand",
          groupable: false,
          sortable: false,
        },
      ],
    };
  },
  components: { OrderModal },
  beforeMount() {
    axios.get("/api/orders/").then((response) => (this.orders = response.data));
    axios
      .get("/api/certificates/")
      .then((response) => (this.certificates = response.data));
    axios.get("/api/users/").then((response) => (this.users = response.data));
    this.fetchOrders(1);
  },

  methods: {
    fetchOrders(page_number) {
      let page_url = "/api/orders/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.orders = response.data.data;
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
    getIsFinishedColor(isfinished) {
      if (isfinished == 1) return "green";
      else return "red";
    },
    finish(index) {
      // alert(index);
      let order = this.orders[index];
      axios.patch(`/api/orders/${order.id}/finish`).then((response) => {
        this.orders[index].is_finished = 1;
        this.$forceUpdate();
      });
    },
    showEditingDialog(order) {
      this.editingOrder = order;
      this.showEditOrderDialog = true;
    },
    showAddingDialog() {
      this.addingOrder = {
        certificate_id: "",
        user_id: "",
        date: "",
        time: "",
        is_finished: "",
      };
      this.showAddOrderDialog = true;
    },
    closeEditing(order) {
      this.showEditOrderDialog = false;
      this.editingOrder = null;
    },
    closeAdding(order) {
      this.showAddOrderDialog = false;
      this.addingOrder = null;
    },
  },
};
</script>