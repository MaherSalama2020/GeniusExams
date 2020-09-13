<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn class="orange white--text" @click="showAddingDialog">Add New Coupon</v-btn>
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
              :items="coupons"
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
              <template v-slot:item="{item,}">
                <tr @dblclick="showEditingDialog(item)">
                  <td>{{ item.id }}</td>
                  <td>{{ item.user.name }}</td>
                  <td>{{ item.user.email }}</td>
                  <td class="text-xs-right">{{ item.code }}</td>
                  <td class="text-xs-right">
                    <v-chip :color="getTypeColor(item.type)" dark>{{ item.type }}</v-chip>
                  </td>
                  <td class="text-xs-right">
                    <span v-if="item.value">{{ item.value }}</span>
                    <span v-if="!item.value">NULL</span>
                  </td>
                  <td class="text-xs-right">
                    <span v-if="item.percent_off">{{ item.percent_off }}%</span>
                    <span v-if="!item.percent_off">NULL</span>
                  </td>
                  <td class="text-xs-right">
                    <v-chip :color="getIsUsedColor(item.is_used)" dark>
                      <span v-if="item.is_used">Yes</span>
                      <span v-if="!item.is_used">No</span>
                    </v-chip>
                  </td>
                  <td>{{new Date(item.created_at).toLocaleDateString()}}</td>
                  <td>{{new Date(item.updated_at).toLocaleDateString()}}</td>

                  <!-- <td>
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
                  </td>-->
                </tr>
              </template>
              <!-- <template v-slot:expanded-item="{ headers, item }">
                <tr :colspan="headers.length" v-if="item.exams.length >0">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <span class="subheading">Users who used in {{item.code}}</span>
                  </td>
                </tr>
                <tr>
                  <th />
                  <th />
                  <th>User Name</th>
                  <th colspan="3">User Email</th>
                  <th></th>
                </tr>
                <tr
                  :colspan="headers.length"
                  :key="item.id+' ' + user.id"
                  v-for="user in item.users"
                >
                  <td></td>
                  <td></td>
                  <td>{{ user.name }}</td>
                  <td colspan="3">{{ user.email }}</td>
                  <td></td>
                </tr>
                <tr v-if="item.users.length<1" :colspan="headers.length">
                  <td :colspan="headers.length" style="text-align: center; vertical-align: middle;">
                    <v-alert
                      slot="no-results"
                      :value="true"
                      color="red lighten-4"
                      icon="warning"
                    >No users used "{{ item.code }}".</v-alert>
                  </td>
                </tr>
                <tr>
                  <td colspan="7" class="blue lighten-5" />
                </tr>
              </template>-->

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
              @input="fetchCoupons"
              class="mt-3 mb-0"
              color="orange"
            ></v-pagination>
            <div class="text-center mt-0">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3 row justify-content-end">
                  <v-subheader>Coupons per page:</v-subheader>
                </div>
                <div class="col-md-1 row justify-content-start">
                  <v-select
                    dense
                    :items="pagescount"
                    name="pagescount"
                    v-model="selected_per_page"
                    @change="fetchCoupons(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
          <CouponModal
            :showCouponDialog="showEditCouponDialog"
            @close="closeEditing"
            :edit="true"
            :coupons="coupons"
            :coupon="editingCoupon"
            :users="users"
            v-show="editingCoupon != null"
          ></CouponModal>
          <CouponModal
            :showCouponDialog="showAddCouponDialog"
            @close="closeAdding"
            :edit="false"
            :coupons="coupons"
            :coupon="addingCoupon"
            :users="users"
            v-show="addingCoupon != null"
          ></CouponModal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CouponModal from "./CouponModal";
export default {
  data() {
    return {
      showEditCouponDialog: false,
      showAddCouponDialog: false,
      dialog: false,
      coupons: [],
      users: [],
      editingCoupon: null,
      addingCoupon: null,
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
        { text: "User Name", value: "user.name" },
        { text: "User Email", value: "user.email" },
        { text: "Code", value: "code" },
        { text: "Type", value: "type" },
        { text: "Value", value: "value" },
        { text: "percent_off", value: "percent_off" },
        { text: "Used?", value: "is_used" },
        { text: "Createdat", value: "created_at" },
        { text: "Updated at", value: "updated_at" },
        { text: "", value: "data-table-expand" },
      ],
    };
  },
  components: { CouponModal },
  beforeMount() {
    axios.get("/api/users/").then((response) => (this.users = response.data));
    this.fetchCoupons(1);
  },

  methods: {
    fetchCoupons(page_number) {
      let page_url = "/api/coupons/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.coupons = response.data.data;
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
    getIsUsedColor(isused) {
      if (isused == 0) return "green";
      else return "red";
    },
    getTypeColor(type) {
      if (type == "fixed") return "red";
      else return "green";
    },

    showEditingDialog(coupon) {
      this.editingCoupon = coupon;
      this.showEditCouponDialog = true;
    },
    showAddingDialog(coupon) {
      this.addingCoupon = {
        user_id: null,
        code: "",
        type: "",
        value: "",
        percent_off: "",
        is_used: 0,
      };
      this.showAddCouponDialog = true;
    },
    closeEditing(coupon) {
      this.showEditCouponDialog = false;
      this.editingCoupon = null;
    },
    closeAdding(coupon) {
      this.showAddCouponDialog = false;
      this.addingCoupon = null;
    },
  },
};
</script>
