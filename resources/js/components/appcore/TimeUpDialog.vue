<template>
  <v-row justify="center">
    <v-dialog v-model="showTimeUpDialog" persistent max-width="400">
      <v-card>
        <v-card-title>{{title}}</v-card-title>
        <hr class="hr mt-0 mb-0" />
        <v-card-title>
          <v-row align="center" justify="center">
            <v-icon color="error" x-large>timer_off</v-icon>
          </v-row>
        </v-card-title>
        <v-card-title>
          {{examName}}&nbsp;
          <v-chip :color="getTypeColor(type)" dark>{{ type}}</v-chip>
          <v-chip
            color="orange"
            dark
            v-if="passing_rate"
            class="ml-1"
          >Passing rate: {{ passing_rate}}%</v-chip>
        </v-card-title>

        <!-- <v-card-text v-if="result>=passing_rate && type=='Exam'">
          <v-icon class="mr-1" color="teal">mdi-check</v-icon>Congratulations!!
          <span class="=ml-1">{{ Number.parseFloat(result).toFixed(2)}}%</span>
        </v-card-text>
        <v-card-text v-if="result<passing_rate && type=='Exam'">
          <v-icon class="mr-1" color="error">mdi-alert-circle</v-icon>Sorry!!
          <span class="=ml-1">{{ Number.parseFloat(result).toFixed(2)}}%</span>
        </v-card-text>-->
        <!-- <v-card-text v-if="text1 && type!='Exam'">{{text1}}</v-card-text>
        <v-card-text v-if="text2 && type!='Exam'">{{text2}}</v-card-text>
        <v-card-text v-if="text3 && type!='Exam'">{{text3}}</v-card-text>
        <v-card-text v-if="text4 && type!='Exam'">{{text4}}</v-card-text>-->

        <v-card-actions>
          <v-spacer></v-spacer>
          <!-- <v-btn color="green darken-1" text @click="showTimeUpDialog = false">Disagree</v-btn> -->
          <v-btn color="orange white--text " @click="closeTimeUpDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: [
    "showTimeUpDialog",
    "type",
    "examName",
    "passing_rate",
    "title",
    "result",
  ],
  data() {
    return {};
  },
  mounted() {},
  methods: {
    closeTimeUpDialog() {
      // this.showTimeUpDialog = false;
      this.$emit("closeTimeUpDialog");
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    // submit() {
    //   this.$emit("submit");
    // },
  },
};
</script>
<style scoped>
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