<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <!-- <button class="btn btn-primary" @click="newUser">Add New User</button> -->
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
              :items="users"
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
                <tr>
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.email }}</td>
                  <td>
                    <v-chip
                      :color="getIsAdminColor(item.is_admin)"
                      dark
                    >{{item.is_admin == 1? "Yes" : "No" }}</v-chip>
                  </td>
                  <td>
                    <v-chip
                      :color="getIsAdminColor(item.verified)"
                      dark
                    >{{item.verified == 1? "Yes" : "No" }}</v-chip>
                  </td>
                  <td>{{new Date(item.created_at).toLocaleDateString()}}</td>
                  <td>{{item.orders.length}}</td>
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
                <tr :colspan="headers.length" v-if="item.orders.length>0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">{{item.name}}</span>
                  </td>
                </tr>
                <tr v-if="item.orders.length>0">
                  <th />
                  <th />
                  <th>Certificate</th>
                  <th>Image</th>
                  <th>DateTime</th>
                  <th>Finished?</th>
                  <th />
                  <th />
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + order.id"
                  v-for="order in item.orders"
                >
                  <td></td>
                  <td></td>
                  <td>{{certificates[certificates.findIndex(x => x.id ===order.certificate_id)].name}}</td>
                  <td>
                    <v-img
                      :src="certificates[certificates.findIndex(x => x.id ===order.certificate_id)].image"
                      max-height="45"
                      max-width="45"
                      contain
                      class="py-3 px-3"
                    ></v-img>
                  </td>
                  <td>
                    <v-icon>mdi-calendar</v-icon>
                    {{new Date(order.created_at).toLocaleDateString()}}
                    <!-- <v-icon>mdi-clock</v-icon>
                    {{ order.time }}-->
                  </td>
                  <td>
                    <v-chip
                      :color="getIsAdminColor(order.is_finished)"
                      dark
                    >{{order.is_finished == 1? "Yes" : "No" }}</v-chip>
                  </td>
                  <td />
                  <td />
                </tr>

                <tr v-if="item.orders.length==0" :colspan="headers.length">
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
                    >No Orders yet for "{{ item.name }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="8" class="blue lighten-5" />
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
              @input="fetchUsers"
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
                    @change="fetchUsers(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      certificates: [],
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
          text: "User Name",
          value: "name",
          groupable: false,
          sortable: true,
        },
        {
          text: "User Email",
          value: "email",
          groupable: false,
          sortable: true,
        },
        {
          text: "Admin?",
          value: "is_admin",
          groupable: false,
          sortable: true,
        },
        {
          text: "Verified?",
          value: "verified",
          groupable: false,
          sortable: true,
        },
        {
          text: "Joined at",
          value: "created_at",
          groupable: false,
          sortable: true,
        },
        {
          text: "Total Orders",
          value: "totalorders",
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
  beforeMount() {
    axios.get("/api/users/").then((response) => (this.users = response.data));
    axios
      .get("/api/certificates/")
      .then((response) => (this.certificates = response.data));
  },
  methods: {
    fetchUsers(page_number) {
      let page_url = "/api/users/paginate?page=" + page_number;
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
    getIsAdminColor(isAdmin) {
      if (isAdmin == 1) return "green";
      else return "red";
    },
  },
};
</script>