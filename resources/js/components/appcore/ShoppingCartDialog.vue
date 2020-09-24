<template>
  <v-row justify="center">
    <v-dialog v-model="showShoppingCartDialog" persistent scrollable max-width="700px">
      <v-card>
        <v-card-title>
          Shopping Cart
          <v-spacer />
          <span icon @click="closeShoppingCartDialog">
            <v-icon class="close">mdi-close</v-icon>
          </span>
        </v-card-title>
        <!-- <v-divider></v-divider> -->
        <v-card-text style="height: 300px;" v-if="shoppingcart.length>0">
          <table class="table">
            <tbody v-if="shoppingcart">
              <tr v-for="(item) in shoppingcart" :key="item.id+'-forsale'">
                <td>
                  <v-img width="75px" :src="item.image"></v-img>
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.price | dollars }}</td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="removeFromCart(item.id)" v-bind="attrs" v-on="on">
                        <v-icon>delete_outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Remove from Cart</span>
                  </v-tooltip>
                </td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="addToWhishlist(item.id)" v-bind="attrs" v-on="on">
                        <v-icon>mdi-heart-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Move to Whish List</span>
                  </v-tooltip>
                </td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn icon small @click="addToSavedlist(item.id)" v-bind="attrs" v-on="on">
                        <v-icon>mdi-clock-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>Move to Saved List</span>
                  </v-tooltip>
                </td>
              </tr>
              <tr>
                <th></th>
                <th>Total:</th>
                <th v-if="shoppingcart">{{ total | dollars }}</th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tbody>
          </table>
        </v-card-text>
        <v-card-text v-if="shoppingcart.length==0" style="height: 300px;">
          <v-divider class="mt-0 mb-2"></v-divider>You haven't added any certificates to your cart.
        </v-card-text>
        <!-- <v-divider></v-divider> -->
        <v-card-actions>
          <v-spacer />
          <v-btn
            color="orange white--text"
            @click="closeShoppingCartDialog"
            class="hovered-button"
          >Keep Shopping</v-btn>
          <!-- <v-btn color="orange darken-1" text @click="checkout">Checkout</v-btn> -->
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  props: ["showShoppingCartDialog", "certificates"],
  filters: {
    dollars: (num) => `$${num}`,
  },
  data() {
    return {};
  },
  computed: {
    total() {
      return this.shoppingcart.reduce((acc, cur) => acc + cur.price, 0);
    },
    /* snip */
    shoppingcart() {
      if (!this.showShoppingCartDialog) {
        return [];
      }
      return this.$store.getters.inCart.map((cartItem) => {
        return this.certificates.find((certificate) => {
          return cartItem === certificate.id;
        });
      });
    },
  },
  methods: {
    closeShoppingCartDialog() {
      this.$emit("closeShoppingCartDialog");
    },
    // checkout() {
    //   this.$emit("checkout");
    // },
    removeFromCart(certificate_id) {
      this.$store.dispatch("removeFromCart", certificate_id);
    },
    addToWhishlist(certificate_id) {
      this.$store.dispatch("addToWhishlist", certificate_id);
    },
    addToSavedlist(certificate_id) {
      this.$store.dispatch("addToSavedlist", certificate_id);
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
