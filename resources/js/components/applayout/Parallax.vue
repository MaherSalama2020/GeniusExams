<template>
  <vueper-slides
    class="no-shadow mb-15"
    transition-speed="2"
    :infinite="true"
    fade
    :touchable="false"
    disable-arrows-on-edges
    bullets-inside
    autoplay
    :slide-ratio="0.2"
  >
    <vueper-slide
      v-for="(slide, i) in slides"
      :key="i"
      :image="slide.image"
      :title="slide.title"
      :content="slide.content"
    />
  </vueper-slides>
</template>
<script>
import { VueperSlides, VueperSlide } from "vueperslides";
import "vueperslides/dist/vueperslides.css";
export default {
  components: { VueperSlides, VueperSlide },
  data() {
    return {
      slides: [
        {
          title: "El Teide Volcano, Spain",
          content: "Photo by Max Rive",
          // You can also provide a URL for the image.
          image: "/images/slider/bg1.jpg",
        },
        {
          title: "El Teide Volcano, Spain",
          content: "Photo by Max Rive",
          // You can also provide a URL for the image.
          image: "/images/slider/bg2.jpg",
        },
      ],
    };
  },
};
</script>