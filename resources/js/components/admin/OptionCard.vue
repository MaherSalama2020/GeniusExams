<template>
  <div>
    <v-card
      v-if="option.isCorrect"
      v-bind:class="{'orange lighten-4':edit &&currentEditedOption==option,'success': currentEditedOption!=option }"
      class="cursor-move"
      height="75"
    >
      <v-container>
        <v-row justify="space-between" cols="2">
          <v-col cols="12" sm="10">
            <span
              class="font-weight-black textover"
            >{{optionsNames[option.sequence - 1]}}.{{option.name}}</span>
          </v-col>
          <v-col cols="12" sm="1">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  x-small
                  fab
                  dark
                  color="orange"
                  @click="editOption(option)"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>edit</v-icon>
                </v-btn>
              </template>
              Edit Option
            </v-tooltip>
          </v-col>
          <v-col cols="12" sm="1">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  dark
                  x-small
                  fab
                  color="orange"
                  @click="$emit('on-delete', option)"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>delete</v-icon>
                </v-btn>
              </template>
              Delete Option
            </v-tooltip>
          </v-col>
        </v-row>
      </v-container>
    </v-card>

    <v-card
      v-else
      height="75"
      v-bind:class="{'orange lighten-4':edit &&currentEditedOption==option }"
      class="cursor-move"
    >
      <v-container>
        <v-row justify="space-between" cols="2">
          <v-col cols="12" sm="10">
            <span
              class="font-weight-black textover"
            >{{optionsNames[option.sequence - 1]}}.{{option.name}}</span>
          </v-col>
          <v-col cols="12" sm="1">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  x-small
                  fab
                  dark
                  color="orange"
                  @click="editOption(option)"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>edit</v-icon>
                </v-btn>
              </template>
              Edit Option
            </v-tooltip>
          </v-col>
          <v-col cols="12" sm="1">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  x-small
                  fab
                  dark
                  color="orange"
                  @click="$emit('on-delete', option)"
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-icon>delete</v-icon>
                </v-btn>
              </template>
              Delete Option
            </v-tooltip>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ["index"],
  data() {
    return {
      edit: false,
      currentEditedOption: "",
      optionsNames: ["A", "B", "C", "D", "E", "F"],
    };
  },
  components: {},
  props: {
    option: {
      type: Object,
      default: () => ({}),
    },
  },
  methods: {
    editOption(option) {
      this.edit = true;
      this.currentEditedOption = option;
      this.$emit("on-edit", option);
    },
  },
};
</script>
<style >
.cursor-move {
  list-style: none;
  cursor: move;
  background: #fcfcfc;

  width: 100%;
  max-width: 100%;

  padding: 0.1em;
  margin-bottom: 0.3em;

  z-index: 1;

  box-shadow: rgba(0, 0, 0, 0.3) 0px 0px 1px 1px;
}

.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* number of lines to show */
  -webkit-box-orient: vertical;
}
</style>
