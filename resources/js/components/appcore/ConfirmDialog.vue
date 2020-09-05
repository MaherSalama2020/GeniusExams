<template>
  <v-row justify="center">
    <v-dialog v-model="confirmdialog" max-width="290" persistent>
      <v-card>
        <v-card-title v-if="control=='closeSession'">Close session</v-card-title>
        <v-card-title v-else>
          <v-icon>warning</v-icon>Delete
        </v-card-title>

        <v-card-text>Are you sure?</v-card-text>
        <v-card-text
          v-if="control=='closeSession'"
        >If you close your session you will lose all your answers.</v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="orange  white--text" @click="Cancel">Cancel</v-btn>
          <v-btn color="orange" text @click="OK">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["confirmdialog", "control"],
  data() {
    return {};
  },
  methods: {
    OK() {
      this.$emit("OK");
    },
    Cancel() {
      this.$emit("Cancel");
    },
  },
};
</script>