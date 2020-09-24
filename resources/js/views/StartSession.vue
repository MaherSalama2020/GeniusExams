<template>
  <div>
    <!-- <p>SelectedExam:{{ selectedExam || 'null' }}</p> -->
    <!-- <p>SelectedExamName:{{ selectedExamName || 'null' }}</p> -->
    <!-- <p>Duration:{{ duration || 'null' }}</p> -->
    <!-- <p>isLoading:{{ isLoading || 'null' }}</p> -->
    <!-- <p>ExamQuestions:{{ examQuestions || 'null' }}</p> -->
    <!-- <p>CertificateExams:{{ exams || 'null' }}</p> -->
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <template>
      <div
        class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
      >
        <v-col>
          <v-row class="ml-14" no-gutters>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn x-small icon @click="linkToHome" v-bind="attrs" v-on="on">
                  <v-icon color="white">home</v-icon>
                </v-btn>
              </template>
              home
            </v-tooltip>
            <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
            <span class="activeBreadcrumb" @click="linkToOrders">Orders</span>
            <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
            <span class="inactiveBreadcrumb">{{certificate_name}}</span>
          </v-row>
          <v-row class="ml-15 mt-3" no-gutters>
            <h3 class="white--text">Orders</h3>
          </v-row>
        </v-col>
      </div>
      <v-row justify="center" align="center" no-gutters>
        <v-col class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
          <vueper-slides
            3d
            :arrows="true"
            arrows-outside
            bullets-outside
            :touchable="false"
            class="no-shadow"
            fixed-height="200px"
            fixed-width="400px"
          >
            <vueper-slide v-for="exam in exams " :key="exam.id">
              <template v-slot:content>
                <transition appear name="slide-in">
                  <v-card rounded class="ma-0" height="200" width="400">
                    <v-card-title>
                      <v-chip :color="getTypeColor(exam.type)" dark>{{ exam.type}}</v-chip>
                      <v-chip
                        color="orange"
                        dark
                        v-if="exam.passing_rate"
                        class="ml-1"
                      >Passing rate: {{ exam.passing_rate}}%</v-chip>
                    </v-card-title>
                    <v-card-text class="textover font-weight-black">
                      <h4>{{exam.name}}</h4>
                      <v-row justify="end" align="center">
                        <span class="ml-auto textover caption font-weight-light mt-0 mb-0">
                          <v-icon small>mdi-counter</v-icon>
                          <span class="small-text">{{exam.questions.length}}</span>
                          <span class="small-text" v-if="exam.questions.length>1">Questions</span>
                          <span class="small-text" v-if="exam.questions.length<=1">Question</span>
                        </span>
                        <span class="ml-2 mr-2 textover caption font-weight-light mt-0 mb-0">
                          <v-icon small>mdi-clock</v-icon>
                          <span class="small-text">{{exam.duration}}</span>
                          <span class="small-text" v-if="exam.duration>1">Minutes</span>
                          <span class="small-text" v-if="exam.duration<=1">Minute</span>
                        </span>
                      </v-row>
                    </v-card-text>
                    <v-card-actions class="mb-0 mt-0">
                      <span style="color:#FF9800;">{{certificate.rate}}</span>
                      <v-rating
                        dense
                        v-model="certificate.rate"
                        background-color="grey darken-1"
                        empty-icon="$ratingFull"
                        color="#FF9800"
                        half-increments
                        readonly
                        size="20"
                      ></v-rating>
                      <v-spacer />
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            fab
                            small
                            dark
                            @click="ExamSelected(exam.id)"
                            v-bind="attrs"
                            v-on="on"
                            class="hovered-button-black"
                          >
                            <v-icon class="arrow">double_arrow</v-icon>
                          </v-btn>
                        </template>
                        Start Session
                      </v-tooltip>
                    </v-card-actions>
                  </v-card>
                </transition>
              </template>
            </vueper-slide>
          </vueper-slides>
        </v-col>
      </v-row>
      <!-- <v-sheet class="mx-auto" max-width="auto">
          <v-slide-group
            v-if="exams.length>0"
            class="pa-1"
            :mandatory="true"
            :show-arrows="true"
            :center-active="true"
            prev-icon="mdi-arrow-left-thick mdi-48px"
            next-icon="mdi-arrow-right-thick mdi-48px"
          >
            <v-slide-item v-for="exam in exams " :key="exam.id" v-slot:default="{ active, toggle}">
              <v-col>
                <v-row class="mb-0" no-gutters>
                  <transition appear name="slide-in">
                    <v-card
                      :color="active ? 'orange lighten-5' : 'white'"
                      rounded
                      class="ma-1"
                      height="175"
                      width="300"
                      @click="toggle"
                    >
                      <v-card-title>
                        <v-chip :color="getTypeColor(exam.type)" dark>{{ exam.type}}</v-chip>
                        <v-chip
                          color="orange"
                          dark
                          v-if="exam.passing_rate"
                          class="ml-1"
                        >Passing rate: {{ exam.passing_rate}}%</v-chip>
                      </v-card-title>
                      <v-card-text class="textover">{{exam.name}}</v-card-text>

                      <v-card-actions class="mb-0 mt-0">
                        <v-spacer />
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn
                              fab
                              small
                              :color="active ? 'orange' : 'orange'"
                              dark
                              @click="ExamSelected(exam.id)"
                              v-bind="attrs"
                              v-on="on"
                            >
                              <v-icon class="arrow">double_arrow</v-icon>
                            </v-btn>
                          </template>
                          Start Session
                        </v-tooltip>
                      </v-card-actions>
                    </v-card>
                  </transition>
                </v-row>
              </v-col>
            </v-slide-item>
          </v-slide-group>
      </v-sheet>-->
    </template>
    <!-- for exam session done -->
    <!-- <hr class="hr mt-0 mb-0" /> -->
    <template>
      <v-card flat>
        <v-card-text v-if="sessions.length>0">
          <v-row no-gutters align="center" justify="center">
            <v-slide-y-transition>
              <v-row
                no-gutters
                align="center"
                justify="center"
                v-if="responseReady&&responseMessageStatus"
              >
                <v-col>
                  <v-alert
                    type="success"
                    border="left"
                    colored-border
                    color="success accent-4"
                    elevation="2"
                    dismissible
                  >
                    {{responseMessage}}
                    <v-btn
                      text
                      color="success"
                      class="ml-2 hovered-button-undeline"
                      @click="alertUserReviewsDialog"
                    >See your reviews</v-btn>
                  </v-alert>
                </v-col>
              </v-row>
            </v-slide-y-transition>
            <v-slide-y-transition>
              <v-row
                no-gutters
                align="center"
                justify="center"
                v-if="responseReady&&!responseMessageStatus"
              >
                <v-col>
                  <v-alert
                    type="error"
                    border="left"
                    colored-border
                    color="error accent-4"
                    elevation="2"
                    dismissible
                  >{{responseMessage}}</v-alert>
                </v-col>
              </v-row>
            </v-slide-y-transition>
          </v-row>
          <v-row no-gutters align="center" justify="center" v-if="!rated">
            <v-form
              v-model="isValid"
              ref="reviewForm"
              v-on:submit.prevent="sendReview"
              class="col-md-12"
            >
              <v-row no-gutters align="center" justify="center">
                <v-col cols="12" md="1">
                  <span class="text--lighten-2 caption mr-2">Rate: ({{ default_rating }})</span>
                </v-col>
                <v-col cols="12" md="2">
                  <v-rating
                    dense
                    v-model="default_rating"
                    background-color="grey darken-1"
                    empty-icon="$ratingFull"
                    color="#FF9800"
                    half-increments
                    hover
                    size="20"
                    @input="callToggleReview"
                  ></v-rating>
                </v-col>
              </v-row>
              <v-slide-y-transition>
                <v-row v-show="ToggleReview" align="center" justify="center">
                  <v-col class="col-md-8">
                    <v-textarea
                      label="Enter Review"
                      outlined
                      required
                      dense
                      v-model="review"
                      :rules="requiredRules"
                      color="purple"
                      no-resize
                    >
                      <template v-slot:append>
                        <v-btn
                          small
                          @click="sendReview"
                          color="orange white--text"
                          :disabled="!isValid"
                          class="hovered-button"
                        >Submit</v-btn>
                      </template>
                    </v-textarea>
                  </v-col>
                </v-row>
              </v-slide-y-transition>
            </v-form>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-row no-gutters align="center" justify="start">
            <v-col col2="12" md="1">
              <v-tooltip top v-if="panel.length==0&&sessions.length>0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="ml-4 hovered-button-black"
                    fab
                    small
                    dark
                    @click="all"
                    color="black white--text"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>add</v-icon>
                  </v-btn>
                </template>
                Expand All
              </v-tooltip>
              <v-tooltip top v-if="panel.length>0&&sessions.length>0">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="ml-4 hovered-button-black"
                    fab
                    small
                    dark
                    @click="none"
                    color="black  white--text"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>mdi-minus</v-icon>
                  </v-btn>
                </template>
                Collapse All
              </v-tooltip>
            </v-col>
          </v-row>
        </v-card-actions>

        <v-card-text>
          <v-expansion-panels color="warning" v-model="panel" multiple popout :hover="true">
            <v-expansion-panel
              v-for="(session,index) in sessions"
              :key="session.id"
              active-class="orange lighten-5 underline-from-top"
            >
              <v-expansion-panel-header
                :disable-icon-rotate="session.exam.type=='Exam'"
                class="expansion-panel-header font-weight-bold"
              >
                <span class="expansion-panel-title">
                  {{index+1}}-&nbsp;{{session.exam.name}}&nbsp;
                  <span
                    v-if="session.exam.type=='Exam'"
                    style="color:red"
                  >{{session.exam.type}}</span>
                  <span
                    v-if="session.exam.type=='Practical Test'"
                    style="color:green"
                  >{{session.exam.type}}</span>
                </span>
                <v-spacer />
                {{session.humans}}
                <!-- {{new Date(session.created_at).toLocaleDateString()}} -->
                <template v-slot:actions>
                  <v-icon
                    class="ml-2"
                    color="orange"
                    v-if="session.exam.type=='Practical Test'"
                  >mdi-arrow-down-drop-circle-outline</v-icon>
                  <v-icon
                    class="ml-2"
                    color="teal"
                    v-if="session.exam.type=='Exam'&&session.result>=session.exam.passing_rate && session.exam.type=='Exam'"
                  >mdi-check</v-icon>
                  <v-icon
                    class="ml-2"
                    color="error"
                    v-if="session.exam.type=='Exam'&&session.result<session.exam.passing_rate && session.exam.type=='Exam'"
                  >mdi-alert-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content
                color="white"
                class="black--text underline-from-top expansion-panel-text"
              >
                <!-- <v-chip :color="getTypeColor(session.exam.type)" dark>{{ session.exam.type}}</v-chip> -->
                <span>
                  Result:
                  <v-chip color="orange" dark>{{session.result}}%</v-chip>
                </span>
                <span v-if="session.exam.type=='Exam'">
                  Passing Rate:
                  <v-chip color="orange" dark>{{session.exam.passing_rate}}%</v-chip>
                </span>
                <SessionAnswers
                  :answers="session.answers"
                  :certificate_id="session.certificate_id"
                  :exam_id="session.exam_id"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-card>
    </template>
    <SessionDialog
      :certificate_id="certificate_id"
      :exam_id="selectedExam"
      :type="type"
      :passing_rate="passing_rate"
      :isLoading="isLoading"
      :showSessionDialog="showSessionDialog"
      :examQuestions="examQuestions"
      :examName="selectedExamName"
      :duration="duration"
      @closeSessionDialog="closeSessionDialog"
    />
    <UserReviewsDialog
      :showUserReviewsDialog="showUserReviewsDialog"
      @closeUserReviewsDialog="closeUserReviewsDialog"
      :userReviews="userReviews"
    />
  </div>
