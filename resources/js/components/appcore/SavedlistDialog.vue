<template>
  <v-row justify="center">
    <v-dialog v-model="showSavedlistDialog" persistent scrollable max-width="700px">
      <v-card>
        <v-card-title>
          Saved List
          <v-spacer />
          <span icon @click="closeSavedlistDialog">
            <v-icon class="close">mdi-close</v-icon>
          </span>
        </v-card-title>
        <!-- <v-divider></v-divider> -->
        <v-card-text v-if="savedlist.length>0" style="height: 300px;">
          <table class="table">
            <tbody v-if="savedlist">
              <tr v-for="(item) in savedlist" :key="item.id+'-savedlist'">
                <td>
                  <v-img width="75px" :src="item.image"></v-img>
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.price | dollars }}</td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        icon
                        small
                        @click="removeFromSavedlist(item.id)"
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon>mdi-delete-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Remove from Saved List</span>
                  </v-tooltip>
                </td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="addToCart(item.id)" v-bind="attrs" v-on="on">
                        <v-icon>mdi-cart-plus</v-icon>
                      </v-btn>
                    </template>
                    <span>Add to Cart</span>
                  </v-tooltip>
                </td>
              </tr>
            </tbody>
          </table>
        </v-card-text>
        <v-card-text v-if="savedlist.length==0" style="height: 300px;">
          <v-divider class="mt-0 mb-2"></v-divider>You haven't added any certificates to your savedlist.
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
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
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
td {
  height: 75;
  vertical-align: middle;
}
</style>
