<template>
  <v-row justify="center">
    <v-layout row justify-center>
      <v-dialog v-model="spinner" hide-overlay persistent width="300">
        <v-card color="orange" dark>
          <v-card-text>
            Processing
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-dialog v-model="showCertificateDialog" width="600px" persistent>
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Edit Certificate</span>
          <span v-else class="headline">Add Certificate</span>
          <v-spacer />
          <span @click="close">
            <v-icon class="close" medium>close</v-icon>
          </span>
        </v-card-title>
        <hr class="hr mt-0" />
        <div v-if="edit" class="col-md-12 row">
          <h4>
            <v-icon medium color="cyan">help</v-icon>
            {{ data.name }}
          </h4>
        </div>
        <v-card-text>
          <v-form v-model="isValid">
            <v-text-field
              dense
              prepend-inner-icon="mdi-pencil"
              label="Certificate Name"
              v-model="data.name"
              :rules="requiredRules"
              error-count="2"
              required
              outlined
              autofocus
            />
            <div class="form-row">
              <v-text-field
                dense
                prepend-inner-icon="mdi-cash"
                type="number"
                label="Price"
                v-model="data.price"
                :rules="numberRules"
                class="col-md-3 mr-3"
                required
                outlined
              />
              <v-text-field
                dense
                prepend-inner-icon="mdi-calendar"
                type="number"
                label="Available for"
                v-model="data.available_for"
                :rules="numberRules"
                class="col-md-3 mr-3"
                required
                outlined
              />
              <v-text-field
                dense
                prepend-inner-icon="mdi-counter"
                type="number"
                label="Booked NO."
                class="col-md-3"
                v-model="data.bookedNO"
                outlined
                disabled
              />
            </div>
            <v-textarea
              dense
              label="Description"
              v-model="data.description"
              :rules="requiredRules"
              error-count="2"
              required
              outlined
              no-resize
            />
            <!-- <v-image-input
              v-model="data.image"
              @change="attachFile"
              :image-quality="0.85"
              image-format="jpeg"
              hideActions
              :imageHeight="75"
            />-->
            <div class="form-group">
              <img
                id="cImage"
                class="col-md-3"
                :src="data.image"
                :alt="data.name"
                v-show="data.image"
                @dblclick="alertImageDialog"
              />
              <v-text-field
                label="Select Image"
                @click="pickImage"
                v-model="data.image"
                prepend-icon="image"
                color="purple"
                tabindex="-1"
              ></v-text-field>

              <input
                class="form-control"
                type="file"
                id="certificatefile"
                @change="attachFile"
                style="display: none"
                accept="image/*"
                ref="image"
              />
            </div>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            v-if="edit"
            :disabled="!isValid"
            color="orange white--text"
            @click="saveCertificate"
            class="hovered-button"
            >Save Certificate</v-btn
          >
          <v-btn
            v-else
            :disabled="!isValid"
            color="orange white--text"
            @click="addCertificate"
            class="hovered-button"
            >Add Certificate</v-btn
          >
        </v-card-actions>
      </v-card>
      <ImageDialog
        :showImageDialog="showImageDialog"
        :image="data.image"
        @closeImageDialog="closeImageDialog"
      />
    </v-dialog>
  </v-row>
</template>



<script>
import VImageInput from "vuetify-image-input";
import ImageDialog from "../appcore/ImageDialog";
export default {
  props: ["certificate", "edit", "certificates", "showCertificateDialog"],
  data() {
    return {
      showImageDialog: false,
      spinner: false,
      imageChanged: false,
      attachment: null,
      isValid: true,
      requiredRules: [(v) => !!v || "required!!"],
      numberRules: [
        (v) => !!v || "required!! Must be a valid number.",
        (v) => (v && v > 0) || "Price must be more than 0.",
      ],
    };
  },
  components: { VImageInput, ImageDialog },
  // mounted() {
  //   alert(this.edit);
  // },
  computed: {
    data: function () {
      if (this.certificate != null) {
        return this.certificate;
      }
      return {
        name: null,
        price: null,
        bookedNO: null,
        available_for: null,
        description: null,
        image: null,
      };
    },
  },
  methods: {
    alertImageDialog() {
      this.showImageDialog = true;
    },
    closeImageDialog() {
      this.showImageDialog = false;
    },
    close(event) {
      this.$emit("close", this.certificate);
    },
    pickImage() {
      this.$refs.image.click();
    },
    attachFile(event) {
      // alert(event.target.files[0].name);
      if (event.target.files[0]) {
        this.spinner = true;
        let selectedImage = this.selectedImage;
        var formData = new FormData();
        this.attachment = event.target.files[0];
        formData.append("image", this.attachment);
        let headers = { "Content-Type": "multipart/form-data" };
        axios
          .post("/api/certificates/upload-file", selectedImage, formData, {
            headers,
          })
          .then((response) => {
            this.data.image = response.data;
            let image = response.data;
            this.imageChanged = true;
            this.spinner = false;
          })
          .catch((error) => {
            this.spinner = false;
          });
      }
    },
    saveCertificate(e) {
      e.preventDefault();
      let index = this.certificates.indexOf(this.certificate);
      let name = this.certificate.name;
      let bookedNO = this.certificate.bookedNO;
      let price = this.certificate.price;
      let available_for = this.certificate.available_for;
      let description = this.certificate.description;
      // if (name === "") return;
      // if (date === "") return;
      // if (startTime === "") return;
      // if (endTime === "") return;
      // if (duration === "") return;
      // if (duration < 0) return;
      // if (price === "") return;
      // if (price < 0) return;
      // if (description === "") return;

      let image = this.certificate.image;
      axios
        .put(`/api/certificates/${this.certificate.id}`, {
          name,
          bookedNO,
          price,
          available_for,
          description,
          image,
        })
        .then((response) => {
          this.$emit("close", this.certificate);
          this.certificates[index] = this.certificate;
          this.$emit("close", this.certificate);
        });
    },
    addCertificate(e) {
      e.preventDefault();
      let name = this.certificate.name;
      let bookedNO = this.certificate.bookedNO;
      let price = this.certificate.price;
      let available_for = this.certificate.available_for;
      let description = this.certificate.description;
      let image = "";
      if (!this.imageChanged)
        image = "http://knowledgewavws.org/images/exam.jpeg";
      else image = this.certificate.image;
      axios
        .post("/api/certificates/", {
          name,
          bookedNO,
          price,
          available_for,
          description,
          image,
        })
        .then((res) => {
          // alert(image);
          this.$emit("close", this.certificate);
          this.certificate.image = image;
          this.certificate.id = res.data.id;
          this.certificate.exams = [];
          this.certificates.unshift(this.certificate);
        });
    },
  },
};
</script>
<style scoped>
.close:hover {
  color: orange;
  cursor: pointer;
  transform: rotate(90deg);
}
.hr {
  border: 0;
  height: 3px;
  background-image: linear-gradient(
    to right,
    rgba(0, 0, 0, 0),
    rgba(0, 0, 0, 0.75),
    rgba(0, 0, 0, 0)
  );
}
.hovered-button:hover {
  background-color: transparent !important;
  color: orange !important;
  border: 1px solid orange;
}
</style>