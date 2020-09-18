    <template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <v-card>
            <v-card-title>
              <v-btn @click="all" color="orange white--text" v-if="panel.length==0">all</v-btn>
              <v-btn @click="none" color="orange white--text" v-else>none</v-btn>
              <!-- <div>{{ panel }}</div> -->
              <!-- <button class="btn btn-primary" @click="newUser">Add New User</button> -->
              <v-spacer />
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
                clearable
                color="purple"
              ></v-text-field>
            </v-card-title>

            <span v-if="!search">
              <v-expansion-panels focusable color="warning" v-model="panel" multiple popout>
                <v-expansion-panel v-for="(session,index) in sessions" :key="index">
                  <v-expansion-panel-header>
                    {{session.user.name}}/{{session.user.email}}/{{session.exam.name}}
                    <v-spacer />
                    {{new Date(session.created_at).toLocaleDateString()}}
                    <!-- <template v-slot:actions>
            <v-icon color="teal">mdi-check</v-icon>
                    </template>-->
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-chip :color="getTypeColor(session.exam.type)" dark>{{ session.exam.type}}</v-chip>
                    Result: {{session.result}} %
                    <SessionAnswers
                      :answers="session.answers"
                      :certificate_id="session.certificate_id"
                      :exam_id="session.exam_id"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
            </span>
            <span v-if="search">
              <v-expansion-panels focusable color="warning" v-model="panel" multiple popout>
                <v-expansion-panel v-for="(session,index) in filteredSessions" :key="index">
                  <v-expansion-panel-header>
                    {{session.user.name}}/{{session.user.email}}/{{session.exam.name}}
                    <v-spacer />
                    {{new Date(session.created_at).toLocaleDateString()}}
                    <!-- <template v-slot:actions>
            <v-icon color="teal">mdi-check</v-icon>
                    </template>-->
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-chip :color="getTypeColor(session.exam.type)" dark>{{ session.exam.type}}</v-chip>
                    Correct Answers: {{session.result}}
                    <SessionAnswers
                      :answers="session.answers"
                      :certificate_id="session.certificate_id"
                      :exam_id="session.exam_id"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
            </span>
            <v-alert
              v-if="noresults"
              type="error"
              border="left"
              colored-border
              color="error accent-4"
              elevation="2"
              dismissible
              class="ma-4"
            >Your search for "{{ search }}" found no results.</v-alert>
            <v-pagination
              v-model="pagination.current_page"
              :length="pagination.last_page"
              @input="fetchSessions"
              class="mt-3 mb-0"
              color="orange"
            ></v-pagination>
            <div class="text-center mt-0">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-3 row justify-content-end">
                  <v-subheader>Sessions per page:</v-subheader>
                </div>
                <div class="col-md-1 row justify-content-start">
                  <v-select
                    dense
                    :items="pagescount"
                    name="pagescount"
                    v-model="selected_per_page"
                    @change="fetchSessions(1)"
                    color="purple"
                  ></v-select>
                </div>
                <div class="col-md-4"></div>
              </div>
            </div>
          </v-card>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import SessionAnswers from "../appcore/SessionAnswers";

export default {
  data() {
    return {
      sessions: [],
      filteredSessions: [],
      panel: [],
      pagination: {},
      pagescount: [1, 2, 3, 4, 5, 10, 25, 50, 100],
      selected_per_page: 5,
      search: "",
      noresults: false,
    };
  },
  components: { SessionAnswers },
  beforeMount() {
    axios.get("/api/sessions/").then((response) => {
      this.sessions = response.data;
    });
  },
  //   computed: {
  //     filteredSessions() {
  //       return this.sessions.filter((item) => {
  //         return (
  //           item.user.name.toLowerCase().includes(this.search.toLowerCase()) ||
  //           item.user.email.toLowerCase().includes(this.search.toLowerCase()) ||
  //           new Date(item.created_at)
  //             .toLocaleDateString()
  //             .includes(this.search.toLowerCase())
  //         );
  //       });
  //     },
  //   },
  // filters:{
  // count: function(value){
  //     return value.length;
  //     }
  // },
  watch: {
    search: {
      handler() {
        if (this.search) {
          this.filteredSessions = this.sessions.filter((item) => {
            return (
              item.user.name
                .toLowerCase()
                .includes(this.search.toLowerCase()) ||
              item.user.email
                .toLowerCase()
                .includes(this.search.toLowerCase()) ||
              item.exam.name
                .toLowerCase()
                .includes(this.search.toLowerCase()) ||
              new Date(item.created_at)
                .toLocaleDateString()
                .includes(this.search.toLowerCase())
            );
          });
          // console.log(JSON.stringify(filteredSessions));
          if (this.filteredSessions.length == 0) this.noresults = true;
          else this.noresults = false;
        } else {
          this.noresults = false;
        }
        // console.log(this.noresults);
        // this.sessions = filteredSessions;
      },
    },
  },
  methods: {
    fetchSessions(page_number) {
      this.search = "";
      let page_url = "/api/sessions/paginate?page=" + page_number;
      let selected_per_page = this.selected_per_page;

      axios
        .post(page_url, { selected_per_page })
        .then((response) => {
          this.sessions = response.data.data;
          this.makePagination(response.data.meta, response.data.links);
        })
        .catch((error) => console.log(error));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        per_page: meta.per_page,
        first: links.first,
        last: links.last,
        next: links.next,
        prev: links.prev,
      };
      this.pagination = pagination;
    },
    getTypeColor(type) {
      if (type == "Exam") return "red";
      else return "green";
    },
    // Create an array the length of our items
    // with all values as true
    all() {
      this.panel = Object.keys(this.sessions).map((k, i) => i);
    },
    // Reset the panel
    none() {
      this.panel = [];
    },
  },
};
</script>