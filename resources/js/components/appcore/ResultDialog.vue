<template>
  <v-row justify="center">
    <v-dialog v-model="alertResultDialog" width="auto" persistent max-width="340px">
      <v-card class="mx-auto">
        <v-card-title>
          <span>Result</span>
        </v-card-title>
        <v-card-text>Passing rate: {{passing_rate}}%</v-card-text>
        <v-card-text>Your result: {{result.toFixed(2)}}%</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange" @click="closeResultDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["alertResultDialog", "result", "passing_rate"],
  data() {
    return {};
  },
  methods: {
    closeResultDialog() {
      this.$emit("closeResultDialog");
    },
  },
};
</script>