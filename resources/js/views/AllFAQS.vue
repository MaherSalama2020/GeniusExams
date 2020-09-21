<template>
  <v-main>
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
            Home
          </v-tooltip>
          <v-icon small color="white" class="mr-1 ml-1">arrow_forward</v-icon>
          <span class="inactiveBreadcrumb">FAQS</span>
        </v-row>
        <v-row class="ml-15 mt-3" no-gutters>
          <h3 class="white--text">FAQS</h3>
        </v-row>
      </v-col>
    </div>
    <v-row no-gutters justify="start" align="center">
      <div class="big-title ml-10 border-bottom black--text">Find Your Questions & Answers.</div>
    </v-row>
    <v-row class="mt-15">
      <v-col class="col-md-1"></v-col>
      <v-col class="col-md-10">
        <v-expansion-panels popout :hover="true">
          <v-expansion-panel
            v-for="(item,i) in faqs"
            :key="i"
            active-class="orange lighten-5 underline-from-top"
          >
            <v-expansion-panel-header class="expansion-panel-header font-weight-bold">
              <span class="expansion-panel-title">{{i+1 +"- "+item.title}}</span>
              <template v-slot:actions>
                <v-icon color="orange">mdi-arrow-down-drop-circle-outline</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content
              color="white"
              class="black--text underline-from-top expansion-panel-text"
            >{{item.body}}</v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
      <v-col class="col-md-1"></v-col>
    </v-row>
  </v-main>
</template>
<script>
export default {
  props: ["isLoggedIn"],
  data() {
    return {
      faqs: [
        {
          title: "What is PMP Certification?",
          body:
            "PMP  stands for Project Management Professional (PMP). PMP Certification, offered by  the Project Management Institute(PMI), is an industry-recognized credential forproject managers. PMP certification demonstrates the project manager'sexperience, knowledge, skills, and competencies  required to lead and direct  projects.",
        },
        {
          title:
            "Who is the PMP certification for? What are the benefits of getting PMP certified? ",
          body:
            "The PMP certification is ideal for  Project and Program Managers, Delivery Managers, Project/Team Leaders,Consultants, Senior Executives, IT Managers, Project Team Members, and  PMO  staff.",
        },
        {
          title: "What are the PMP Certification eligibility requirements?",
          body: "requirementsfor PMP® certification",
        },
        {
          title: "What are the PMP Certification Exam fees? ",
          body: " for PMI® members and nonmembers are:",
        },
      ],
    };
  },

  methods: {
    linkToHome() {
      this.$emit("linkToHome");
    },
  },
};
</script>
