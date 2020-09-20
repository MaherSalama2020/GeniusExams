<template>
  <v-container fluid class="home-hero">
    <v-col pa-5>
      <v-row no-gutters justify="center" align="start">
        <v-col cols="12" sm="auto" md="auto" lg="auto">
          <span class="big-dot mr-2">.</span>
          <span class="big-dot mr-2">.</span>
          <span class="medium-title mr-6 ml-4">BENEFITS</span>
          <span class="big-dot mr-2">.</span>
          <span class="big-dot">.</span>
        </v-col>
      </v-row>
      <v-row no-gutters justify="center" align="start" class="py-0">
        <v-col cols="12" sm="auto" md="auto" lg="auto">
          <span class="x-big-title mr-6 ml-4 mb-0">Reason Why Choose PM-point.</span>
        </v-col>
      </v-row>
      <v-row no-gutters justify="center" align="center">
        <v-col class="col-md-2"></v-col>
        <v-col class="col-md-8">
          <vueper-slides
            :bullets="false"
            :arrows="false"
            class="no-shadow"
            :visible-slides="3"
            :touchable="true"
            slide-multiple
            :gap="0"
            :slide-ratio="1 / 4"
            :dragging-distance="200"
            :breakpoints="breakpoints"
            fixed-height="130px"
            bullets-inside
          >
            <vueper-slide
              v-for="slide in Benefits_slides "
              :key="slide.title+'benefits-vueper-slide'"
            >
              <template v-slot:content>
                <v-card max-width="344" tile flat color="transparent">
                  <v-list-item three-line>
                    <v-list-item-avatar tile color="transparent" left>
                      <v-icon x-large color="white" class="gradient_icon">{{slide.icon}}</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <div style="color:white;" class="slide_title mb-1">{{slide.title}}</div>
                      <div class="description">{{slide.description}}</div>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </template>
            </vueper-slide>
          </vueper-slides>
        </v-col>
        <v-col class="col-md-2"></v-col>
      </v-row>
      <v-row no-gutters justify="center" align="center" class="hidden-sm-and-down">
        <v-col>
          <v-row no-gutters justify="center" align="center">
            <div class="big-title ml-10 border-bottom">Students' Reviews</div>
            <v-spacer />
            <v-btn
              color="orange"
              fab
              large
              @click="$refs.reviewsVueperSlides.previous()"
              class="mr-2 py-6"
            >
              <v-icon x-large color="white">mdi-menu-left</v-icon>
            </v-btn>
            <v-btn
              color="orange"
              fab
              large
              @click="$refs.reviewsVueperSlides.next()"
              class="mr-15 py-6"
            >
              <v-icon x-large color="white">mdi-menu-right</v-icon>
            </v-btn>
          </v-row>
          <v-row>
            <v-col class="col-md-1"></v-col>
            <v-col class="col-md-10">
              <vueper-slides
                v-if="reviews.length>0"
                ref="reviewsVueperSlides"
                :bullets="false"
                :arrows="false"
                class="no-shadow"
                :visible-slides="2"
                autoplay
                :infinite="true"
                :touchable="true"
                slide-multiple
                :gap="0"
                :slide-ratio="1 / 4"
                :dragging-distance="200"
                :breakpoints="breakpoints"
                bullets-inside
              >
                <vueper-slide v-for="review in reviews" :key="review.id+'-reviews-vueper-slide'">
                  <template v-slot:content>
                    <blockquote>
                      <div class="description textover-5">{{review.review}}</div>
                      <div class="description ml-8">{{review.user.name}} | {{review.user.country}}</div>
                    </blockquote>
                  </template>
                </vueper-slide>
              </vueper-slides>
            </v-col>
            <v-col class="col-md-1"></v-col>
          </v-row>
        </v-col>
      </v-row>

      <v-row no-gutters justify="center" align="start" class="hidden-sm-and-down">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn fab class="mt-5 orange" @click="linkToReviews" v-bind="attrs" v-on="on">
              <v-icon large color="white">expand_more</v-icon>
            </v-btn>
          </template>
          Show all Reviews
        </v-tooltip>
      </v-row>
    </v-col>
  </v-container>
</template>

<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
export default {
  data() {
    return {
      reviews: [],
      breakpoints: {
        960: {
          arrows: false,
          bullets: true,
          visibleSlides: 1,
          slideMultiple: 1,
        },
      },
      Benefits_slides: [
        {
          icon: "mdi-camcorder",
          title: "Self placed online video",
          description:
            "Allow you to learn from the comfort of your home and begin and end whenever you want.",
        },
        {
          icon: "mdi-cloud",
          title: "Live Virtual Classroom",
          description:
            "Engage and learn more with these live and highly-interactive classes alongside your peers.",
        },
        {
          icon: "mdi-playlist-check",
          title: "High Quality Questions",
          description:
            "Practice a high quality PMP and PMI-SP Exam questions, and increase your chances to pass the exam from your first trial.",
        },
      ],
    };
  },
  components: { VueperSlides, VueperSlide },
  mounted() {
    axios
      .get("/api/reviews")
      .then((response) => {
        this.reviews = response.data;
      })
      .catch(() => {});
  },
  methods: {
    linkToReviews() {
      this.$emit("linkToReviews");
    },
  },
};
</script>

<style >
.home-hero {
  background: url("/images/backs/1.jpg");
  background-size: cover;
  /* width: 100%; */
  /* height: 100%; */
}
.slide_title {
  color: #fff !important;
  font-size: 1.4vw;
}
.description {
  color: #82b1ff !important;
  font-size: 1.1vw;
}
.gradient_icon {
  background: -moz-linear-gradient(top, #0000ff 0%, #a742c6 100%);
  background: -webkit-linear-gradient(top, #0000ff 0%, #a742c6 100%);
  background: linear-gradient(to bottom, #0000ff 0%, #a742c6 100%);
  -webkit-background-clip: text;
  -moz-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
.border-bottom {
  border-bottom-style: solid !important;
  border-bottom-color: coral !important;
  border-bottom-width: 7px !important;
}
.border-left {
  border-left-style: solid !important;
  border-left-color: coral !important;
  border-left-width: 7px !important;
}
blockquote {
  background: transparent;
  border-left: 10px solid coral;
  margin: 1.5em 10px;
  padding: 0.5em 10px;
  /* quotes: "\201C""\201D""\2018""\2019"; */
  quotes: "\201C""\201D";
}
blockquote:before {
  color: coral;
  content: open-quote;
  font-size: 5em;
  line-height: 0.1em;
  margin-right: 0.25em;
  vertical-align: -0.4em;
}
/* blockquote:after {
  position: relative;
  color: coral;
  content: close-quote;
  font-size: 5em;
  line-height: 0.1em;
  margin-right: 0.25em;
  vertical-align: -0.4em;
} */
blockquote p {
  display: inline;
}
.textover-5 {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 5; /* number of lines to show */
  -webkit-box-orient: vertical;
}
</style>