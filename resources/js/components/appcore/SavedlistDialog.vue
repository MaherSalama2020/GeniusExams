<template>
  <v-row justify="center">
    <v-dialog v-model="showSavedlistDialog" persistent scrollable max-width="700px">
      <v-card>
        <v-card-title>
          Savedlist
          <v-spacer />
          <span icon @click="closeSavedlistDialog">
            <v-icon class="close">mdi-close</v-icon>
          </span>
        </v-card-title>
        <!-- <v-divider></v-divider> -->
        <v-card-text style="height: 300px;">
          <table class="table">
            <tbody v-if="savedlist">
              <tr v-for="(item) in savedlist" :key="item.id+'-savedlist'">
                <td>{{ item.name }}</td>
                <td>{{ item.price | dollars }}</td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        icon
                        small
                        color="error"
                        @click="removeFromSavedlist(item.id)"
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon>watch_later</v-icon>
                      </v-btn>
                    </template>
                    <span>Remove from savedlist</span>
                  </v-tooltip>
                </td>
              </tr>
            </tbody>
          </table>
        </v-card-text>
        <!-- <v-divider></v-divider> -->
        <v-card-actions>
          <v-spacer />
          <v-btn color="orange darken-1" text @click="closeSavedlistDialog">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showSavedlistDialog", "certificates"],
  filters: {
    dollars: (num) => `$${num}`,
  },
  data() {
    return {
      dialog: false,
    };
  },
  computed: {
    total() {
      return this.savedlist.reduce((acc, cur) => acc + cur.price, 0);
    },
    /* snip */
    savedlist() {
      if (!this.showSavedlistDialog) {
        return [];
      }
      return this.$store.getters.inSavedlist.map((item) => {
        return this.certificates.find((certificate) => {
          return item === certificate.id;
        });
      });
    },
  },
  methods: {
    closeSavedlistDialog() {
      this.$emit("closeSavedlistDialog");
    },
    addToSavedlist(certificate_id) {
      this.$store.dispatch("addToSavedlist", certificate_id);
    },
    removeFromSavedlist(certificate_id) {
      this.$store.dispatch("removeFromSavedlist", certificate_id);
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
</style>
