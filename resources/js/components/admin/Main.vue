<template>
  <div>
    <v-container>
      <v-col class>
        <v-row gutters style="height: 150px; ">
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Certificates ({{certificates.length}})</span>
              </v-card-title>
              <!-- <a class="big-text" href="/admin/certificates">Certificates ({{certificates.length}})</a> -->
            </v-card>
          </v-col>
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Requests ({{orders.length}})</span>
              </v-card-title>
              <!-- <a class="big-text" href="/admin/certificates">Certificates ({{certificates.length}})</a> -->
            </v-card>
          </v-col>
        </v-row>
        <v-row gutters style="height: 150px; ">
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Exams ({{exams.length}})</span>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Questions ({{questions.length}})</span>
              </v-card-title>
            </v-card>
          </v-col>
        </v-row>
        <v-row gutters style="height: 150px; ">
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Users ({{users.length}})</span>
              </v-card-title>
            </v-card>
          </v-col>
          <v-col>
            <v-card class="mx-auto" color="orange" dark max-width="400" shaped>
              <v-card-title>
                <v-icon large left>mdi-twitter</v-icon>
                <span class="font-weight-light">Sessions ({{sessions.length}})</span>
              </v-card-title>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null,
      certificates: [],
      orders: [],
      exams: [],
      questions: [],
      users: [],
      sessions: [],
    };
  },
  beforeMount() {
    this.user = JSON.parse(localStorage.getItem("genius.  d fuser"));
    axios.defaults.headers.common["Content-Type"] = "application/json";
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.getItem("genius.jwt");

    axios.get("/api/users/").then((response) => (this.users = response.data));
    axios
      .get("/api/sessions/")
      .then((response) => (this.sessions = response.data));
    axios
      .get("/api/certificates/")
      .then((response) => (this.certificates = response.data));
    axios.get("/api/exams/").then((response) => (this.exams = response.data));
    axios.get("/api/orders/").then((response) => (this.orders = response.data));
    axios
      .get("/api/questions/")
      .then((response) => (this.questions = response.data));
  },
};
</script>

<style scoped>
.big-text {
  font-size: 28px;
  text-decoration: none;
}
.big-text:hover {
  color: skyblue;
}
.exam-box {
  border: 1px solid #cccccc;
  padding: 10px 15px;
  height: 20vh;
}
</style>