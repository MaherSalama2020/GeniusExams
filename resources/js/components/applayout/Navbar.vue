<template >
  <span class="myFont">
    <v-app-bar color="orange" dark fixed hide-on-scroll v-show="showParentContent" class="navBar">
      <v-app-bar-nav-icon
        class="mt-6 hidden-md-and-up"
        large
        @click="drawer = !drawer"
        v-if="isLoggedIn"
      >
        <v-icon>mdi-hamburger</v-icon>
      </v-app-bar-nav-icon>
      <v-row no-gutters justify="center" align="center">
        <v-col class="col-sm-6 col-md-6 col-lg-2 col-xl-2">
          <v-row justify="end" align="center" no-gutters>
            <v-toolbar-title class="ml-6 mt-6">
              <span @click="setComponent('home')" class="pointer">
                <v-img :src="'/images/logo.png'" max-width="75" contain></v-img>
              </span>
            </v-toolbar-title>
          </v-row>
        </v-col>
        <v-col class="col-lg-8 col-xl-8 hidden-md-and-down">
          <v-row justify="center" align="center" no-gutters>
            <v-col class="col-sm-3" dark color="orange">
              <v-row justify="center" align="center">
                <v-divider class="mr-0 white" vertical></v-divider>
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
            <v-col class="col-sm-3" dark color="orange">
              <v-row justify="center" align="center">
                <v-divider class="mr-0 white" vertical></v-divider>
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
            <v-col class="col-sm-3" dark color="orange">
              <v-row justify="center" align="center">
                <v-divider class="mr-0 white" vertical></v-divider>
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
        </v-col>

        <v-spacer />
        <v-col class="col-6 col-sm-6 col-md-6 col-lg-2 col-xl-2 mt-6">
          <v-row no-gutters justify="end" align="center">
            <!-- Not Empty Whishllist  -->
            <v-badge
              color="green"
              :content="numInWhishlist"
              v-if="numInWhishlist >0 && (user_type ==-1 || user_type==0) "
              overlap
              class="ml-0 mr-0"
            >
              <v-menu
                :nudge-width="200"
                offset-y
                nudge-left
                open-on-hover
                rounded
                bottom
                transition="scroll-y-transition"
                :close-on-click="false"
                :close-on-content-click="false"
                open-delay="200"
                max-width="325"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon dark medium v-bind="attrs" v-on="on">mdi-heart</v-icon>
                </template>
                <span>
                  <v-list dense v-if="typeof Whishlist[0]!='undefined'">
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group
                      color="primary"
                      active-class="pink--text "
                      v-model="WhishListMenu"
                    >
                      <v-list-item
                        v-for="(item) in Whishlist"
                        :key="item.id+'-certificatesNavBar'"
                        :selectable="activeComponentName=='whishlist'"
                        :inactive="activeComponentName=='whishlist'"
                        @click="showSingleCertificate(item.id,'home')"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar tile>
                          <v-img :src="item.image" alt="item.name" />
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">{{item.name}}</v-list-item-title>
                          <v-list-item-subtitle
                            class="font-weight-bold active-menu-item py-1"
                          >{{item.price | dollars}}</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-btn
                                icon
                                color="orange"
                                @click="addToCart(item.id)"
                                v-bind="attrs"
                                v-on="on"
                              >
                                <v-icon>mdi-cart-plus</v-icon>
                              </v-btn>
                            </template>
                            Add To Cart
                          </v-tooltip>
                        </v-list-item-action>
                      </v-list-item>
                      <!-- <v-divider class="mt-1 mb-1" />
                <v-list-item inactive :selectable="false">
                  <v-list-item-content>
                    <v-list-item-title>
                      <p class="font-weight-black ma-0 pa-0">Total{{total | dollars}}</p>
                    </v-list-item-title>
                  </v-list-item-content>
                      </v-list-item>-->
                    </v-list-item-group>
                    <!-- <v-divider class="mt-1 mb-1" />
                    <v-row class="ml-2 mr-2 mt-2" v-if="activeComponentName!='whishlist'">
                      <v-btn
                        color="orange white--text"
                        block
                        @click="setComponent('whishlist')"
                      >Go to Whishlist</v-btn>
                    </v-row>-->
                  </v-list>
                </span>
              </v-menu>
            </v-badge>
            <!-- Empty Whishlist -->
            <div v-show="numInWhishlist ==0 && (user_type ==-1 || user_type==0)">
              <v-menu
                :nudge-width="200"
                offset-y
                nudge-right
                open-on-hover
                rounded
                bottom
                transition="scroll-y-transition"
                :close-on-click="false"
                :close-on-content-click="false"
                open-delay="200"
                max-width="325"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon dark medium v-bind="attrs" v-on="on">mdi-heart-outline</v-icon>
                </template>
                <span>
                  <v-list dense>
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group color="primary" active-class="black--text ">
                      <v-list-item
                        :selectable="true"
                        :inactive="true"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar tile>
                          <v-icon>mdi-heart-outline</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Whish List is Empty</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </span>
              </v-menu>
            </div>
            <!-- end of Whishllist -->
            <!-- Not Empty Shopping Cart  -->
            <v-badge
              color="green"
              :content="numInCart"
              v-if="numInCart >0 && (user_type ==-1 || user_type==0)"
              overlap
              class="ml-8 mr-0"
            >
              <v-menu
                :nudge-width="200"
                offset-y
                nudge-left
                open-on-hover
                rounded
                bottom
                data-container="body"
                transition="scroll-y-transition"
                open-delay="200"
                max-width="325"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon dark medium v-bind="attrs" v-on="on">shopping_cart</v-icon>
                </template>
                <span>
                  <v-list dense v-if="typeof shoppingcart[0]!='undefined'">
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group
                      color="primary"
                      active-class="pink--text "
                      v-model="ShoppingCartMenu"
                    >
                      <v-list-item
                        v-for="(item) in shoppingcart"
                        :key="item.id+'-certificatesNavBar'"
                        @click="showSingleCertificate(item.id,'home')"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar tile>
                          <v-img :src="item.image" alt="item.name" />
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">{{item.name}}</v-list-item-title>
                          <v-list-item-subtitle
                            class="active-menu-item font-weight-bold"
                          >{{item.price | dollars}}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-divider class="mt-1 mb-1" />
                      <v-list-item :selectable="true" :inactive="true" active-class="black--text">
                        <v-list-item-content>
                          <v-list-item-title class="active-menu-item py-1">
                            <p class="font-weight-black ma-0 pa-0">
                              Total:
                              <span style="position:absolute; right:15px">{{total | dollars}}</span>
                            </p>
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                    <v-divider class="mt-1 mb-1" />
                    <v-row class="ml-2 mr-2 mt-2" v-if="activeComponentName!='cart'">
                      <v-btn
                        color="orange white--text"
                        block
                        @click="setComponent('cart')"
                        class="hovered-button"
                      >Go to cart</v-btn>
                    </v-row>
                  </v-list>
                </span>
              </v-menu>
            </v-badge>
            <!-- Empty Shopping Cart -->
            <div class="ml-8 mr-0" v-if="numInCart==0 && (user_type ==-1 || user_type==0)">
              <v-menu
                :nudge-width="200"
                offset-y
                nudge-right
                open-on-hover
                rounded
                bottom
                transition="scroll-y-transition"
                :close-on-click="false"
                :close-on-content-click="false"
                open-delay="200"
                max-width="325"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon dark medium v-bind="attrs" v-on="on">mdi-cart-outline</v-icon>
                </template>
                <span>
                  <v-list dense>
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group color="primary" active-class="black--text ">
                      <v-list-item
                        :selectable="true"
                        :inactive="true"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar tile>
                          <v-icon>mdi-cart-outline</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Shopping Cart is Empty</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </span>
              </v-menu>
            </div>
            <!-- end of Shopping Cart -->
            <!-- Profile -->
            <div class="mr-1 ml-8">
              <v-menu
                :nudge-width="200"
                offset-y
                nudge-right
                open-on-hover
                rounded
                bottom
                transition="scroll-y-transition"
                open-delay="200"
                max-width="325"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon light medium dark v-bind="attrs" v-on="on">mdi-account</v-icon>
                  <!-- <v-avatar color="purple" v-bind="attrs" v-on="on" size="32px">
            <span class="white--text font-weight-bold">{{abreviatedName}}</span>
            <v-badge color="error" v-if="numInCart >0" dot></v-badge>
                  </v-avatar>-->
                </template>
                <span>
                  <v-list dense v-if="!isLoggedIn">
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group
                      color="indigo"
                      active-class="pink--text "
                      v-model="NotLoggedInMenu"
                    >
                      <v-list-item
                        @click="setComponent('login')"
                        value="NotLoggedInMenu_login"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon class>
                          <v-icon>mdi-login</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Sign in</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <!-- <v-divider></v-divider> -->
                      <v-list-item
                        @click="setComponent('register')"
                        value="NotLoggedInMenu_register"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon>mdi-account-plus</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Sign up</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                  <v-list dense v-else>
                    <v-subheader>
                      <v-row no-gutters justify="center">
                        <v-icon x-large color="orange">mdi-menu-down</v-icon>
                      </v-row>
                    </v-subheader>
                    <v-list-item-group
                      color="primary"
                      active-class="pink--text "
                      v-model="LoggedInMenu"
                    >
                      <v-list-item
                        v-if="user_type == 0 && isLoggedIn"
                        @click="setComponent('userboard')"
                        value="LoggedInMenu_userboard"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar>
                          <v-avatar color="purple" size="48px">
                            <span class="white--text font-weight-bold">{{abreviatedName}}</span>
                          </v-avatar>
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item">Hi, {{name}}</v-list-item-title>
                          <v-list-item-subtitle class="active-menu-item py-2">Genius Exams Website</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-divider class="mt-1 mb-1" />
                      <v-list-item
                        v-if="user_type == 1 && isLoggedIn"
                        @click="setComponent('main')"
                        value="LoggedInMenu_main"
                        class="active-menu-item-list"
                      >
                        <v-list-item-avatar>
                          <v-img src="./images/logo.png" alt="Genius Exams" />
                        </v-list-item-avatar>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item">Hi, {{name}}</v-list-item-title>
                          <v-list-item-subtitle class="active-menu-item py-1">Genius Exams Website</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item
                        v-if="user_type == 0 && isLoggedIn"
                        @click="alertShoppingCartDialog "
                        value="LoggedInMenu_cart"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon color="error" v-if="numInCart>0">mdi-cart</v-icon>
                          <v-icon v-else>mdi-cart-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">My cart</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-badge
                            color="error"
                            :content="numInCart"
                            v-if="numInCart >0 && isLoggedIn"
                            overlap
                            class="ml-4 mr-4"
                          ></v-badge>
                        </v-list-item-action>
                      </v-list-item>

                      <v-list-item
                        v-if="user_type == 0 && isLoggedIn"
                        @click="alertWhishlistDialog "
                        value="LoggedInMenu_whish"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon color="error" v-if="numInWhishlist>0">mdi-heart</v-icon>
                          <v-icon v-else>mdi-heart-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Whish List</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-badge
                            color="error"
                            :content="numInWhishlist"
                            v-if="numInWhishlist >0 && isLoggedIn"
                            overlap
                            class="ml-4 mr-4"
                          ></v-badge>
                        </v-list-item-action>
                      </v-list-item>

                      <v-list-item
                        v-if="user_type == 0 && isLoggedIn"
                        @click="alertSavedlistDialog "
                        value="LoggedInMenu_saved"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon color="error" v-if="numInSavedlist>0">mdi-clock</v-icon>
                          <v-icon v-else>mdi-clock-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Saved List</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-badge
                            color="error"
                            :content="numInSavedlist"
                            v-if="numInSavedlist >0 && isLoggedIn"
                            overlap
                            class="ml-4 mr-4"
                          ></v-badge>
                        </v-list-item-action>
                      </v-list-item>
                      <v-divider class="mt-1 mb-1" v-if="user_type == 0 && isLoggedIn" />

                      <v-list-item
                        @click="setComponent('changepassword'); "
                        value="LoggedInMenu_changepassword"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon>mdi-key-change</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Change Password</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <!-- <v-divider></v-divider> -->
                      <v-list-item
                        @click="logout"
                        value="LoggedInMenu_logout"
                        class="active-menu-item-list"
                      >
                        <v-list-item-icon>
                          <v-icon>mdi-logout</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="active-menu-item-content">
                          <v-list-item-title class="active-menu-item py-1">Sign out</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </span>
              </v-menu>
            </div>
          </v-row>
        </v-col>
      </v-row>
      <template v-slot:extension>
        <v-container
          row
          fill-height
          justify-center
          style="max-width: 1200px"
          class="mx-auto col-md-10 col-lg-10 col-xl-10 hidden-sm-and-down"
          color="#ff9800"
        >
          <v-hover v-slot:default="{ hover }">
            <v-card
              :elevation="hover ? 2 : 0"
              id="rounded-card"
              light
              class="py-2"
              color="orange lighten-5"
              shaped
            >
              <v-tabs
                show-arrows
                v-model="active_tab"
                background-color="orange lighten-5"
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
                  :href="`#tab_cart`"
                  @click="setComponent('cart')"
                  v-if="user_type == -1 || user_type == 0"
                  key="cart"
                >Cart({{numInCart}})</v-tab>
                <v-tab
                  :href="`#tab_slider`"
                  @click="setComponent('slider')"
                  v-if="user_type == 1 && isLoggedIn"
                >Slider</v-tab>
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
                  :href="`#tab_coupons`"
                  @click="setComponent('coupons')"
                  v-if="user_type == 1 && isLoggedIn"
                >Coupons</v-tab>
                <v-tab
                  :href="`#tab_reviews`"
                  @click="
                active_user_drawer='user_drawer_reviews';active_admin_drawer='admin_drawer_reviews';$vuetify.goTo('#tab_benefits', {
                  offset: 100,
                  duration: 2000,
                  easing: 'easeInOutQuad'
                })
                "
                >Reviews</v-tab>
                <v-tab
                  :href="`#tab_allfaqs`"
                  @click="
                active_user_drawer='user_drawer_allfaqs';active_admin_drawer='admin_drawer_allfaqs';$vuetify.goTo('#tab_FAQS', {
                  offset: 100,
                  duration: 2000,
                  easing: 'easeInOutQuad'
                })
                "
                >FAQS</v-tab>
                <!-- <v-tab
                  class="font-weight-bold text--black"
                  :href="`#tab_services`"
                  v-if="user_type !=1"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';$vuetify.goTo('#tab_services', {
                offset: 100,
                duration: 2000,
                easing: 'easeInOutQuad'
              })"
                >Our Services</v-tab>-->
                <v-tab
                  :href="`#tab_contactus`"
                  v-if="user_type !=1"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';$vuetify.goTo('#tab_contactus', {
                offset: 100,
                duration: 2000,
                easing: 'easeInOutQuad'
              })"
                >Contact us</v-tab>
                <!-- <v-spacer />
                <v-divider class="ma-0 pa-0 grey lighten-2" vertical></v-divider>
                <v-tab
                  class="pl-0"
                  :href="`#tab_login`"
                  v-if="user_type ==-1 && !isLoggedIn"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';"
                >Sign in</v-tab>
                <v-divider class="ma-0 pa-0 grey lighten-2" vertical></v-divider>
                <v-tab
                  class="pl-0"
                  :href="`#tab_register`"
                  v-if="user_type ==-1 && !isLoggedIn"
                  @click="active_user_drawer='user_drawer_home';active_admin_drawer='admin_drawer_home';"
                >Sign up</v-tab>-->
              </v-tabs>
            </v-card>
          </v-hover>
        </v-container>
      </template>
    </v-app-bar>
    <v-row v-show="showParentContent">
      <v-col>
        <v-fab-transition>
          <v-btn
            v-scroll="onScroll"
            v-show="fab"
            fab
            dark
            fixed
            bottom
            right
            color="black"
            @click="toTop"
            class="hovered-button-black"
          >
            <v-icon large class="arrow">keyboard_arrow_up</v-icon>
          </v-btn>
        </v-fab-transition>
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
      <v-img :aspect-ratio="16/9" src="images/logo.png" contain>
        <v-row align="end" class="lightbox white--text pa-2 fill-height">
          <v-col>
            <div class="subheading">{{user.name}}</div>
            <div class="body-1">{{user.email}}</div>
          </v-col>
        </v-row>
      </v-img>
      <v-list dense nav class="pt-3 white--text">
        <v-list-item-group color="primary" v-model="active_admin_drawer" active-class="pink--text ">
          <v-list-item
            @click="setComponent('home')"
            value="admin_drawer_home"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">home</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('slider')"
            value="admin_drawer_slider"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-image</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Slider</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('main')"
            value="admin_drawer_main"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-arrange-bring-forward</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Main</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('certificates')"
            value="admin_drawer_certificates"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-seal</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Certificates</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('orders')"
            value="admin_drawer_requests"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-receipt</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Orders</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('exams')"
            value="admin_drawer_exams"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-comment-text-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Exams</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('questions')"
            value="admin_drawer_questions"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-comment-question-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Questions</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('users')"
            value="admin_drawer_users"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-account-multiple</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Users</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('sessions')"
            value="admin_drawer_sessions"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-run-fast</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Sessions</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('coupons')"
            value="admin_drawer_coupons"
            class="active-drawer-item-list"
          >
            <v-list-item-action>
              <v-icon class="white--text">mdi-cash</v-icon>
            </v-list-item-action>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Coupons</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('reviews')"
            value="admin_drawer_reviews"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">preview</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Reviews</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('allfaqs')"
            value="admin_drawer_allfaqs"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">mdi-help</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">FAQS</v-list-item-title>
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
          <v-list-item
            @click="setComponent('home')"
            value="user_drawer_home"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">home</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-divider></v-divider> -->
          <v-list-item
            @click="setComponent('userboard')"
            value="user_drawer_requests"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">mdi-book-multiple</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Orders</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-group prepend-icon="mdi-cart" :value="false">
            <template v-slot:activator>
              <v-list-item-title class="white--text active-drawer-item py-1">Shopping Cart</v-list-item-title>
            </template>
            <v-list-item
              @click="setComponent('cart')"
              value="user_drawer_cart"
              class="active-drawer-item-list ml-4"
            >
              <v-list-item-icon v-if="numInCart==0">
                <v-icon class="white--text">mdi-cart-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-icon v-if="numInCart>0">
                <v-icon class="white--text">mdi-cart</v-icon>
              </v-list-item-icon>
              <v-list-item-content class="active-drawer-item-content">
                <v-list-item-title class="white--text active-drawer-item py-1">Cart</v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-badge
                  color="error"
                  :content="numInCart"
                  v-if="numInCart >0 && isLoggedIn"
                  overlap
                  class="ml-4 mr-4"
                ></v-badge>
              </v-list-item-action>
            </v-list-item>
            <v-list-item
              @click="setComponent('cart')"
              value="user_drawer_whishlist"
              class="active-drawer-item-list ml-4"
            >
              <v-list-item-icon v-if="numInWhishlist==0">
                <v-icon class="white--text">mdi-heart-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-icon v-if="numInWhishlist>0">
                <v-icon class="white--text">mdi-heart</v-icon>
              </v-list-item-icon>
              <v-list-item-content class="active-drawer-item-content">
                <v-list-item-title class="white--text active-drawer-item py-1">Whish List</v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-badge
                  color="error"
                  :content="numInWhishlist"
                  v-if="numInWhishlist >0 && isLoggedIn"
                  overlap
                  class="ml-4 mr-4"
                ></v-badge>
              </v-list-item-action>
            </v-list-item>
            <v-list-item
              @click="setComponent('cart')"
              value="user_drawer_savedlist"
              class="active-drawer-item-list ml-4"
            >
              <v-list-item-icon v-if="numInSavedlist==0">
                <v-icon class="white--text">mdi-clock-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-icon v-if="numInSavedlist>0">
                <v-icon class="white--text">mdi-clock</v-icon>
              </v-list-item-icon>
              <v-list-item-content class="active-drawer-item-content">
                <v-list-item-title class="white--text active-drawer-item py-1">Saved List</v-list-item-title>
              </v-list-item-content>
              <v-list-item-action>
                <v-badge
                  color="error"
                  :content="numInSavedlist"
                  v-if="numInSavedlist >0 && isLoggedIn"
                  overlap
                  class="ml-4 mr-4"
                ></v-badge>
              </v-list-item-action>
            </v-list-item>
          </v-list-group>
          <v-list-item
            @click="setComponent('reviews')"
            value="user_drawer_reviews"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">preview</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">Reviews</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
            @click="setComponent('allfaqs')"
            value="user_drawer_allfaqs"
            class="active-drawer-item-list"
          >
            <v-list-item-icon>
              <v-icon class="white--text">preview</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="active-drawer-item-content">
              <v-list-item-title class="white--text active-drawer-item py-1">FAQS</v-list-item-title>
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
    <v-main class="pa-0 ma-0">
      <v-app fluid>
        <v-flex style="margin-top:105px">
          <component
            class="myFont"
            id="tab_home"
            v-intersect="handleHomeIntersect"
            :verification_message="verification_message"
            :verification_result="verification_result"
            :isLoggedIn="isLoggedIn"
            :certificates="certificates"
            :reference_singlecertificate="reference_singlecertificate"
            @linkToTerms="linkToTerms"
            @linkToHome="linkToHome"
            @linkToCart="linkToCart"
            @linkToOrders="linkToOrders"
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
            @showCheckout="showCheckout"
            @loggedIn="change"
            @StartSession="StartSession"
            @verification_result_hide="verification_result_hide"
            :is="activeComponent"
          ></component>
        </v-flex>
        <Benefits
          @linkToHome="linkToHome"
          @linkToReviews="linkToReviews"
          v-show="showParentContent"
          class="myFont mt-10"
          id="tab_benefits"
          v-intersect="handleBenefitsIntersect"
        />
        <FAQS
          @linkToHome="linkToHome"
          @linkToAllFAQS="linkToAllFAQS"
          v-show="showParentContent"
          class="myFont mt-10"
          id="tab_FAQS"
          v-intersect="handleFAQSIntersect"
        />
        <!-- <Services
          v-show="showParentContent"
          class="myFont mt-10"
          id="tab_services"
          v-intersect="handleServicesIntersect"
        />-->
        <ContactUs
          v-show="showParentContent"
          class="myFont mt-10"
          id="tab_contactus"
          v-intersect="handleContactUsIntersect"
        />
        <SubFooter
          v-show="showParentContent"
          class="myFont mt-10"
          id="subfooter"
          :isLoggedIn="isLoggedIn"
          :availableCertificatesLength="certificates.length"
          @linkToHome="linkToHome"
          @linkToOrders="linkToOrders"
          @linkToCart="linkToCart"
          @linkToReviews="linkToReviews"
          @linkToAllFAQS="linkToAllFAQS"
          @linkToTerms="linkToTerms"
          @linkToAbout="linkToAbout"
        />
        <!-- <Terms @linkToHome="linkToHome" />
        <About @linkToHome="linkToHome" />-->
        <Footer v-show="showParentContent" class="myFont mt-10 mb-0" id="footer" />
        <ShoppingCartDialog
          :showShoppingCartDialog="showShoppingCartDialog"
          @closeShoppingCartDialog="closeShoppingCartDialog"
          :certificates="certificates"
        />
        <WhishlistDialog
          :showWhishlistDialog="showWhishlistDialog"
          @closeWhishlistDialog="closeWhishlistDialog"
          :certificates="certificates"
        />
        <SavedlistDialog
          :showSavedlistDialog="showSavedlistDialog"
          @closeSavedlistDialog="closeSavedlistDialog"
          :certificates="certificates"
        />
      </v-app>
    </v-main>
  </span>
