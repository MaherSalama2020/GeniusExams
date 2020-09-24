<template>
  <v-row justify="center">
    <v-dialog v-model="showImageDialog" width="600px" persistent>
      <v-card>
        <v-card-title>
          <span>Image</span>
          <v-spacer />
          <span @click="closeImageDialog">
            <v-icon class="close" medium>close</v-icon>
          </span>
        </v-card-title>
        <v-card-text>
          <v-img :src="image" />
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="orange white--text hovered-button" @click="closeImageDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showImageDialog", "image"],
  data() {
    return {
      dialog: false,
    };
  },
  methods: {
    closeImageDialog() {
      this.$emit("closeImageDialog");
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
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>