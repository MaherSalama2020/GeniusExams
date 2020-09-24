<template>
  <v-main>
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <v-col>
        <v-row class="ml-14" no-gutters>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn x-small icon @click="linkToHome" v-bind="attrs" v-on="on">
                <v-icon color="white">home</v-icon>
              </v-btn>
            </template>
            Home
          </v-tooltip>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">Reviews</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">Reviews</h3>
        </v-row>
      </v-col>
    </div>
    <v-row no-gutters justify="start" align="center">
      <div class="big-title ml-10 border-bottom black--text">Students' Reviews</div>
    </v-row>
    <span class="mx-10">
      <v-row
        justify="center"
        align="center"
        class="my-1 mx-15"
        v-for=" review in reviews"
        :key="review.id+'-reviews'"
      >
        <v-col>
          <v-row no-gutters align="center" class="mb-1">
            BY
            <span class="text--name ml-1">{{review.user.name}}</span>
            <span class="ml-2" v-if="isLoggedIn && user.is_admin">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="hovered-button-scale"
                    small
                    icon
                    @click="deleteReview(review)"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon color="black">mdi-delete-outline</v-icon>
                  </v-btn>
                </template>
                Delete Review
              </v-tooltip>
            </span>
            <span class="ml-2" v-if="isLoggedIn && user.is_admin && !review.happy">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="hovered-button-scale"
                    small
                    icon
                    @click="happyReview(review)"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon color="black">mdi-emoticon-excited</v-icon>
                  </v-btn>
                </template>
                Mark as Happy
              </v-tooltip>
            </span>
            <span class="ml-2" v-if="isLoggedIn && user.is_admin && review.happy">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="hovered-button-scale"
                    small
                    icon
                    @click="happyReview(review)"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon color="black">mdi-emoticon-sad</v-icon>
                  </v-btn>
                </template>
                Mark as Sad
              </v-tooltip>
            </span>
            <span
              style="color:green;"
              class="ml-2"
              v-if="isLoggedIn && user.is_admin && review.happy"
            >Happy Review</span>
            <span
              class="ml-2"
              style="color:red;"
              v-if="isLoggedIn && user.is_admin && !review.happy"
            >Sad Review</span>
            <v-spacer />
            <span class="font-weight-bold">{{review.humans}}</span>
            <span>
              <v-rating
                dense
                v-model="review.value"
                background-color="grey darken-1"
                empty-icon="$ratingFull"
                color="#FF9800"
                half-increments
                readonly
                size="20"
              ></v-rating>
            </span>
          </v-row>
          <v-row no-gutters class="border-left">
            <v-col>
              <v-row no-gutters justify="start" align="center" class="ml-1">
                <span class="text--name ml-1">{{review.certificate.name}}</span>
              </v-row>
              <v-row no-gutters justify="start" align="center" class="ml-1">
                <span class="ml-1">{{review.review}}</span>
              </v-row>
            </v-col>
          </v-row>
          <v-divider class="my-1" />
        </v-col>
      </v-row>
    </span>
    <!-- <v-expansion-panels accordion>
        <v-expansion-panel v-for=" review in reviews" :key="review.id+'-reviews'">
          <v-expansion-panel-header>
            BY
            <span class="text--name ml-1">{{review.user.name}}</span>
            <v-spacer />
            <span>{{review.humans}}</span>
          </v-expansion-panel-header>
          <v-expansion-panel-content class="border-left ml-10 mb-5">{{review.review}}</v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>-->
    <v-pagination
      v-model="pagination.current_page"
      :length="pagination.last_page"
      @input="fetchReviews"
      class="mt-3 mb-0"
      color="orange"
    ></v-pagination>
    <div class="text-center mt-0">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-3 row justify-content-end">
          <v-subheader>Reviews per page:</v-subheader>
        </div>
        <div class="col-md-1 row justify-content-start">
          <v-select
            dense
            :items="pagescount"
            name="pagescount"
            v-model="selected_per_page"
            @change="fetchReviews(1)"
            color="purple"
          ></v-select>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </v-main>
</template>
<script>
export default {
  props: ["isLoggedIn"],
  data() {
    return {
      spinner: false,
      reviews: [],
      pagination: {},
      pagescount: [1, 2, 3, 4, 5, 10, 25, 50, 100],
      selected_per_page: 5,
      user: {},
    };
  },
  mounted() {
    if (this.isLoggedIn) {
      this.user = JSON.parse(localStorage.getItem("genius.user"));
    }
    this.fetchReviews(1);
  },
  methods: {
    fetchReviews(page_number) {
      this.spinner = true;
      let page_url = "/api/reviews/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.reviews = response.data.data;
          this.makePagination(response.data.meta, response.data.links);
          this.spinner = false;
        })
        .catch((error) => {
          console.log(error);
          this.spinner = false;
        });
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
    linkToHome() {
      this.$emit("linkToHome");
    },
    deleteReview(review) {
      this.spinner = true;
      let index = this.reviews.indexOf(review);
      let id = review.id;
      axios
        .delete(`/api/reviews/${id}`)
        .then((response) => {
          let filteredReviews = this.reviews.filter((item) => item.id !== id);
          this.reviews = filteredReviews;
          this.spinner = false;
        })
        .catch((error) => {
          this.spinner = false;
        });
    },
    happyReview(review) {
      this.spinner = true;
      let index = this.reviews.indexOf(review);
      let id = review.id;
      axios
        .put(`/api/reviews/${id}`)
        .then((response) => {
          this.reviews[index].happy = !this.reviews[index].happy;
          this.spinner = false;
        })
        .catch((error) => {
          this.spinner = false;
        });
    },
  },
};
</script>
<style scoped>
.text--name {
  color: coral !important;
  font-size: 1.3rem;
}
.hovered-button-scale:hover {
  transform: scale(1.5);
}
</style>