<template >
  <div class="myFont">
    <v-app-bar color="orange" dark fixed hide-on-scroll shaped v-show="showParentContent">
      <v-app-bar-nav-icon x-large @click="drawer = !drawer" v-if="isLoggedIn"></v-app-bar-nav-icon>
      <v-spacer />
      <v-toolbar-title class="ml-6 mt-6">
        <span @click="setComponent('home')" class="pointer">
          <v-img class="mx-2" :src="'/images/logo.png'" max-width="75" contain></v-img>
        </span>
      </v-toolbar-title>
      <v-container>
        <v-row justify="center" no-gutters>
          <v-col cols="12" sm="3" dark color="orange">
            <v-row justify="center" align="center">
              <v-divider class="mr-0 white" inset vertical></v-divider>
              <v-col cols="12" sm="2">
                <v-icon class="pr-1" x-large>email</v-icon>
              </v-col>
              <v-col cols="12" sm="9">
                <v-card dark color="orange" flat>
                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title class="headline mb-1">Headline 5</v-list-item-title>
                      <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" sm="3" dark color="orange">
            <v-row justify="center" align="center">
              <v-divider class="mr-0 white" inset vertical></v-divider>
              <v-col cols="12" sm="2">
                <v-icon class="pr-1" x-large>facebook</v-icon>
              </v-col>
              <v-col cols="12" sm="9">
                <v-card dark color="orange" flat>
                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title class="headline mb-1">Headline 5</v-list-item-title>
                      <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" sm="3" dark color="orange">
            <v-row justify="center" align="center">
              <v-divider class="mr-0 white" inset vertical></v-divider>
              <v-col cols="12" sm="2">
                <v-icon class="pr-1" x-large>linkedin</v-icon>
              </v-col>
              <v-col cols="12" sm="9">
                <v-card dark color="orange" flat>
                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title class="headline mb-1">Headline 5</v-list-item-title>
                      <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-container>
      <v-spacer />
      <v-menu
        :nudge-width="200"
        offset-y
        nudge-left
        open-on-hover
        rounded
        transition="slide-y-transition"
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-icon light medium dark v-bind="attrs" v-on="on">mdi-account</v-icon>
        </template>
        <v-card>
          <v-list dense v-if="!isLoggedIn">
            <v-subheader>Account</v-subheader>
            <v-list-item-group color="indigo" active-class="pink--text ">
              <v-list-item @click="setComponent('login')">
                <v-list-item-icon>
                  <v-icon>mdi-login</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Sign in</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <!-- <v-divider></v-divider> -->
              <v-list-item @click="setComponent('register')">
                <v-list-item-icon>
                  <v-icon>mdi-account-plus</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Sign up</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
          <v-list dense v-else>
            <v-subheader>Account</v-subheader>
            <v-list-item-group color="primary" active-class="pink--text ">
              <v-list-item v-if="user_type == 0 && isLoggedIn" @click="setComponent('userboard')">
                <v-list-item-avatar>
                  <v-img src="./images/logo.png" alt="Genius Exams" />
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>Hi, {{name}}</v-list-item-title>
                  <v-list-item-subtitle>Genius Exams Website</v-list-item-subtitle>
                </v-list-item-content>
                <!-- <v-list-item-action>
                  <v-btn :class="fav ? 'red--text' : ''" icon @click="fav = !fav">
                    <v-icon>mdi-heart</v-icon>
                  </v-btn>
                </v-list-item-action>-->
              </v-list-item>
              <v-list-item v-if="user_type == 1 && isLoggedIn" @click="setComponent('main')">
                <v-list-item-avatar>
                  <v-img src="./images/logo.png" alt="Genius Exams" />
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>Hi, {{name}}</v-list-item-title>
                  <v-list-item-subtitle>Genius Exams Website</v-list-item-subtitle>
                </v-list-item-content>
                <!-- <v-list-item-action>
                  <v-btn :class="fav ? 'red--text' : ''" icon @click="fav = !fav">
                    <v-icon>mdi-heart</v-icon>
                  </v-btn>
                </v-list-item-action>-->
              </v-list-item>
              <!-- <v-divider></v-divider> -->
              <v-list-item @click="setComponent('changepassword'); ">
                <v-list-item-icon>
                  <v-icon>mdi-key-change</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Change Password</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <!-- <v-divider></v-divider> -->
              <v-list-item @click="logout">
                <v-list-item-icon>
                  <v-icon>mdi-logout</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>Sign out</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-menu>
      <template v-slot:extension>
        <v-container
          row
          fill-height
          justify-center
          style="max-width: 1200px"
          class="mx-auto"
          color="#26c6da"
        >
          <v-hover v-slot:default="{ hover }">
            <v-card :elevation="hover ? 2 : 0" id="rounded-card" light outlined>
              <v-tabs
                show-arrows
                grow
                v-model="active_tab"
                background-color="white"
                center-active
                centered
                color="orange"
                slider-color="orange"
                slider-size="3"
              >
                <v-tabs-slider></v-tabs-slider>
                <v-tab
                  v-if="user_type==0 && isLoggedIn"
                  :href="`#tab_home`"
                  @click="setComponent('home');   $vuetify.goTo('#navbar', {
                  offset: 100,
                  duration: 2000,
                  easing: 'easeInOutQuad'
                })"
                >Home</v-tab>
                <v-tab
                  v-if="user_type==-1 && !isLoggedIn"
                  :href="`#tab_home`"
                  @click="setComponent('home');   $vuetify.goTo('#navbar', {
                  offset: 100,
                  duration: 2000,
                  easing: 'easeInOutQuad'
                })"
                >Home</v-tab>
                <v-tab
                  v-if="user_type==1 && isLoggedIn"
                  :href="`#tab_home`"
                  @click="active_admin_drawer='admin_drawer_home';   setComponent('home');   $vuetify.goTo('#navbar', {
                  offset: 100,
                  duration: 2000,
                  easing: 'easeInOutQuad'
                })"
                >Home</v-tab>
                <v-tab
                  :href="`#tab_requests`"
                  @click="setComponent('userboard')"
                  v-if="user_type == 0 && isLoggedIn"
                  key="userboard"
                >Orders</v-tab>
                <v-tab
                  :href="`#tab_main`"
                  @click="setComponent('main')"
                  v-if="user_type == 1 && isLoggedIn"
                >Main</v-tab>
                <v-tab
                  :href="`#tab_certificates`"
                  @click="setComponent('certificates')"
                  v-if="user_type == 1 && isLoggedIn"
                >Certificates</v-tab>
                <v-tab
                  :href="`#tab_orders`"
                  @click="setComponent('orders')"
                  v-if="user_type == 1 && isLoggedIn"
                >Orders</v-tab>
                <v-tab
                  :href="`#tab_exams`"
                  @click="setComponent('exams')"
                  v-if="user_type == 1 && isLoggedIn"
                >Exams</v-tab>
                <v-tab
                  :href="`#tab_questions`"
                  @click="setComponent('questions')"
                  v-if="user_type == 1 && isLoggedIn"
                >Questions</v-tab>
                <v-tab
                  :href="`#tab_users`"
                  @click="setComponent('users')"
                  v-if="user_type == 1 && isLoggedIn"
                >Users</v-tab>
                <v-tab
                  :href="`#tab_sessions`"
                  @click="setComponent('sessions')"
                  v-if="user_type == 1 && isLoggedIn"
                >Sessions</v-tab>
                <v-tab
                  :href="`#tab_services`"
                  v-if="user_type !=1"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';$vuetify.goTo('#tab_services', {
                offset: 100,
                duration: 2000,
                easing: 'easeInOutQuad'
              })"
                >Our Services</v-tab>
                <v-tab
                  :href="`#tab_contactus`"
                  v-if="user_type !=1"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';$vuetify.goTo('#tab_contactus', {
                offset: 100,
                duration: 2000,
                easing: 'easeInOutQuad'
              })"
                >Contact us</v-tab>
                <v-spacer></v-spacer>
              </v-tabs>
            </v-card>
          </v-hover>
        </v-container>
      </template>
    </v-app-bar>
    <v-row v-show="showParentContent">
      <v-col>
        <v-slide-y-reverse-transition>
          <v-btn
            v-scroll="onScroll"
            v-show="fab"
            fab
            dark
            fixed
            bottom
            right
            color="orange"
            @click="toTop"
          >
            <v-icon large class="arrow">keyboard_arrow_up</v-icon>
          </v-btn>
        </v-slide-y-reverse-transition>
      </v-col>
    </v-row>
    <v-navigation-drawer
      v-if="user_type == 1 && isLoggedIn"
      :clipped="true"
      enable-resize-watcher
      hide-overlay
      temporary
      mini-variant
      expand-on-hover
      width="250"
      style="z-index:1000"
      v-model="drawer"
      app
      color="orange"
      dark
      flat
      v-show="showParentContent"
      class="drawer"
    >
      <v-img :aspect-ratio="16/9" src="images/header.jpg">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <div class="subheading">{{user.name}}</div>
            <div class="body-1">{{user.email}}</div>
          </v-col>
        </v-row>
      </v-img>
      <v-list dense nav class="pt-3 white--text">
        <v-list-item-group color="primary" v-model="active_admin_drawer" active-class="pink--text ">
          <v-list-item @click="setComponent('home')" value="admin_drawer_home">
            <v-list-item-action>
              <v-icon class="white--text">home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="setComponent('main')" value="admin_drawer_main">
            <v-list-item-action>
              <v-icon class="white--text">home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Main</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('certificates')" value="admin_drawer_certificates">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Certificates</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('orders')" value="admin_drawer_requests">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Orders</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('exams')" value="admin_drawer_exams">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Exams</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('questions')" value="admin_drawer_questions">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Questions</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('users')" value="admin_drawer_users">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Users</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="setComponent('sessions')" value="admin_drawer_sessions">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Sessions</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <v-footer app>
        <div>&copy; {{ new Date().getFullYear() }}</div>
      </v-footer>
    </v-navigation-drawer>
    <v-navigation-drawer
      v-if="user_type == 0 && isLoggedIn"
      :clipped="true"
      enable-resize-watcher
      hide-overlay
      temporary
      mini-variant
      expand-on-hover
      width="250"
      style="background-color:#e7e8ea"
      v-model="drawer"
      app
      color="orange"
      dark
      flat
      v-show="showParentContent"
      class="drawer"
    >
      <v-img :aspect-ratio="16/9" src="images/header.jpg">
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <div class="subheading">{{user.name}}</div>
            <div class="body-1">{{user.email}}</div>
          </v-col>
        </v-row>
      </v-img>
      <v-list dense nav class="pt-3 white--text">
        <v-list-item-group color="primary" v-model="active_user_drawer" active-class="pink--text ">
          <v-list-item @click="setComponent('home')" value="user_drawer_home">
            <v-list-item-action>
              <v-icon class="white--text">home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item @click="setComponent('userboard')" value="user_drawer_requests">
            <v-list-item-action>
              <v-icon class="white--text">dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title class="white--text">Orders</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
        </v-list-item-group>
      </v-list>
      <v-footer app>
        <v-spacer></v-spacer>
        <div>&copy; {{ new Date().getFullYear() }}</div>
      </v-footer>
    </v-navigation-drawer>
    <v-main>
      <v-app fluid>
        <v-flex style="margin-top:135px">
          <component
            class="myFont"
            id="tab_home"
            v-intersect="handleHomeIntersect"
            :verification_message="verification_message"
            :verification_result="verification_result"
            :isLoggedIn="isLoggedIn"
            @linkToHome="linkToHome"
            @linkToRegister="linkToRegister"
            @linkToLogin="linkToLogin"
            @PasswordWasReset="PasswordWasReset"
            @loginForgotPassword="loginForgotPassword"
            @passwordChanged="passwordChanged"
            @enrollLogin="enrollLogin"
            @enrollRegister="enrollRegister"
            @hasNext="hasNext"
            @noNext="noNext"
            @showExams="showExams"
            @showConfirmation="showConfirmation"
            @showSingleCertificate="showSingleCertificate"
            @loggedIn="change"
            @StartSession="StartSession"
            @verification_result_hide="verification_result_hide"
            :is="activeComponent"
          ></component>
        </v-flex>
        <Services
          v-scrollanimation
          v-show="showParentContent"
          class="myFont"
          id="tab_services"
          v-intersect="handleServicesIntersect"
        />
        <ContactUs
          v-scrollanimation
          v-show="showParentContent"
          class="myFont"
          id="tab_contactus"
          v-intersect="handleContactUsIntersect"
        />
        <Footer v-scrollanimation v-show="showParentContent" class="myFont" id="footer" />
      </v-app>
    </v-main>
  </div>