</template>
<script>
import SessionDialog from "../components/appcore/SessionDialog";
import SessionAnswers from "../components/appcore/SessionAnswers";
import UserReviewsDialog from "../components/appcore/UserReviewsDialog";

import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
export default {
  props: ["isLoggedIn"],
  data() {
    return {
      ToggleReview: false,
      responseReady: false,
      responseMessage: "",
      responseMessageStatus: "",
      isValid: false,
      review: "",
      panel: [],
      sessions: [],
      exams: [],
      examQuestions: [],
      selectedExam: 0,
      selectedExamName: "",
      isLoading: "",
      spinner: false,
      showSessionDialog: false,
      duration: -1,
      type: "",
      passing_rate: "",
      certificate: {},
      certificate_id: 0,
      certificate_name: "",
      default_rating: 2.5,
      userReviews: [],
      user: {},
      requiredRules: [(v) => !!v || "required!!"],
      rated: false,
      showUserReviewsDialog: false,
    };
  },
  components: {
    SessionDialog,
    SessionAnswers,
    VueperSlides,
    VueperSlide,
    UserReviewsDialog,
  },
  beforeMount() {
    if (localStorage.getItem("genius.jwt") != null) {
      this.user = JSON.parse(localStorage.getItem("genius.user"));
    }

    let id = this.$route.params.id;
    let certificate_id = id;
    this.certificate_id = id;
    axios.get(`/api/certificates/${id}`).then((response) => {
      this.certificate_name = response.data.name;
      this.certificate = response.data;
      axios.post("/api/reviews/crate", { certificate_id }).then((res) => {
        this.certificate.rate = res.data.rate;
      });
    });
    axios.post("/api/certificates/cexams/", { id }).then((response) => {
      this.exams = response.data;
    });
    axios
      .post("/api/sessions/csessions/", { certificate_id })
      .then((response) => {
        this.sessions = response.data;
      });
  },
  // watch: {
  //   selectedExam: {
  //     handler() {

  //     },
  //   },
  // },

  methods: {
    callToggleReview() {
      this.ToggleReview = !this.ToggleReview;
    },
    linkToHome() {
      this.$emit("linkToHome");
    },
    linkToOrders() {
      this.$emit("linkToOrders");
    },
    fetchSessions() {
      let certificate_id = this.certificate_id;

      axios
        .post("/api/sessions/csessions/", { certificate_id })
        .then((response) => {
          this.sessions = response.data;
        });
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    resetExam() {},
    ExamSelected(id) {
      this.selectedExam = id;
      // alert(id);
      this.isLoading = "YES";
      this.spinner = true;
      this.exams.forEach((e) => {
        if (e.id == this.selectedExam) {
          this.duration = e.duration;
          this.type = e.type;
          this.passing_rate = e.passing_rate;
          this.selectedExamName = e.name;
        }
      });
      axios
        .post("/api/exams/equestions", { id })
        .then((response) => {
          this.isLoading = "NO";
          this.spinner = false;
          this.examQuestions = response.data;
          this.showSessionDialog = true;
        })
        .catch((err) => {
          this.isLoading = "NO";
          this.spinner = false;
        });
    },
    closeSessionDialog() {
      this.fetchSessions();
      // this.examQuestions = [];
      // this.selectedExam = 0;
      // this.selectedExamName = "";
      // this.examQuestions = [];
      this.isLoading = "NO";
      this.spinner = false;
      this.duration = -1;
      this.showSessionDialog = false;
    },
    // Create an array the length of our items
    // with all values as true
    all() {
      this.panel = Object.keys(this.sessions).map((k, i) => i);
    },
    // Reset the panel
    none() {
      this.panel = [];
    },
    sendReview() {
      this.responseReady = false;
      this.responseMessage = "";
      this.responseMessageStatus = false;
      this.spinner = true;
      let certificate_id = this.certificate.id;
      let value = this.default_rating;
      let review = this.review;
      axios
        .post("/api/reviews", { certificate_id, review, value })
        .then((response) => {
          this.rated = true;
          this.spinner = false;
          this.responseReady = true;
          this.responseMessageStatus = response.data.status;
          this.responseMessage = response.data.message;
          axios
            .post("/api/reviews/crate", { certificate_id })
            .then((res) => {
              this.certificate.rate = res.data.rate;
              this.spinner = false;
            })
            .catch(() => {
              this.spinner = false;
            });
          this.$refs.reviewForm.reset();
        })
        .catch((error) => {
          this.spinner = false;
          this.responseReady = true;
          this.responseMessageStatus = false;
          this.responseMessage = "Something went wrong!!";
        });
    },
    alertUserReviewsDialog() {
      this.spinner = true;
      let id = this.certificate.id;
      axios
        .post(`/api/certificates/creviews`, { id })
        .then((response) => {
          let filteredReviews = response.data.filter(
            (review) =>
              review.user_id == this.user.id &&
              review.certificate_id == this.certificate.id
          );
          this.userReviews = filteredReviews;
          // console.log(this.certificate.reviews);
          this.spinner = false;
          this.showUserReviewsDialog = true;
        })
        .catch((error) => {
          this.spinner = false;
        });
    },
    closeUserReviewsDialog() {
      this.showUserReviewsDialog = false;
    },
  },
};
</script>
<style scoped>
.inactiveBreadcrumb {
  color: grey;
}
.activeBreadcrumb {
  color: whitesmoke;
  cursor: pointer;
}
.activeBreadcrumb:hover {
  text-decoration: underline;
}
.hero-section {
  background: #ababab;
  height: 20vh;
  align-items: center;
  margin-bottom: 20px;
  margin-top: -20px;
}
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* number of lines to show */
  -webkit-box-orient: vertical;
}
.arrow:hover {
  animation: moveRightToLeft -2s 900ms infinite linear;
}
@keyframes moveRightToLeft {
  0% {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(4px, 0);
  }
  50.1% {
    transform: translate(-2px, 0);
  }
  100% {
    transform: translate(4px, 0);
  }
}
.hr {
  border: 0;
  height: 3px;
  background-image: linear-gradient(
    to right,
    rgba(0, 0, 0, 0),
    rgba(0, 0, 0, 0.75),
    rgba(0, 0, 0, 0)
  );
}
/*
    This class would be automatically added to the element 
    before the element is inserted and removed one frame 
    after the element is inserted. 
    Basically it’s the initial state from the element.
  */
.slide-in-enter {
  opacity: 0;
  transform: scale(0.5);
}
/*
    This class can be used to define the duration, 
    delay and easing curve for the entering transition.
    --i is the CSS variable from the Card Component (current index)
    With this you have a staggered delay.
  */
.slide-in-enter-active {
  transition: all 0.4s ease;
  transition-delay: calc(0.1s * var(--i));
}
.small-text {
  font-size: 11px;
}
.hovered-button-black:hover {
  background-color: white !important;
  color: black !important;
  border: 1px solid black;
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
.hovered-button-undeline:hover {
  text-decoration: underline;
  text-decoration-color: green;
}
</style>