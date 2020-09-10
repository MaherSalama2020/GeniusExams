<template>
  <v-app>
    <PageLoader />
    <Navbar id="navbar" />
    <!-- <Services id="services" /> -->

    <!-- <v-main>
      <router-view></router-view>
    </v-main>-->
  </v-app>
  <!-- <div>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
      <div class="container">
        <router-link :to="{name: 'home'}" class="navbar-brand">Genius</router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto"></ul>
          <ul class="navbar-nav ml-auto">
            <router-link :to="{ name: 'login' }" class="nav-link" v-if="!isLoggedIn">Login</router-link>
            <router-link :to="{ name: 'register' }" class="nav-link" v-if="!isLoggedIn">Register</router-link>
            <span v-if="isLoggedIn">
              <router-link
                :to="{ name: 'userboard' }"
                class="nav-link"
                v-if="user_type == 0"
              >Hi, {{name}}</router-link>
              <router-link
                :to="{ name: 'admin' }"
                class="nav-link"
                v-if="user_type == 1"
              >Hi, {{name}}</router-link>
            </span>
            <li class="nav-link" v-if="isLoggedIn" @click="logout">Logout</li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="py-4">
      <router-view @loggedIn="change"></router-view>
    </main>
  </div>-->
</template>

<script>
import Navbar from "../components/applayout/Navbar";
import PageLoader from "../components/appcore/PageLoader";
export default {
  data() {
    return {
      name: null,
      user_type: 0,
      isLoggedIn: localStorage.getItem("genius.jwt") != null,
    };
  },
  components: {
    Navbar,
    PageLoader,
  },
  mounted() {
    this.setDefaults();
  },
  methods: {
    setDefaults() {
      if (this.isLoggedIn) {
        let user = JSON.parse(localStorage.getItem("genius.user"));
        this.name = user.name;
        this.user_type = user.is_admin;
      }
    },
    change() {
      this.isLoggedIn = localStorage.getItem("genius.jwt") != null;
      this.setDefaults();
    },
    logout() {
      localStorage.removeItem("genius.jwt");
      localStorage.removeItem("genius.user");
      this.change();
      this.$router.push("/").catch((err) => {});
    },
  },
};
</script>