</template>


<script>
import Main from "../admin/Main";
import Users from "../admin/Users";
import Sessions from "../admin/Sessions";
import Certificates from "../admin/Certificates";
import Exams from "../admin/Exams";
import Questions from "../admin/Questions";
import Orders from "../admin/Orders";
import Login from "../../views/Login";
import Register from "../../views/Register";
import ChangePassword from "../../views/ChangePassword";
import ForgotPassword from "../../views/ForgotPassword";
import ResetPasswordForm from "../../views/ResetPasswordForm";
import Admin from "../../views/Admin";
import UserBoard from "../../views/UserBoard";
import Home from "../../views/Home";
import SingleCertificate from "../../views/SingleCertificate";
import Confirmation from "../../views/Confirmation";
import StartSession from "../../views/StartSession";
import * as easings from "vuetify/es5/services/goto/easing-patterns";

import ScrollAnimation from "../../directives/scrollanimation";

import Services from "./Services";
import ContactUs from "./ContactUs";
import Footer from "./Footer";
Vue.directive("scrollanimation", ScrollAnimation);

export default {
  data() {
    return {
      showParentContent: true,
      fav: false,
      fab: false,
      active_tab: "tab_home",
      active_user_drawer: "user_drawer_home",
      active_admin_drawer: "admin_drawer_home",
      drawer: false,
      tab: "",
      user: null,
      activeComponent: null,
      name: null,
      email: null,
      user_type: -1,
      isLoggedIn: localStorage.getItem("genius.jwt") != null,
      verification_message: "",
      verification_result: false,
      // links: [
      //   { icon: "home", text: "Home", route: "/" },
      //   { icon: "dashboard", text: "Dashboard", route: "/dashboard" },
      //   { icon: "dashboard", text: "Certificates", route: "/admin/certificates" },
      //   { icon: "dashboard", text: "Requests", route: "/admin/orders" },
      //   { icon: "dashboard", text: "Exams", route: "/admin/exams" },
      //   { icon: "dashboard", text: "Questions", route: "/admin/questions" },
      //   { icon: "dashboard", text: "Users", route: "/admin/users" },
      // ],
    };
  },
  components: {
    Main,
    Users,
    Sessions,
    Certificates,
    Exams,
    Questions,
    Orders,
    Services,
    ContactUs,
    Footer,
  },
  mounted() {
    this.setDefaults();
  },
  beforeMount() {
    // alert(JSON.stringify(this.$route.query.token));
    if (this.$route.query.token) {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.activeComponent = ResetPasswordForm;
      this.$router.push({
        name: "reset-password",
        params: { token: this.$route.query.token },
      });
    } else if (this.$route.query.verification_token) {
      this.showParentContent = false;
      let verification_token = this.$route.query.verification_token;
      axios
        .post("/api/user/verify", { verification_token })
        .then((response) => {
          this.verification_message = response.data.message;
          this.verification_result = true;
        });
      this.active_tab = "tab_home";
      this.activeComponent = Login;
      this.$router.push({
        name: "verify",
      });
    } else {
      this.showParentContent = true;
      this.setComponent(this.$route.params.page);
    }
    this.user = JSON.parse(localStorage.getItem("genius.  d fuser"));
    axios.defaults.headers.common["Content-Type"] = "application/json";
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.getItem("genius.jwt");
  },
  methods: {
    loginForgotPassword() {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.activeComponent = ForgotPassword;
      this.$router.push({
        name: "reset-password",
      });
    },
    PasswordWasReset() {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.activeComponent = Login;
      this.$router.push({
        name: "login",
      });
    },
    onScroll(e) {
      if (typeof window === "undefined") return;
      const top = window.pageYOffset || e.target.scrollTop || 0;
      this.fab = top > 60;
    },
    toTop() {
      this.$vuetify.goTo(0);
    },
    passwordChanged() {
      this.showParentContent = true;
      this.active_tab = "tab_home";
      this.activeComponent = Home;
    },
    showSingleCertificate(id) {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.active_user_drawer = "user_drawer_requests";
      this.activeComponent = SingleCertificate;
      this.$router.push({
        name: "single-certificates",
        params: { id: id },
      });
    },
    showConfirmation() {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.active_user_drawer = "user_drawer_requests";
      this.activeComponent = Confirmation;
      this.$router.push({
        name: "confirmation",
      });
    },
    StartSession(certificate_id) {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.activeComponent = StartSession;
      this.$router.push({
        name: "start-session",
        params: { id: certificate_id },
      });
    },
    showExams() {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.activeComponent = UserBoard;
      this.$router.push({ name: "userboard" }).catch((err) => {});
    },
    enrollLogin(next) {
      // alert(next);
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.activeComponent = Login;
      this.$router
        .push({
          name: "login",
          params: { nextUrl: next },
        })
        .catch((err) => {});
    },
    enrollRegister(next) {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.activeComponent = Register;
      this.$router
        .push({
          name: "register",
          params: { nextUrl: next },
        })
        .catch((err) => {});
    },
    setDefaults() {
      if (this.isLoggedIn) {
        let user = JSON.parse(localStorage.getItem("genius.user"));
        this.user = user;
        this.name = user.name;
        this.email = user.email;
        this.user_type = user.is_admin;
      }
    },
    change() {
      this.isLoggedIn = localStorage.getItem("genius.jwt") != null;
      this.setDefaults();
    },
    hasNext(url) {
      // alert(url);
      let parts = url.split("/");
      // alert(parts[2]);
      let page = parts[1];
      let id = parts[2];
      if (page === "certificates") {
        this.showParentContent = true;
        this.active_tab = "tab_requests";
        this.activeComponent = SingleCertificate;
        this.$router.push({
          name: "single-certificates",
          params: { id: id },
        });
      }
    },
    noNext() {
      if (this.user_type == 1) {
        this.showParentContent = true;
        this.active_tab = "tab_main";
        this.active_admin_drawer = "admin_drawer_main";
        this.activeComponent = Main;
      } else if (this.user_type == 0) {
        this.showParentContent = true;
        this.active_tab = "tab_requests";
        this.active_user_drawer = "user_drawer_requests";
        this.activeComponent = UserBoard;
      }
    },
    logout() {
      this.showParentContent = true;
      localStorage.removeItem("genius.jwt");
      localStorage.removeItem("genius.user");
      this.user_type = -1;
      this.change();
      this.active_tab = "tab_home";
      this.activeComponent = Home;
      this.$router.push("/").catch((err) => {});
    },
    setComponent(value) {
      if (this.user_type == 1) {
        switch (value) {
          case "main":
            this.showParentContent = true;
            this.active_tab = "tab_main";
            this.active_admin_drawer = "admin_drawer_main";
            this.activeComponent = Main;
            this.$router.push({
              name: "admin-pages",
              params: { page: "main" },
            });
            break;
          case "home":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
          // case "admin":
          //   this.activeComponent = Admin;
          //   this.$router.push({ name: "admin" }).catch((err) => {});
          //   break;
          case "userboard":
            this.showParentContent = true;
            this.active_tab = "tab_requests";
            this.active_admin_drawer = "admin_drawer_requests";
            this.activeComponent = UserBoard;
            this.$router.push({ name: "userboard" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Login;
            this.$router.push({ name: "login" }).catch((err) => {});
            break;
          case "users":
            this.showParentContent = true;
            this.active_tab = "tab_users";
            this.active_admin_drawer = "admin_drawer_users";
            this.activeComponent = Users;
            this.$router
              .push({ name: "admin-pages", params: { page: "users" } })
              .catch((err) => {});
            break;
          case "sessions":
            this.showParentContent = true;
            this.active_tab = "tab_sessions";
            this.active_admin_drawer = "admin_drawer_sessions";
            this.activeComponent = Sessions;
            this.$router
              .push({ name: "admin-pages", params: { page: "sessions" } })
              .catch((err) => {});
            break;
          case "certificates":
            this.showParentContent = true;
            this.active_tab = "tab_certificates";
            this.active_admin_drawer = "admin_drawer_certificates";
            this.activeComponent = Certificates;
            this.$router
              .push({
                name: "admin-pages",
                params: { page: "certificates" },
              })
              .catch((err) => {});
            break;
          case "orders":
            this.showParentContent = true;
            this.active_tab = "tab_orders";
            this.active_admin_drawer = "admin_drawer_requests";
            this.activeComponent = Orders;
            this.$router
              .push({
                name: "admin-pages",
                params: { page: "orders" },
              })
              .catch((err) => {});
            break;
          case "exams":
            this.showParentContent = true;
            this.active_tab = "tab_exams";
            this.active_admin_drawer = "admin_drawer_exams";
            this.activeComponent = Exams;
            this.$router
              .push({
                name: "admin-pages",
                params: { page: "exams" },
              })
              .catch((err) => {});
            break;
          case "questions":
            this.showParentContent = true;
            this.active_tab = "tab_questions";
            this.active_admin_drawer = "admin_drawer_questions";
            this.activeComponent = Questions;
            this.$router
              .push({
                name: "admin-pages",
                params: { page: "questions" },
              })
              .catch((err) => {});
            break;
          case "changepassword":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = ChangePassword;
            this.$router.push({ name: "changepassword" }).catch((err) => {});
            break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      } else if (this.user_type == 0) {
        switch (value) {
          case "home":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
          case "userboard":
            this.showParentContent = true;
            this.active_tab = "tab_requests";
            this.active_user_drawer = "user_drawer_requests";
            this.activeComponent = UserBoard;
            this.$router.push({ name: "userboard" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Login;
            this.$router.push({ name: "login" }).catch((err) => {});
            break;
          case "changepassword":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = ChangePassword;
            this.$router.push({ name: "changepassword" }).catch((err) => {});
            break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      } else if (this.user_type == -1) {
        switch (value) {
          case "home":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.activeComponent = Login;
            this.$router.push({ name: "login" }).catch((err) => {});
            break;
          // case "reset-password":
          //   this.activeComponent = ResetPasswordForm;
          //   this.$router
          //     .push({
          //       name: "reset-password",
          //       params: { token: this.$route.params.token },
          //     })
          //     .catch((err) => {});
          //   break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      }
    },
    handleHomeIntersect(entries, observer) {
      // console.log(JSON.stringify(entries));
      if (!this.isLoggedIn && entries[0].isIntersecting) {
        this.active_tab = "tab_home";
      }
    },
    handleContactUsIntersect(entries, observer) {
      // console.log(JSON.stringify(entries));
      if (!this.isLoggedIn && entries[0].isIntersecting) {
        this.active_tab = "tab_contactus";
      }
    },
    handleServicesIntersect(entries, observer) {
      // console.log(JSON.stringify(entries));
      if (!this.isLoggedIn && entries[0].isIntersecting) {
        this.active_tab = "tab_services";
      }
    },
    linkToHome() {
      this.setComponent("home");
    },
    linkToLogin() {
      this.setComponent("login");
    },
    linkToRegister() {
      this.setComponent("register");
    },
    verification_result_hide() {
      this.verification_message = "";
      this.verification_result = false;
    },
  },
};
</script>
<style scoped>
.myFont {
  font-family: "Karla", sans-serif !important;
}
#rounded-card {
  border-radius: 10px;
  padding-left: 0px;
  padding-right: 0px;
  padding-top: 5px;
  padding-bottom: 5px;
}
.pointer {
  cursor: pointer;
}
.v-navigation-drawer {
  /* transition: none !important; */
}
.lightbox {
  box-shadow: 0 0 20px inset rgba(0, 0, 0, 0.2);
  background-image: linear-gradient(
    to top,
    rgba(0, 0, 0, 0.4) 0%,
    transparent 72px
  );
}
a.v-tab {
  text-decoration: none;
}
.arrow:hover {
  animation: moveRightToLeft -2s 1s infinite linear;
}
@keyframes moveRightToLeft {
  0% {
    transform: translate(0, 4px);
  }
  50% {
    transform: translate(0, -4px);
  }
  50.1% {
    transform: translate(0, 4px);
  }
  100% {
    transform: translate(0, -4px);
  }
}
/*
    This classes are for the directive. 
    For each element observed by our directive, the before-enter class is added.
  */
.before-enter {
  opacity: 0;
  transform: translateY(100px);
  transition: all 1s cubic-bezier(0.41, 0.01, 0.57, 1.61);
}
/* 
    If the element intersects with the viewport, the before-enter class is added.
  */
.enter {
  opacity: 1;
  transform: translateY(0px);
}
.drawer {
  /* z-index: 1000; */
}
</style>