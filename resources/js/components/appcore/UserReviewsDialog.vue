<template>
  <v-row justify="center">
    <v-dialog v-model="showUserReviewsDialog" persistent scrollable>
      <v-card>
        <v-card-title>
          <span v-if="userReviews.length>0">Your Reviews for {{userReviews[0].cName}}</span>
          <span v-if="userReviews.length==0">Your Reviews</span>
          <v-spacer />
          <span icon @click="closeUserReviewsDialog">
            <v-icon class="close">mdi-close</v-icon>
          </span>
        </v-card-title>
        <!-- <v-divider></v-divider> -->
        <v-card-text style="height: 300px;" v-if="userReviews.length>0">
          <table class="table">
            <tbody v-if="userReviews">
              <tr v-for="(review) in userReviews" :key="review.id+'-userReviews'">
                <!-- <td>{{ review.certificate.name }}</td> -->
                <td>
                  <span style="color:#FF9800">{{ review.value }}</span>
                </td>
                <td>
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
                </td>
                <td class="text-truncate">{{ review.humans}}</td>
                <td class="text-truncate">{{ review.review}}</td>
              </tr>
            </tbody>
          </table>
        </v-card-text>
        <v-card-text v-if="userReviews.length==0" style="height: 300px;">
          <v-divider class="mt-0 mb-2"></v-divider>You haven't added any reviews yet.
        </v-card-text>
        <!-- <v-divider></v-divider> -->
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="orange white--text"
            @click="closeUserReviewsDialog"
            class="hovered-button"
          >OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showUserReviewsDialog", "userReviews"],
  data() {
    return {};
  },
  methods: {
    closeUserReviewsDialog() {
      this.$emit("closeUserReviewsDialog");
    },
  },
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
td {
  height: 35 !important;
  vertical-align: middle;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>