</template>


<script>
import Slider from "../admin/slider";
import Main from "../admin/Main";
import Users from "../admin/Users";
import Sessions from "../admin/Sessions";
import Coupons from "../admin/Coupons";
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
import ShoppingCartDialog from "../appcore/ShoppingCartDialog";
import Cart from "../../views/Cart";
import Reviews from "../../views/Reviews";
import AllFAQS from "../../views/AllFAQS";
import Terms from "../../views/Terms";
import About from "../../views/About";
import WhishlistDialog from "../appcore/WhishlistDialog";
import SavedlistDialog from "../appcore/SavedlistDialog";
import Home from "../../views/Home";
import SingleCertificate from "../../views/SingleCertificate";
import Checkout from "../../views/Checkout";
import Confirmation from "../../views/Confirmation";
import StartSession from "../../views/StartSession";
import * as easings from "vuetify/es5/services/goto/easing-patterns";

import ScrollAnimation from "../../directives/scrollanimation";
Vue.directive("scrollanimation", ScrollAnimation);

import Benefits from "./Benefits";
import FAQS from "./FAQS";
// import Services from "./Services";
import ContactUs from "./ContactUs";
import SubFooter from "./SubFooter";
import Footer from "./Footer";

export default {
  filters: {
    dollars: (num) => `$${num}`,
  },
  data() {
    return {
      reference_singlecertificate: "",
      abreviatedName: "",
      showShoppingCartDialog: false,
      showWhishlistDialog: false,
      showSavedlistDialog: false,
      certificates: [],
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
      NotLoggedInMenu: "",
      ShoppingCartMenu: "",
      WhishListMenu: "",
      LoggedInMenu: "",
      activeComponentName: "",
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
    Slider,
    Main,
    Users,
    Sessions,
    Coupons,
    Certificates,
    Exams,
    Questions,
    Orders,
    Benefits,
    FAQS,
    AllFAQS,
    Terms,
    About,
    ContactUs,
    Footer,
    SubFooter,
    Cart,
    ShoppingCartDialog,
    WhishlistDialog,
    SavedlistDialog,
    Reviews,
  },
  computed: {
    inCart() {
      return this.$store.getters.inCart;
    },
    numInCart() {
      return this.inCart.length;
    },
    inWhishlist() {
      return this.$store.getters.inWhishlist;
    },
    numInWhishlist() {
      return this.inWhishlist.length;
    },
    inSavedlist() {
      return this.$store.getters.inSavedlist;
    },
    numInSavedlist() {
      return this.inSavedlist.length;
    },
    total() {
      return this.shoppingcart.reduce((acc, cur) => acc + cur.price, 0);
    },
    /* snip */
    shoppingcart() {
      return this.$store.getters.inCart.map((cartItem) => {
        return this.certificates.find((certificate) => {
          return cartItem === certificate.id;
        });
      });
    },
    Whishlist() {
      return this.$store.getters.inWhishlist.map((item) => {
        return this.certificates.find((certificate) => {
          return item === certificate.id;
        });
      });
    },
  },
  mounted() {
    this.setDefaults();
  },
  beforeMount() {
    axios
      .get("/api/certificates")
      .then((response) => {
        this.certificates = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
    // alert(JSON.stringify(this.$route.query.token));
    if (this.$route.query.token) {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.LoggedInMenu = "";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
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
      this.LoggedInMenu = "";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
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
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = ForgotPassword;
      this.activeComponentName = "forgotpassword";
      this.$router.push({
        name: "reset-password",
      });
    },
    PasswordWasReset() {
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = Login;
      this.activeComponentName = "login";
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
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = Home;
      this.activeComponentName = "home";
    },
    showSingleCertificate(id, reference_singlecertificate) {
      this.reference_singlecertificate = reference_singlecertificate;
      this.showParentContent = true;
      // this.active_tab = "tab_requests";
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.active_user_drawer = "user_drawer_requests";
      this.activeComponent = SingleCertificate;
      this.activeComponentName = "singlecertificate";
      this.$router
        .push({
          name: "single-certificates",
          params: { id: id },
        })
        .catch(() => {});
    },
    showCheckout() {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.active_user_drawer = "user_drawer_requests";
      this.activeComponent = Checkout;
      this.activeComponentName = "checkout";
      this.$router
        .push({
          name: "checkout",
        })
        .catch(() => {});
    },
    showConfirmation() {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.active_user_drawer = "user_drawer_requests";
      this.activeComponent = Confirmation;
      this.activeComponentName = "confirmation";
      this.$router.push({
        name: "confirmation",
      });
    },
    StartSession(certificate_id) {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.LoggedInMenu = "";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = StartSession;
      this.activeComponentName = "startsession";
      this.$router.push({
        name: "start-session",
        params: { id: certificate_id },
      });
    },
    showExams() {
      this.showParentContent = true;
      this.active_tab = "tab_requests";
      this.LoggedInMenu = "";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = UserBoard;
      this.activeComponentName = "userboard";
      this.$router.push({ name: "userboard" }).catch((err) => {});
    },
    enrollLogin(next) {
      // alert(next);
      this.showParentContent = false;
      this.active_tab = "tab_home";
      this.LoggedInMenu = "";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = Login;
      this.activeComponentName = "login";
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
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.activeComponent = Register;
      this.activeComponentName = "register";
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
        let nameParts = this.user.name.split(" ");
        let f = "";
        let l = "";
        f = nameParts[0][0];
        if (nameParts[1]) l = nameParts[1][0];
        this.abreviatedName = f.toUpperCase() + l.toUpperCase();
      }
    },
    change() {
      this.isLoggedIn = localStorage.getItem("genius.jwt") != null;
      this.NotLoggedInMenu = "";
      this.LoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.setDefaults();
    },
    hasNext(url) {
      let parts = url.split("/");
      // alert(parts[2]);
      let page = parts[1];
      if (page === "certificates") {
        let id = parts[2];
        this.showParentContent = true;
        this.active_tab = "tab_requests";
        this.LoggedInMenu = "LoggedInMenu_userboard";
        this.activeComponent = SingleCertificate;
        this.activeComponentName = "singlecertificate";
        this.NotLoggedInMenu = "";
        this.ShoppingCartMenu = "";
        this.WhishListMenu = "";
        this.$router
          .push({
            name: "single-certificates",
            params: { id: id },
          })
          .catch(() => {});
      } else if (page === "checkout") {
        let price = parts[2];
        this.showParentContent = true;
        this.active_tab = "tab_requests";
        this.LoggedInMenu = "LoggedInMenu_userboard";
        this.activeComponent = Checkout;
        this.activeComponentName = "checkout";
        this.NotLoggedInMenu = "";
        this.ShoppingCartMenu = "";
        this.WhishListMenu = "";
        this.$router
          .push({
            name: "checkout",
            params: { price: price },
          })
          .catch(() => {});
      } else if (page === "cart") {
        // let price = parts[2];
        this.showParentContent = true;
        this.active_tab = "tab_cart";
        this.LoggedInMenu = "LoggedInMenu_userboard";
        this.activeComponent = Cart;
        this.activeComponentName = "cart";
        this.NotLoggedInMenu = "";
        this.ShoppingCartMenu = "";
        this.WhishListMenu = "";
        this.$router
          .push({
            name: "cart",
          })
          .catch(() => {});
      }
    },
    noNext() {
      if (this.user_type == 1) {
        this.showParentContent = true;
        this.active_tab = "tab_main";
        this.NotLoggedInMenu = "";
        this.ShoppingCartMenu = "";
        this.WhishListMenu = "";
        this.LoggedInMenu = "LoggedInMenu_main";
        this.active_admin_drawer = "admin_drawer_main";
        this.activeComponent = Main;
        this.activeComponentName = "main";
      } else if (this.user_type == 0) {
        this.showParentContent = true;
        this.active_tab = "tab_requests";
        this.LoggedInMenu = "LoggedInMenu_userboard";
        this.NotLoggedInMenu = "";
        this.WhishListMenu = "";
        this.ShoppingCartMenu = "";
        this.active_user_drawer = 0;
        this.activeComponent = UserBoard;
        this.activeComponentName = "userboard";
      }
    },
    logout() {
      this.showParentContent = true;
      localStorage.removeItem("genius.jwt");
      localStorage.removeItem("genius.user");
      this.user_type = -1;
      this.change();
      this.active_tab = "tab_home";
      this.NotLoggedInMenu = "";
      this.ShoppingCartMenu = "";
      this.WhishListMenu = "";
      this.LoggedInMenu = "";
      this.activeComponent = Home;
      this.activeComponentName = "home";
      this.$router.push("/").catch((err) => {});
    },
    setComponent(value) {
      this.reference_singlecertificate = "";
      this.activeComponentName = value;
      if (this.user_type == 1) {
        switch (value) {
          case "terms":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = Terms;
            this.active_admin_drawer = "";
            this.$router.push({ name: "terms" }).catch((err) => {});
            break;
          case "about":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = About;
            this.active_admin_drawer = "";
            this.$router.push({ name: "about" }).catch((err) => {});
            break;
          case "allfaqs":
            this.showParentContent = true;
            this.active_tab = "tab_allfaqs";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = AllFAQS;
            this.active_admin_drawer = "admin_drawer_allfaqs";
            this.$router.push({ name: "allfaqs" }).catch((err) => {});
            break;
          case "reviews":
            this.showParentContent = true;
            this.active_tab = "tab_reviews";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = Reviews;
            this.active_admin_drawer = "admin_drawer_reviews";
            this.$router.push({ name: "reviews" }).catch((err) => {});
            break;
          case "cart":
            this.showParentContent = true;
            this.active_tab = "tab_cart";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_cart";
            this.active_user_drawer = "user_drawer_cart";
            this.activeComponent = Cart;
            this.$router.push({ name: "cart" }).catch((err) => {});
            break;
          case "slider":
            this.showParentContent = true;
            this.active_tab = "tab_slider";
            this.NotLoggedInMenu = "";
            this.WhishListMenu = "";
            this.ShoppingCartMenu = "";
            this.LoggedInMenu = "";
            this.active_admin_drawer = "admin_drawer_slider";
            this.activeComponent = Slider;
            // this.$router.push({
            //   name: "slider",
            // });
            break;
          case "main":
            this.showParentContent = true;
            this.active_tab = "tab_main";
            this.NotLoggedInMenu = "";
            this.WhishListMenu = "";
            this.ShoppingCartMenu = "";
            this.LoggedInMenu = "LoggedInMenu_main";
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
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
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
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_userboard";
            this.active_admin_drawer = "admin_drawer_requests";
            this.activeComponent = UserBoard;
            this.$router.push({ name: "userboard" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Login;
            this.$router.push({ name: "login" }).catch((err) => {});
            break;
          case "users":
            this.showParentContent = true;
            this.active_tab = "tab_users";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_users";
            this.activeComponent = Users;
            this.$router
              .push({ name: "admin-pages", params: { page: "users" } })
              .catch((err) => {});
            break;
          case "sessions":
            this.showParentContent = true;
            this.active_tab = "tab_sessions";
            this.LoggedInMenu = "";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_sessions";
            this.activeComponent = Sessions;
            this.$router
              .push({ name: "admin-pages", params: { page: "sessions" } })
              .catch((err) => {});
            break;
          case "coupons":
            this.showParentContent = true;
            this.active_tab = "tab_coupons";
            this.LoggedInMenu = "";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_coupons";
            this.activeComponent = Coupons;
            this.$router
              .push({ name: "admin-pages", params: { page: "coupons" } })
              .catch((err) => {});
            break;
          case "certificates":
            this.showParentContent = true;
            this.active_tab = "tab_certificates";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
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
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
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
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
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
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
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
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_changepassword";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = ChangePassword;
            this.$router.push({ name: "changepassword" }).catch((err) => {});
            break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_admin_drawer = "admin_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      } else if (this.user_type == 0) {
        switch (value) {
          case "terms":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = Terms;
            this.active_admin_drawer = "";
            this.$router.push({ name: "terms" }).catch((err) => {});
            break;
          case "about":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = About;
            this.active_admin_drawer = "";
            this.$router.push({ name: "about" }).catch((err) => {});
            break;
          case "allfaqs":
            this.showParentContent = true;
            this.active_tab = "tab_allfaqs";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = AllFAQS;
            this.active_user_drawer = "user_drawer_allfaqs";
            this.$router.push({ name: "allfaqs" }).catch((err) => {});
            break;
          case "reviews":
            this.showParentContent = true;
            this.active_tab = "tab_reviews";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "user_drawer_reviews";
            this.activeComponent = Reviews;
            this.$router.push({ name: "reviews" }).catch((err) => {});
            break;
          case "home":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
          case "userboard":
            this.showParentContent = true;
            this.active_tab = "tab_requests";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_userboard";
            this.active_user_drawer = "user_drawer_requests";
            this.activeComponent = UserBoard;
            this.$router.push({ name: "userboard" }).catch((err) => {});
            break;
          case "cart":
            this.showParentContent = true;
            this.active_tab = "tab_cart";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_cart";
            this.active_user_drawer = "user_drawer_cart";
            this.activeComponent = Cart;
            this.$router.push({ name: "cart" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Login;
            this.$router.push({ name: "login" }).catch((err) => {});
            break;
          case "changepassword":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "LoggedInMenu_changepassword";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = ChangePassword;
            this.$router.push({ name: "changepassword" }).catch((err) => {});
            break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.active_user_drawer = "user_drawer_home";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      } else if (this.user_type == -1) {
        // alert(value);
        switch (value) {
          case "terms":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = Terms;
            this.active_admin_drawer = "";
            this.$router.push({ name: "terms" }).catch((err) => {});
            break;
          case "about":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = About;
            this.active_admin_drawer = "";
            this.$router.push({ name: "about" }).catch((err) => {});
            break;
          case "allfaqs":
            this.showParentContent = true;
            this.active_tab = "tab_allfaqs";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = AllFAQS;
            // this.active_admin_drawer = "admin_drawer_faqs";
            this.$router.push({ name: "allfaqs" }).catch((err) => {});
            break;
          case "reviews":
            this.showParentContent = true;
            this.active_tab = "tab_reviews";
            this.NotLoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.LoggedInMenu = "";
            this.active_user_drawer = "";
            this.activeComponent = Reviews;
            this.$router.push({ name: "reviews" }).catch((err) => {});
            break;
          case "home":
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
          case "register":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.activeComponent = Register;
            this.$router.push({ name: "register" }).catch((err) => {});
            break;
          case "login":
            this.showParentContent = false;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
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
          case "cart":
            this.showParentContent = true;
            this.active_tab = "tab_cart";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            // this.active_user_drawer = "user_drawer_cart";
            this.activeComponent = Cart;
            this.$router.push({ name: "cart" }).catch((err) => {});
            break;
          default:
            this.showParentContent = true;
            this.active_tab = "tab_home";
            this.NotLoggedInMenu = "";
            this.LoggedInMenu = "";
            this.ShoppingCartMenu = "";
            this.WhishListMenu = "";
            this.activeComponent = Home;
            this.$router.push({ name: "home" }).catch((err) => {});
            break;
        }
      }
    },
    handleHomeIntersect(entries, observer) {
      // if (!this.isLoggedIn && entries[0].isIntersecting) {
      //   this.active_tab = "tab_home";
      // }
    },
    handleContactUsIntersect(entries, observer) {
      // if (!this.isLoggedIn && entries[0].isIntersecting) {
      //   this.active_tab = "tab_contactus";
      // }
    },
    handleBenefitsIntersect(entries, observer) {
      // if (!this.isLoggedIn && entries[0].isIntersecting) {
      //   this.active_tab = "tab_benefits";
      // }
    },
    handleFAQSIntersect(entries, observer) {
      // if (!this.isLoggedIn && entries[0].isIntersecting) {
      //   this.active_tab = "tab_faqs";
      // }
    },

    linkToHome() {
      this.setComponent("home");
    },
    linkToOrders() {
      this.setComponent("userboard");
    },
    linkToCart() {
      this.setComponent("cart");
    },
    linkToReviews(from) {
      if (from == "Benefits") {
        this.$vuetify.goTo("#navbar", {
          offset: 100,
          duration: 2000,
          easing: "easeInOutQuad",
        });
      }
      this.setComponent("reviews");
    },
    linkToAllFAQS(from) {
      if (from == "FAQS") {
        this.$vuetify.goTo("#navbar", {
          offset: 100,
          duration: 2000,
          easing: "easeInOutQuad",
        });
      }
      this.setComponent("allfaqs");
    },
    linkToTerms() {
      this.setComponent("terms");
    },
    linkToAbout() {
      this.setComponent("about");
    },
    linkToLogin() {
      this.NotLoggedInMenu = "NotLoggedInMenu_login";
      this.setComponent("login");
    },
    linkToRegister() {
      this.NotLoggedInMenu = "NotLoggedInMenu_register";
      this.setComponent("register");
    },
    verification_result_hide() {
      this.verification_message = "";
      this.verification_result = false;
    },
    alertShoppingCartDialog() {
      this.showShoppingCartDialog = true;
    },
    closeShoppingCartDialog() {
      this.LoggedInMenu = "";
      this.showShoppingCartDialog = false;
    },
    alertWhishlistDialog() {
      this.showWhishlistDialog = true;
    },
    closeWhishlistDialog() {
      this.LoggedInMenu = "";
      this.showWhishlistDialog = false;
    },
    alertSavedlistDialog() {
      this.showSavedlistDialog = true;
    },
    closeSavedlistDialog() {
      this.LoggedInMenu = "";
      this.showSavedlistDialog = false;
    },
    checkout() {},
    addToCart(certificate_id) {
      this.$store.dispatch("addToCart", certificate_id);
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
  color: black !important;
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
  /* z-index: 1001 !important; */
}
.navBar {
  /* z-index: 1000 !important; */
}
.v-tab:hover {
  color: orange !important;
  background-color: rgb(248, 228, 190) !important;
}
.active-menu-item {
  font-size: 18px !important;
}
.active-menu-item-content:hover {
  transition: 0.2s;
  transform: translateX(5px);
  color: coral;
}
.active-menu-item:hover {
}
.active-menu-item-list:hover {
  background-color: rgb(248, 228, 190) !important;
}

.active-drawer-item {
  font-size: 18px !important;
}
.active-drawer-item-content:hover {
  transition: 0.2s;
  transform: translateX(5px);
  color: coral;
}
.active-drawer-item:hover {
}
.active-drawer-item-list:hover {
  background-color: #ffcc80 !important;
}

.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
.hovered-button-black:hover {
  background-color: white !important;
  color: black !important;
  border: 1px solid black;
}
.hovered-button-scale:hover {
  transform: scale(1.25);
}
</style>