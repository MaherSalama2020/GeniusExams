<template>
  <div>
    <v-card v-if="option.isCorrect" height="75" class="bg-success text-white cursor-move">
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
                  medium
                  color="white"
                  @click="$emit('on-edit', option)"
                  icon
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
                  medium
                  color="white"
                  @click="$emit('on-delete', option)"
                  icon
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

    <v-card v-else height="75" class="cursor-move">
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
                  medium
                  color="black"
                  @click="$emit('on-edit', option)"
                  icon
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
                  medium
                  color="black"
                  @click="$emit('on-delete', option)"
                  icon
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
  methods: {},
};
</script>
<style >
.ellipsis {
  width: 400px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
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
button {
  outline: none;
}
button:hover {
  /* outline: 1px solid red; */
  color: firebrick;
}
.option-name {
  font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
  font-size: 1em;
}
/* Customize the label (the chkcontainer) */
.chkcontainer {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.chkcontainer input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.chkcontainer:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.chkcontainer input:checked ~ .checkmark {
  background-color: #2196f3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.chkcontainer input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.chkcontainer .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.textover {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 1; /* number of lines to show */
  -webkit-box-orient: vertical;
}
</style>
