<template>
  <div>
    <div class="text-center" v-if="countDown">
      <v-icon>mdi-clock</v-icon>
      <span>{{ minutes | formatTime }}:{{ seconds | formatTime }}</span>
    </div>
    <div class="text-center" v-if="!countDown">
      <v-icon>mdi-clock</v-icon>Time's Up!
    </div>
  </div>
</template>

<script>
export default {
  props: ["duration"],
  data() {
    return {
      countDown: this.duration * 60 * 1000,
    };
  },
  mounted() {
    this.countDownTimer();
  },
  computed: {
    seconds() {
      return Math.floor((this.countDown / 1000) % 60);
    },
    minutes() {
      return Math.floor((this.countDown / 1000 / 60) % 60);
    },
  },
  filters: {
    formatTime(value) {
      if (value < 10) {
        return "0" + value;
      }
      return value;
    },
  },
  methods: {
    countDownTimer() {
      if (this.countDown > 0) {
        setTimeout(() => {
          this.countDown -= 1000;
          this.countDownTimer();
        }, 1000);
      } else {
        this.$emit("TimeIsUp");
      }
    },
  },
};
</script>