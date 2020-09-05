<template>
  <div>
    <div
      class="container-fluid hero-section d-flex align-content-center justify-content-center flex-wrap ml-auto"
    >
      <h2>
        <span>
          <strong>Thank You!</strong>
        </span>
        <br />
        <span class="medium-text">Your order has been placed.</span>
        <br />
        <button
          class="col-md-4 btn btn-sm btn-success float-right"
          @click="showExams"
        >See your exams</button>
      </h2>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    showExams() {
      this.$emit("showExams");
    },
  },
};
</script>

<style scoped>
.medium-text {
  font-size: 36px;
}
.small-link {
  font-size: 24px;
  text-decoration: underline;
  color: #777;
}
.product-box {
  border: 1px solid #cccccc;
  padding: 10px 15px;
}
.hero-section {
  height: 80vh;
  align-items: center;
  margin-top: -20px;
  margin-bottom: 20px;
}
.title {
  font-size: 60px;
}
</style>