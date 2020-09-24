<template>
  <v-container class="mt-15">
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
    <vueper-slides
      ref="sliderVueperSlides"
      class="ml-10 mr-10 no-shadow"
      :autoplay="false"
      :visible-slides="5"
      slide-multiple
      :gap="1"
      :slide-ratio="1 / 6"
      :bullets="false"
      :breakpoints="breakpoints"
      :arrows="true"
      :touchable="false"
    >
      <vueper-slide v-for="image in images" :key="image+'-vueper-slide'">
        <template v-slot:content>
          <v-img
            :src="image + '?rnd=' + cacheKey"
            :alt="image"
            contain
            class="cursor-move"
            @click="pickImage(image)"
          ></v-img>
        </template>
      </vueper-slide>
    </vueper-slides>
    <input
      class="form-control"
      type="file"
      id="sliderfile"
      style="display:none;"
      @change="attachFile"
      accept="image/*"
      ref="sliderimage"
    />
  </v-container>
</template>
<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
export default {
  data() {
    return {
      spinner: false,
      cacheKey: +new Date(),
      name: "",
      images: [
        "/images/slider/bg1.jpg",
        "/images/slider/bg2.jpg",
        "/images/slider/bg3.jpg",
        "/images/slider/bg4.jpg",
        "/images/slider/bg5.jpg",
        "/images/slider/bg6.jpg",
        "/images/slider/bg7.jpg",
      ],
      breakpoints: {
        1200: {
          slideRatio: 1 / 4,
        },
        900: {
          slideRatio: 1 / 4,
        },
        600: {
          slideRatio: 1 / 4,
          arrows: false,
          bullets: true,
          bulletsOutside: true,
        },
        // The order you list breakpoints does not matter, Vueper Slides will sort them for you.
        1100: {
          slideRatio: 1 / 4,
        },
      },
    };
  },
  components: { VueperSlides, VueperSlide },

  methods: {
    pickImage(image) {
      let parts = image.split("/");
      this.name = parts[3];
      this.$refs.sliderimage.click();
    },
    attachFile(event) {
      // alert(event.target.files[0].name);
      if (event.target.files[0]) {
        this.spinner = true;
        var formData = new FormData();
        this.attachment = event.target.files[0];
        formData.append("image", this.attachment);
        formData.append("name", this.name);
        let headers = { "Content-Type": "multipart/form-data" };
        let name = this.name;
        axios
          .post("/api/slider/upload-file", formData, { headers })
          .then((response) => {
            this.cacheKey = new Date();
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
      }
    },
  },
};
</script>
<style scoped>
.cursor-move {
  cursor: pointer;
}
</style>