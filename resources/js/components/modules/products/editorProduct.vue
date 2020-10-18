<template>
  <v-row>
    <v-col cols="12" md="6">
      <v-text-field
        ref="pbrandcode"
        v-model="pbrandcode"
        prepend-icon="mdi-barcode-scan"
        label="Barcode"
        clearable
      ></v-text-field>
      <v-text-field
        ref="pname"
        v-model="pname"
        prepend-icon="mdi-rename-box"
        label="Product Name"
        clearable
      ></v-text-field>
      <v-text-field
        ref="pcompany"
        v-model="pcompany"
        prepend-icon="mdi-rename-box"
        label="Company Name"
        clearable
      ></v-text-field>
      <v-text-field
        ref="psupplier"
        v-model="psupplier"
        prepend-icon="mdi-moped-outline"
        label="Product Supplier"
        clearable
      ></v-text-field>
      <v-autocomplete
        ref="pcategory"
        v-model="pcategory"
        prepend-icon="mdi-tag-multiple"
        label="Product Category"
        clearable
        dense
        :loading="gpLoading"
        :items="groups"
        :search-input.sync="searchGroup"
        :item-text="textGroup"
        :item-value="valueGroup"
        autocomplete
        :menu-props="{ bottom: true, offsetY: true }"
        hint="Please Search Catogry/Groups"
        chips
        attach
        v-on:change="handleGroupSearch()"
      >
        <template v-slot:selection="data">
          <v-chip
            v-bind="data.attrs"
            :input-value="data.selected"
            close
            @click="data.select"
            @click:close="removeGroup(data.item)"
          >
            {{ `${data.item.name} ` }}
          </v-chip>
        </template>

        <template v-slot:item="{ item }">
          <v-row align="center" justify="center">
            <v-col cols="12" sm="6" class="mx-1">
              <h6 v-html="`${item.name}`" />
            </v-col>
          </v-row>
        </template>
      </v-autocomplete>
      <v-autocomplete
        ref="pbrands"
        v-model="pbrands"
        prepend-icon="mdi-dots-hexagon"
        label="Product Brands"
        clearable
        dense
        multiple
        :loading="brLoading"
        :items="brandItems"
        :search-input.sync="searchBrand"
        :item-text="textBrand"
        :item-value="valueBrand"
        autocomplete
        :menu-props="{ bottom: true, offsetY: true }"
        hint="Please Search Brands"
        chips
        attach
        v-on:change="handleBrandSearch()"
      >
        <template v-slot:selection="data">
          <v-chip
            v-bind="data.attrs"
            :input-value="data.selected"
            close
            @click="data.select"
            @click:close="removeBrand(data.item)"
          >
            {{ `${data.item.name} ` }}
          </v-chip>
        </template>

        <template v-slot:item="{ item }">
          <v-row align="center" justify="center">
            <v-col cols="12" sm="6" class="mx-1">
              <h6 v-html="`${item.name}`" />
            </v-col>
          </v-row>
        </template>
      </v-autocomplete>
      <v-autocomplete
        ref="psizes"
        v-model="psizes"
        prepend-icon="mdi-dots-hexagon"
        label="Product Sizes"
        clearable
        dense
        multiple
        :loading="szLoading"
        :items="sizeItems"
        :search-input.sync="searchSize"
        :item-text="textSize"
        :item-value="valueSize"
        autocomplete
        :menu-props="{ bottom: true, offsetY: true }"
        hint="Please Search Size"
        chips
        attach
        v-on:change="handleSizeSearch()"
      >
        <template v-slot:selection="data">
          <v-chip
            v-bind="data.attrs"
            :input-value="data.selected"
            close
            @click="data.select"
            @click:close="removeSize(data.item)"
          >
            {{ `${data.item.name} ` }}
          </v-chip>
        </template>

        <template v-slot:item="{ item }">
          <v-row align="center" justify="center">
            <v-col cols="12" sm="6" class="mx-1">
              <h6 v-html="`${item.name}`" />
            </v-col>
          </v-row>
        </template>
      </v-autocomplete>
      <v-radio-group ref="radiosStockType" v-model="radiosStockType">
        <template v-slot:label>
          <div>
            <v-icon>mdi-store-outline</v-icon>
            <strong>Stock Type</strong>
          </div>
        </template>
        <v-radio value="stock">
          <template v-slot:label>
            <div>
              <strong class="success--text">Stock</strong>
            </div>
          </template>
        </v-radio>
        <v-radio value="non-stock">
          <template v-slot:label>
            <div>
              <strong class="primary--text">Non Stock</strong>
            </div>
          </template>
        </v-radio>
      </v-radio-group>
    </v-col>

    <v-col cols="12" md="6">
      <template>
        <v-menu
          ref="menu1"
          v-model="menu1"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="dateFormatted"
              label="Expiry Date"
              hint="MM/DD/YYYY format"
              persistent-hint
              readonly
              prepend-icon="mdi-calendar-text"
              v-bind="attrs"
              @blur="date = parseDate(dateFormatted)"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker
            v-model="date"
            no-title
            @input="menu1 = false"
          ></v-date-picker>
        </v-menu>
      </template>
      <v-divider></v-divider>
      <v-text-field
        ref="pquantity"
        v-model="pquantity"
        prepend-icon="mdi-checkbox-multiple-blank-circle"
        label="Quantity"
        clearable
      ></v-text-field>
      <v-text-field
        ref="pcost"
        v-model="pcostFormat"
        prepend-icon="mdi-cash-multiple"
        label="Cost Price"
        clearable
      ></v-text-field>
      <v-text-field
        ref="pwholesale"
        v-model="pwholesaleFormat"
        prepend-icon="mdi-cash-multiple"
        label="Whole Sale Price"
        clearable
      ></v-text-field>
      <v-text-field
        ref="pretailsale"
        v-model="pretailsaleFormat"
        prepend-icon="mdi-cash-multiple"
        label="Retail Sale Price"
        clearable
      ></v-text-field>
      <v-text-field
        ref="ptax"
        v-model="ptax"
        prepend-icon="mdi-percent-outline"
        label="Tax/VAT"
        clearable
      ></v-text-field>
      <v-textarea
        ref="pdescription"
        v-model="pdescription"
        prepend-icon="mdi-image-text"
        label="Item Description"
        cols="30"
        rows="4"
      ></v-textarea>
    </v-col>
    <div class="clear-fix"></div>
    <v-col cols="12" md="8">
      <h5>Product Picture</h5>
      <div>
        <div class="col-12 position-relative">
          <input
            class="form-control"
            accept="image/jpeg, image/png"
            type="file"
            ref="filesprofilePicture"
            id="filesprofilePicture"
            multiple
            v-on:change="handleFilesprofilePicture()"
          />
          <p>
            Add file here...
            <i class="fas fa-upload"></i>
          </p>
        </div>
        <div class="col-12">
          <div class="col-12">
            <div
              v-for="(file, key) in filesprofilePicture"
              :key="`f-${key}`"
              class="file-listing"
            >
              <img
                class="preview-profilePicture"
                v-bind:ref="'preview-profilePicture' + parseInt(key)"
              />
              {{
                `${file.name}`.substr(0, 10) +
                `${`${file.name}`.length > 10 ? "..." : ""}`
              }}
              <br />
              <span v-if="rulesStatusprofilePicture[key]" class="text-success">
                {{ rulesprofilePicture[key] }}
              </span>
              <span v-else class="text-danger">
                {{ rulesprofilePicture[key] }}
              </span>
              <div class="success-container" v-if="file.id > 0">
                Success
                <input
                  type="hidden"
                  :name="input_nameprofilePicture"
                  :value="file.id"
                />
              </div>
              <div class="remove-container" v-else>
                <a class="remove" v-on:click="removeFileprofilePicture(key)"
                  >Remove</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </v-col>
    <div class="clear-fix"></div>
    <v-col cols="12" md="12">
      <div class="row d-block justify-content-center">
        <v-btn class="btn-block" dark color="teal darker-3">Save</v-btn>
        <v-btn class="btn-block" dark color="red darker-3">Cancel</v-btn>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  name: "editorProduct",
  data: (vm) => {
    return {
      date: new Date().toISOString().substr(0, 10),
      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
      radiosStockType: "stock",
      menu1: false,
      //fields
      pbrandcode: null,
      pname: null,
      pcompany: null,
      psupplier: null,
      pcategory: null,
      pbrands: null,
      psizes: [],
      pquantity: null,
      pcost: null,
      pwholesale: null,
      pretailsale: null,
      ptax: null,
      pdescription: null,

      searchSize: null,
      szLoading: false,
      sizeSelection: [],
      sizeSelected: [],

      searchBrand: null,
      brLoading: false,
      brandSelection: [],
      brandSelected: [],

      searchGroup: null,
      gpLoading: false,
      groupSelection: null,
      groupSelected: null,

      filesprofilePicture: [],
      rulesprofilePicture: [],
      rulesStatusprofilePicture: [],
      fileTypeprofilePicture: [],
      groupI: "",
    };
  },
  computed: {
    ...mapState({
      sizes: (state) => state.sizesModule.sizes,
      product: (state) => state.productsModule.product,
      brands: (state) => state.brandsModule.brands,
      groups: (state) => state.groupsModule.groups,
    }),
    sizeItems() {
      let items = this.sizes;
      if (this.sizeSelection.length > 0) {
        items.unshift(...this.sizeSelection);
      }
      return items;
    },
    brandItems() {
      let items = this.brands;
      if (this.brandSelection.length > 0) {
        items.unshift(...this.brandSelection);
      }
      return items;
    },
    pcostFormat: {
      get: function () {
        if (this.pcost !== null) {
          return this.formatAsCurrency(this.pcost, 0);
        }
      },
      set: function (newValue) {
        const newValue1 = newValue || "UGX 0";
        this.pcost = Number(newValue1.replace(/[^0-9\.]/g, ""));
      },
    },

    pwholesaleFormat: {
      get: function () {
        if (this.pwholesale !== null) {
          return this.formatAsCurrency(this.pwholesale, 0);
        }
      },
      set: function (newValue) {
        const newValue1 = newValue || "UGX 0";
        this.pwholesale = Number(newValue1.replace(/[^0-9\.]/g, ""));
      },
    },
    pretailsaleFormat: {
      get: function () {
        if (this.pretailsale !== null) {
          return this.formatAsCurrency(this.pretailsale, 0);
        }
      },
      set: function (newValue) {
        const newValue1 = newValue || "UGX 0";
        this.pretailsale = Number(newValue1.replace(/[^0-9\.]/g, ""));
      },
    },
  },
  methods: {
    ...mapMutations({
      GET_SELECTED_PRODUCT: "productsModule/GET_SELECTED_PRODUCT",
    }),
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${month}/${day}/${year}`;
    },
    parseDate(date) {
      if (!date) return null;

      const [month, day, year] = date.split("/");
      return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
    },
    formatAsCurrency(value, dec) {
      dec = dec || 0;
      if (value === null) {
        return 0;
      }
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "UGX",
      }).format(value);
    },
    //start groups
    textGroup(item) {
      this.groupSelected = item;
      return item.name;
    },
    valueGroup(item) {
      return item;
    },
    handleGroupSearch() {
      this.groupSelection = this.groupSelected;
      //   this.psizes = this.sizeSelection;
      if (!this.pcategory) {
        this.pcategory = null;
        this.groupSelection = null;
      }
    },
    removeGroup(item) {
      this.pcategory = null;
      this.groupSelection = null;
    },
    async getGroups() {
      let data = {
        val: this.searchGroup,
      };
      this.gpLoading = true;
      this.$store
        .dispatch("groupsModule/GET_GROUPS_ACTION", data)
        .finally(() => {
          this.gpLoading = false;
        });
    },
    //end brands
    //start brands
    textBrand(item) {
      this.brandSelected = item;
      return item.name;
    },
    valueBrand(item) {
      return item;
    },
    handleBrandSearch() {
      this.brandSelection.push(this.brandSelected);
      //   this.psizes = this.sizeSelection;
      if (!this.psizes) {
        this.pbrands = [];
        this.brandSelection = [];
      }
    },
    removeBrand(item) {
      const { id } = item;
      const index = this.pbrands.findIndex((p) => p.id === id);
      this.pbrands.splice(index, 1);
    },
    async getBrands() {
      let data = {
        val: this.searchBrand,
      };
      this.brLoading = true;
      this.$store
        .dispatch("brandsModule/GET_BRANDS_ACTION", data)
        .finally(() => {
          this.brLoading = false;
        });
    },
    //end brands
    //start sizes
    textSize(item) {
      this.sizeSelected = item;
      return item.name;
    },
    valueSize(item) {
      return item;
    },
    handleSizeSearch() {
      this.sizeSelection.push(this.sizeSelected);
      //   this.psizes = this.sizeSelection;
      if (!this.psizes) {
        this.psizes = [];
        this.sizeSelection = [];
      }
    },
    removeSize(item) {
      const { id } = item;
      const index = this.psizes.findIndex((p) => p.id === id);
      this.psizes.splice(index, 1);
    },
    async getSize() {
      let data = {
        val: this.searchSize,
      };
      this.szLoading = true;
      this.$store.dispatch("sizesModule/GET_SIZES_ACTION", data).finally(() => {
        this.szLoading = false;
      });
    },
    //end sizes
    //class end

    convertBytesToSize(bytes) {
      var sizes = ["Bytes", "KB", "MB", "GB", "TB"];
      if (bytes === 0) return "0 Byte";
      var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
      return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
    },

    //start files profilePicture

    handleFilesprofilePicture() {
      let uploadedFiles = this.$refs.filesprofilePicture.files;
      this.filesprofilePicture = [];
      this.rulesprofilePicture = [];
      this.rulesStatusprofilePicture = [];

      for (var i = 0; i < uploadedFiles.length; i++) {
        this.filesprofilePicture.push(uploadedFiles[i]);
        if (uploadedFiles[i].size > 1024 * 1024 * 2) {
          this.$toast.error({
            title: "File Input Error",
            message:
              "file size is " +
              this.convertBytesToSize(uploadedFiles[i].size) +
              ", let size be atleast < 2MB!",
            color: "tomato",
            timeOut: 5000,
            showMethod: "lightSpeedIn",
            hideMethod: "slideOutUp",
          });
          this.rulesprofilePicture.push(
            "file size is " +
              this.convertBytesToSize(uploadedFiles[i].size) +
              ", let size be atleast < 2MB"
          );
          this.rulesStatusprofilePicture.push(false);
        } else if (uploadedFiles[i].size < 1024 * 1024 * 2) {
          this.rulesprofilePicture.push(
            "file size is " + this.convertBytesToSize(uploadedFiles[i].size)
          );
          this.rulesStatusprofilePicture.push(true);
        }
      }

      let data = {
        files: this.filesprofilePicture,
        rules: this.rulesprofilePicture,
        rulesStatus: this.rulesStatusprofilePicture,
      };
      // this.$store.dispatch('UPLOADED_FILE_ACTION',data);

      this.getImagePreviewsprofilePicture();
    },
    getImagePreviewsprofilePicture() {
      for (let i = 0; i < this.filesprofilePicture.length; i++) {
        if (/\.(jpe?g|png|gif)$/i.test(this.filesprofilePicture[i].name)) {
          // this.fileType.push(true);
          let reader = new FileReader();
          reader.addEventListener(
            "load",
            function () {
              this.$refs["preview-profilePicture" + parseInt(i)][0].src =
                reader.result;
            }.bind(this),
            false
          );
          reader.readAsDataURL(this.filesprofilePicture[i]);
        } else {
          this.$nextTick(function () {
            this.$refs["preview-profilePicture" + parseInt(i)][0].src =
              "/images/generic.png";
          });
        }
      }
    },

    removeFileprofilePicture(key) {
      this.filesprofilePicture.splice(key, 1);
      this.rulesprofilePicture.splice(key, 1);
      this.rulesStatusprofilePicture.splice(key, 1);
      this.getImagePreviewsprofilePicture();
    },
    //   end files profilePicture
  },
  beforeDestroy() {
    this.sizeSelection = [];
    this.sizeSelected = [];
    this.brandSelection = [];
    this.brandSelected = [];
    this.GET_SELECTED_PRODUCT({ product: null });
  },
  destroyed() {
    this.sizeSelection = [];
    this.sizeSelected = [];
    this.brandSelection = [];
    this.brandSelected = [];
    this.GET_SELECTED_PRODUCT({ product: null });
  },
  watch: {
    searchSize(val) {
      if (!this.searchSize) {
        this.searchSize = "";
      }
      if (this.searchSize !== "") {
        this.getSize();
      }
    },
    searchBrand(val) {
      if (!this.searchBrand) {
        this.searchBrand = "";
      }
      if (this.searchBrand !== "") {
        this.getBrands();
      }
    },

    searchGroup(val) {
      if (!this.searchGroup) {
        this.searchGroup = "";
      }
      if (this.searchGroup !== "") {
        this.getGroups();
      }
    },
  },
};
</script>

<style scoped>
input[type="file"] {
  opacity: 0;
  width: 100%;
  height: 200px;
  position: absolute;
  cursor: pointer;
}
.filezone {
  outline: 2px dashed #f2f4f5;
  outline-offset: -10px;
  background: #0277bd;
  color: #f2f4f5;
  padding: 10px 10px;
  min-height: 200px;
  position: relative;
  cursor: pointer;
}
.filezone:hover {
  background: #30a7ee;
  color: #f2f4f5;
  font-weight: 600;
  outline: 2px dashed #f2f4f5;
}

.filezone p {
  font-size: 1.2em;
  text-align: center;
  padding: 50px 50px 50px 50px;
}
div.file-listing img {
  max-width: 90%;
}

div.file-listing {
  margin: auto;
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

div.file-listing img {
  height: 100px;
}
div.success-container {
  text-align: center;
  color: green;
}

div.remove-container {
  text-align: center;
}

div.remove-container a {
  color: red;
  cursor: pointer;
}

a.submit-button {
  display: block;
  margin: auto;
  text-align: center;
  width: 200px;
  padding: 10px;
  text-transform: uppercase;
  background-color: #0277bd;
  color: white !important;
  font-weight: bold;
  margin-top: 20px;
}
a.submit-button:hover {
  display: block;
  margin: auto;
  text-align: center;
  width: 200px;
  padding: 10px;
  text-transform: uppercase;
  background-color: #0f4053;
  color: white;
  font-weight: bold;
  margin-top: 20px;
}
</style>
