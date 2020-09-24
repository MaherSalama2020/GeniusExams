<template>
  <vueper-slides
    style="margin-top:-25px"
    fractions
    class="no-shadow"
    transition-speed="400"
    :touchable="false"
    disable-arrows-on-edges
    bullets-inside
    autoplay
    :slide-ratio="1/2.5"
    :pause-on-hover="pauseOnHover"
    @autoplay-pause="internalAutoPlaying = false"
    @autoplay-resume="internalAutoPlaying = true"
    :parallax="-1"
  >
    <template v-slot:pause>
      <v-icon large color="white">pause_circle_outline</v-icon>
    </template>
    <vueper-slide v-for="(slide, i) in slides" :key="i" :image="slide.image">
      <template v-slot:content>
        <v-overlay :absolute="true" :opacity="0.46" :value="true" :z-index="5">
          <!-- <transition name="slide-fade1"> -->
          <v-row no-gutters justify="center" align="start" v-if="show">
            <v-col cols="12" sm="auto" md="auto" lg="auto">
              <span class="big-dot mr-2">.</span>
              <span class="big-dot mr-2">.</span>
              <span class="medium-title mr-6 ml-4">LEARN ONLINE COURSES</span>
              <span class="big-dot mr-2">.</span>
              <span class="big-dot">.</span>
            </v-col>
          </v-row>
          <!-- </transition> -->
          <transition name="slide-fade2">
            <v-row no-gutters justify="center" align="start" v-if="show" class="hidden-sm-and-down">
              <v-col cols="12" sm="auto" md="auto" lg="auto">
                <span class="x-big-title mt-0 font-weight-bold">Advance your Career</span>
              </v-col>
            </v-row>
          </transition>
          <transition name="slide-fade3">
            <v-row no-gutters justify="center" align="start" v-if="show" class="hidden-sm-and-down">
              <v-col cols="12" sm="auto" md="auto" lg="auto">
                <v-btn outlined color="orange" class="py-6" @click="scrollToCertificates">
                  <span style="color:white" class="font-weight-bold">Our Certificates</span>
                  <v-icon color="white" medium right>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </transition>
        </v-overlay>
      </template>
    </vueper-slide>
  </vueper-slides>
</template>
<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";

export default {
  components: { VueperSlides, VueperSlide },
  data() {
    return {
      show: false,
      pauseOnHover: true,
      internalAutoPlaying: true,
      slides: [
        {
          image: "/images/slider/bg1.jpg",
        },
        {
          image: "/images/slider/bg2.jpg",
        },
        {
          image: "/images/slider/bg3.jpg",
        },
        {
          image: "/images/slider/bg4.jpg",
        },
        {
          image: "/images/slider/bg5.jpg",
        },
        {
          image: "/images/slider/bg6.jpg",
        },
        {
          image: "/images/slider/bg7.jpg",
        },
      ],
    };
  },
  mounted() {
    if ($("body").hasClass("loaded")) this.show = true;
    document.onreadystatechange = () => {
      // alert($("body").hasClass("loaded"));
      if (document.readyState == "complete") {
        this.show = true;
        $("body").addClass("loaded");
      }
    };
  },
  methods: {
    scrollToCertificates() {
      this.$emit("scrollToCertificates");
    },
  },
};
</script>
<style>
.vueperslides__progress {
  background: rgba(0, 0, 0, 0.25);
  color: #ff9800;
}
.small-title {
  color: white;
  font-size: 1.1vw;
}
.small-medium-title {
  color: white;
  font-size: 24px;
}
.medium-title {
  color: white;
  font-size: 2vw;
}
.big-title {
  color: white;
  font-size: 3vw;
}
.x-big-title {
  color: white;
  font-size: 4vw;
}
.big-dot {
  color: #ff9800;
  font-size: 72px;
}
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade1-enter-active {
  transition: all 1s ease;
}
.slide-fade1-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade1-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateY(100px);
  opacity: 0;
}

.slide-fade2-enter-active {
  transition: all 1.5s ease;
}
.slide-fade2-leave-active {
  transition: all 2s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade2-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateY(100px);
  opacity: 0;
}

.slide-fade3-enter-active {
  transition: all 2s ease;
}
.slide-fade3-leave-active {
  transition: all 3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade3-enter, .slide-fade-leave-to
      /* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateY(100px);
  opacity: 0;
}
</style>