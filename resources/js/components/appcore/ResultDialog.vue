<template>
  <v-row justify="center">
    <v-dialog v-model="alertResultDialog" width="auto" persistent max-width="400">
      <v-card class="mx-auto">
        <v-card-title>
          <span>Result</span>
        </v-card-title>
        <hr class="hr mt-0 mb-0" />
        <v-card-title>
          <v-row align="center" justify="center" v-if="type=='Exam'">
            <v-icon color="success" v-if="result>=passing_rate" x-large>sentiment_very_satisfied</v-icon>
            <v-icon color="error" v-if="result<passing_rate" x-large>sentiment_very_dissatisfied</v-icon>
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
        <v-card-text>Your result: {{result.toFixed(2)}}%</v-card-text>
        <v-card-text v-if="text1">{{text1}}</v-card-text>
        <v-card-text v-if="text2">{{text2}}</v-card-text>
        <v-card-text v-if="text3">{{text3}}</v-card-text>
        <v-card-text v-if="text4">{{text4}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange white--text" @click="closeResultDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: [
    "alertResultDialog",
    "result",
    "passing_rate",
    "type",
    "examName",
    "text1",
    "text2",
    "text3",
    "text4",
  ],
  data() {
    return {};
  },
  methods: {
    closeResultDialog() {
      this.$emit("closeResultDialog");
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
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