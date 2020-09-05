<template>
  <v-row justify="center">
    <v-dialog v-model="showTimeUpDialog" persistent max-width="400">
      <v-card>
        <v-card-title>{{title}}</v-card-title>

        <v-card-text v-if="result>=passing_rate && type=='Exam'">
          <v-icon class="mr-1" color="teal">mdi-check</v-icon>Congratulations!!
          <span class="=ml-1">{{ Number.parseFloat(result).toFixed(2)}}%</span>
        </v-card-text>
        <v-card-text v-if="result<passing_rate && type=='Exam'">
          <v-icon class="mr-1" color="error">mdi-alert-circle</v-icon>Sorry!!
          <span class="=ml-1">{{ Number.parseFloat(result).toFixed(2)}}%</span>
        </v-card-text>
        <v-card-text v-if="text1 && type!='Exam'">{{text1}}</v-card-text>
        <v-card-text v-if="text2 && type!='Exam'">{{text2}}</v-card-text>
        <v-card-text v-if="text3 && type!='Exam'">{{text3}}</v-card-text>
        <v-card-text v-if="text4 && type!='Exam'">{{text4}}</v-card-text>

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
    "passing_rate",
    "title",
    "text1",
    "text2",
    "text3",
    "text4",
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
    // submit() {
    //   this.$emit("submit");
    // },
  },
};
</script>