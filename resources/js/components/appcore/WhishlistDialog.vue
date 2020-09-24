<template>
  <v-row justify="center">
    <v-dialog v-model="showWhishlistDialog" persistent scrollable max-width="700px">
      <v-card>
        <v-card-title>
          Whish List
          <v-spacer />
          <span icon @click="closeWhishlistDialog">
            <v-icon class="close">mdi-close</v-icon>
          </span>
        </v-card-title>
        <!-- <v-divider></v-divider> -->
        <v-card-text v-if="whishlist.length>0" style="height: 300px;">
          <table class="table">
            <tbody v-if="whishlist">
              <tr v-for="(item) in whishlist" :key="item.id+'-whishlist'">
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
                        @click="removeFromWhishlist(item.id)"
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon>mdi-delete-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Remove from Whish List</span>
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
        <v-card-text v-if="whishlist.length==0" style="height: 300px;">
          <v-divider class="mt-0 mb-2"></v-divider>You haven't added any certificates to your wishlist.
        </v-card-text>
        <!-- <v-divider></v-divider> -->
        <v-card-actions>
          <v-spacer />
          <v-btn color="orange white--text" @click="closeWhishlistDialog" class="hovered-button">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showWhishlistDialog", "certificates"],
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
      return this.whishlist.reduce((acc, cur) => acc + cur.price, 0);
    },
    /* snip */
    whishlist() {
      if (!this.showWhishlistDialog) {
        return [];
      }
      return this.$store.getters.inWhishlist.map((item) => {
        return this.certificates.find((certificate) => {
          return item === certificate.id;
        });
      });
    },
  },
  methods: {
    closeWhishlistDialog() {
      this.$emit("closeWhishlistDialog");
    },
    addToWhishlist(certificate_id) {
      this.$store.dispatch("addToWhishlist", certificate_id);
    },
    removeFromWhishlist(certificate_id) {
      this.$store.dispatch("removeFromWhishlist", certificate_id);
    },
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
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
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>
