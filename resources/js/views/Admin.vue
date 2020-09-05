<template>
  <div>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <h2 class="title">Admin Dashboard</h2>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <ul style="list-style-type:none">
            <li class="active">
              <button class="btn" @click="setComponent('main')">Dashboard</button>
            </li>
            <li>
              <button class="btn" @click="setComponent('certificates')">Certificates</button>
            </li>
            <li>
              <button class="btn" @click="setComponent('orders')">Requests</button>
            </li>
            <li>
              <button class="btn" @click="setComponent('exams')">Exams</button>
            </li>
            <li>
              <button class="btn" @click="setComponent('questions')">Questions</button>
            </li>
            <li>
              <button class="btn" @click="setComponent('users')">Users</button>
            </li>
          </ul>
        </div>
        <div class="col-md-9">
          <component :is="activeComponent"></component>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Main from "../components/admin/Main";
import Users from "../components/admin/Users";
import Certificates from "../components/admin/Certificates";
import Exams from "../components/admin/Exams";
import Questions from "../components/admin/Questions";
import Orders from "../components/admin/Orders";

export default {
  data() {
    return {
      user: null,
      activeComponent: null,
    };
  },
  components: {
    Main,
    Users,
    Certificates,
    Exams,
    Questions,
    Orders,
  },
  beforeMount() {
    this.setComponent(this.$route.params.page);
    this.user = JSON.parse(localStorage.getItem("genius.  d fuser"));
    axios.defaults.headers.common["Content-Type"] = "application/json";
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.getItem("genius.jwt");
  },
  methods: {
    setComponent(value) {
      switch (value) {
        case "users":
          this.activeComponent = Users;
          this.$router
            .push({ name: "admin-pages", params: { page: "users" } })
            .catch((err) => {});
          break;
        case "certificates":
          this.activeComponent = Certificates;
          this.$router
            .push({
              name: "admin-pages",
              params: { page: "certificates" },
            })
            .catch((err) => {});
          break;
        case "orders":
          this.activeComponent = Orders;
          this.$router
            .push({
              name: "admin-pages",
              params: { page: "orders" },
            })
            .catch((err) => {});
          break;
        case "exams":
          this.activeComponent = Exams;
          this.$router
            .push({
              name: "admin-pages",
              params: { page: "exams" },
            })
            .catch((err) => {});
          break;
        case "questions":
          this.activeComponent = Questions;
          this.$router
            .push({
              name: "admin-pages",
              params: { page: "questions" },
            })
            .catch((err) => {});
          break;
        default:
          this.activeComponent = Main;
          this.$router.push({ name: "admin" }).catch((err) => {});
          break;
      }
    },
  },
};
</script>

<style scoped>
.hero-section {
  height: 20vh;
  background: #ababab;
  align-items: center;
  margin-bottom: 20px;
  margin-top: -20px;
}
.title {
  font-size: 60px;
  color: #ffffff;
}
</style>