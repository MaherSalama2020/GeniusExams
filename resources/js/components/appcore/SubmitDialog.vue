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
          <v-btn
            small
            color="orange white--text"
            @click="closeSubmitDialog"
            class="hovered-button"
          >Cancel</v-btn>
          <v-btn small color="orange white--text" @click="submit" class="hovered-button">OK</v-btn>
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
<style scoped>
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>