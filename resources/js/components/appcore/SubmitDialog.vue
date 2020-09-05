<template>
  <v-row justify="center">
    <v-dialog v-model="alertSubmitDialog" width="auto" persistent max-width="340px">
      <v-card class="mx-auto">
        <v-card-title>
          <span>Submit your answers</span>
        </v-card-title>
        <v-card-text>Are you sure to finish your exam?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange" text @click="closeSubmitDialog">Cancel</v-btn>
          <v-btn color="orange white--text" @click="submit">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["alertSubmitDialog"],
  data() {
    return {};
  },
  methods: {
    closeSubmitDialog() {
      this.$emit("closeSubmitDialog");
    },
    submit() {
      this.$emit("submit");
    },
  },
};
</script>