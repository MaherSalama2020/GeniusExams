<template>
  <v-footer dark padless>
    <v-card class="flex" flat tile>
      <v-card-title flat class="orange">
        <strong class="small-medium-title ml-15">Get connected with us on social networks!</strong>

        <v-spacer></v-spacer>
        <span class="mr-15">
          <a :href="icon.link" target="_blank" v-for="icon in icons" :key="icon.icon">
            <v-btn class="mx-4 white--text hovered-button" dark icon>
              <v-icon size="24px">{{ icon.icon}}</v-icon>
            </v-btn>
          </a>
        </span>
      </v-card-title>

      <v-card-text class="py-2 white--text text-center">
        2019- {{ new Date().getFullYear() }} Powered By
        <strong>
          <u>
            <a href="http://google.com" target="_blank">Genius</a>
          </u>
        </strong>
      </v-card-text>
    </v-card>
  </v-footer>
</template>
<script>
export default {
  data: () => ({
    icons: [
      { icon: "mdi-facebook", link: "https://github.com" },
      { icon: "mdi-twitter", link: "https://github.com" },
      { icon: "mdi-linkedin", link: "https://github.com" },
      { icon: "mdi-instagram", link: "https://github.com" },
    ],
  }),
};
</script>
<style scoped>
/* unvisited link */
a:link {
  color: white;
}

/* visited link */
a:visited {
  color: white;
}

/* mouse over link */
a:hover {
  color: coral;
}

/* selected link */
a:active {
  color: coral;
}
.hovered-button:hover {
  transform: scale(1.5);
}
</style>