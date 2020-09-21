<template>
  <v-container fluid>
    <v-col pa-5>
      <v-row no-gutters justify="center" align="start">
        <v-col cols="12" sm="auto" md="auto" lg="auto" class="underline-from-center">
          <span class="big-dot mr-2">.</span>
          <span class="big-dot mr-2">.</span>
          <span class="medium-title mr-6 ml-4 black--text">FAQS</span>
          <span class="big-dot mr-2">.</span>
          <span class="big-dot">.</span>
        </v-col>
      </v-row>
      <v-row no-gutters justify="center" align="start" class="py-0">
        <v-col cols="12" sm="auto" md="auto" lg="auto">
          <span class="x-big-title mr-6 ml-4 mb-0 black--text">Frequently Ask & Questions</span>
        </v-col>
      </v-row>
      <v-row no-gutters justify="center" align="center">
        <v-container fluid class="mx-5">
          <v-col class="col-md-12">
            <v-row justify="center">
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
            </v-row>
          </v-col>
        </v-container>
      </v-row>

      <v-row no-gutters justify="center" align="start" class="hidden-sm-and-down">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn fab class="mt-5 orange" @click="linkToAllFAQS" v-bind="attrs" v-on="on">
              <v-icon large color="white">expand_more</v-icon>
            </v-btn>
          </template>
          Show all FAQS
        </v-tooltip>
      </v-row>
    </v-col>
  </v-container>
</template>

<script>
export default {
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
  mounted() {},
  methods: {
    linkToAllFAQS() {
      this.$emit("linkToAllFAQS", "FAQS");
    },
  },
};
</script>
<style>
.expansion-panel-title {
  font-size: 1.1em;
}

.expansion-panel-header:hover {
  /* background-color: #fff3e0; */
  color: coral;
}
.expansion-panel-text {
  font-size: 1.3em;
}
</style>
