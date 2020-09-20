<template>
  <div id="loader-wrapper" class="page-loader">
    <!-- <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>-->
    <div id="loader" class="lds-spinner">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
  </div>
</template>

<script>
export default {
  data: () => {
    return {
      isloaded: false,
    };
  },
  mounted() {
    document.onreadystatechange = () => {
      if (document.readyState == "complete") {
        this.isloaded = true;
        $("body").addClass("loaded");
      }
    };
  },
};
</script>

<style lang="scss" scoped>
// $colors: #8cc271, #69beeb, #f5aa39, #e9643b;
// -----------------------------------------------------
// .page-loader {
//   display: flex;
//   justify-content: center;
//   align-items: center;
//   position: fixed;
//   top: 0;
//   left: 0;
//   width: 100vw;
//   height: 100vh;
//   background-color: #333;
//   z-index: 999;
// }
// -----------------------------------------------------
// .cube {
//   width: 40px;
//   height: 40px;
//   border-radius: 20px;
//   margin-right: 10px;
//   @for $i from 1 through length($colors) {
//     &:nth-child(#{$i}) {
//       background-color: nth($colors, $i);
//     }
//   }
//   &:first-child {
//     animation: left 1s infinite;
//   }
//   &:last-child {
//     animation: right 1s infinite 0.5s;
//   }
// }
// -----------------------------------------------------
// @keyframes left {
//   40% {
//     transform: translateX(-60px);
//   }
//   50% {
//     transform: translateX(0);
//   }
// }
// @keyframes right {
//   40% {
//     transform: translateX(60px);
//   }
//   50% {
//     transform: translateX(0);
//   }
// }

.lds-spinner {
  color: official;
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-spinner div {
  transform-origin: 40px 40px;
  animation: lds-spinner 1.2s linear infinite;
}
.lds-spinner div:after {
  content: " ";
  display: block;
  position: absolute;
  top: 3px;
  left: 37px;
  width: 6px;
  height: 18px;
  border-radius: 20%;
  background: #000;
}
.lds-spinner div:nth-child(1) {
  transform: rotate(0deg);
  animation-delay: -1.1s;
}
.lds-spinner div:nth-child(2) {
  transform: rotate(30deg);
  animation-delay: -1s;
}
.lds-spinner div:nth-child(3) {
  transform: rotate(60deg);
  animation-delay: -0.9s;
}
.lds-spinner div:nth-child(4) {
  transform: rotate(90deg);
  animation-delay: -0.8s;
}
.lds-spinner div:nth-child(5) {
  transform: rotate(120deg);
  animation-delay: -0.7s;
}
.lds-spinner div:nth-child(6) {
  transform: rotate(150deg);
  animation-delay: -0.6s;
}
.lds-spinner div:nth-child(7) {
  transform: rotate(180deg);
  animation-delay: -0.5s;
}
.lds-spinner div:nth-child(8) {
  transform: rotate(210deg);
  animation-delay: -0.4s;
}
.lds-spinner div:nth-child(9) {
  transform: rotate(240deg);
  animation-delay: -0.3s;
}
.lds-spinner div:nth-child(10) {
  transform: rotate(270deg);
  animation-delay: -0.2s;
}
.lds-spinner div:nth-child(11) {
  transform: rotate(300deg);
  animation-delay: -0.1s;
}
.lds-spinner div:nth-child(12) {
  transform: rotate(330deg);
  animation-delay: 0s;
}
@keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

#loader-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1002;
}
#loader {
  display: block;
  position: relative;
  left: 50%;
  top: 50%;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  z-index: 1001;
}

#loader-wrapper .loader-section {
  position: fixed;
  top: 0;
  width: 51%;
  height: 100%;
  background: #ffffff;
  z-index: 1000;
  -webkit-transform: translateX(0); /* Chrome, Opera 15+, Safari 3.1+ */
  -ms-transform: translateX(0); /* IE 9 */
  transform: translateX(0); /* Firefox 16+, IE 10+, Opera */
}

#loader-wrapper .loader-section.section-left {
  left: 0;
}

#loader-wrapper .loader-section.section-right {
  right: 0;
}

/* Loaded */
.loaded #loader-wrapper .loader-section.section-left {
  -webkit-transform: translateX(-100%); /* Chrome, Opera 15+, Safari 3.1+ */
  -ms-transform: translateX(-100%); /* IE 9 */
  transform: translateX(-100%); /* Firefox 16+, IE 10+, Opera */

  -webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
  transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
}

.loaded #loader-wrapper .loader-section.section-right {
  -webkit-transform: translateX(100%); /* Chrome, Opera 15+, Safari 3.1+ */
  -ms-transform: translateX(100%); /* IE 9 */
  transform: translateX(100%); /* Firefox 16+, IE 10+, Opera */

  -webkit-transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
  transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
}

.loaded #loader {
  opacity: 0;
  -webkit-transition: all 0.3s ease-out;
  transition: all 0.3s ease-out;
}
.loaded #loader-wrapper {
  visibility: hidden;

  -webkit-transform: translateY(-100%); /* Chrome, Opera 15+, Safari 3.1+ */
  -ms-transform: translateY(-100%); /* IE 9 */
  transform: translateY(-100%); /* Firefox 16+, IE 10+, Opera */

  -webkit-transition: all 0.3s 1s ease-out;
  transition: all 0.3s 1s ease-out;
}
</style>