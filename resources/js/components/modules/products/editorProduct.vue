<template>


                <div>

                    <v-form ref="form">
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
                :rules="[
                                            () =>
                                                !!pname ||
                                                'Name is required'
                                        ]"
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
            <v-autocomplete
                ref="psupplier"

                v-model="psupplier"
                prepend-icon="mdi-tag-multiple"
                label="Product Supplier"
                clearable
                dense
                :loading="spLoading"
                :items="suppliers"
                :search-input.sync="searchSupplier"
                :item-text="textSupplier"
                :item-value="valueSupplier"
                autocomplete
                :menu-props="{ bottom: true, offsetY: true }"
                hint="Please Search Supplier"
                chips
                attach
                v-on:change="handleSuppierSearch()"
            >
                <template v-slot:selection="data">
                    <v-chip
                        v-bind="data.attrs"
                        :input-value="data.selected"
                        close
                        @click="data.select"
                        @click:close="removeSupplier(data.item)"
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
                ref="pcategory"
                :rules="[
                                            () =>
                                                !!pcategory ||
                                                'category is required'
                                        ]"
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
                :rules="[
                        () =>
                            !!pcost ||
                            'Cost is required'
                    ]"
                v-model="pcostFormat"
                prepend-icon="mdi-cash-multiple"
                label="Cost Price"
                clearable
            ></v-text-field>
            <v-text-field
                ref="pwholesale"
                :rules="[
                        () =>
                            !!pwholesale ||
                            'Whole Sale is required'
                    ]"
                v-model="pwholesaleFormat"
                prepend-icon="mdi-cash-multiple"
                label="Whole Sale Price"
                clearable
            ></v-text-field>
            <v-text-field
                ref="pretailsale"
                :rules="[
                        () =>
                            !!pretailsale ||
                            'Retail Sale is required'
                    ]"
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
        <!-- <v-col cols="12" md="8">
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
                                v-bind:ref="
                                    'preview-profilePicture' + parseInt(key)
                                "
                            />
                            {{
                                `${file.name}`.substr(0, 10) +
                                    `${`${file.name}`.length > 10 ? "..." : ""}`
                            }}
                            <br />
                            <span
                                v-if="rulesStatusprofilePicture[key]"
                                class="text-success"
                            >
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
                                <a
                                    class="remove"
                                    v-on:click="removeFileprofilePicture(key)"
                                    >Remove</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </v-col> -->
        <div class="clear-fix"></div>
        <v-col cols="12" md="12">
            <div class="row d-block justify-content-center">
                <v-btn class="btn-block" @click="saveProduct()" dark color="teal darker-3">
                    <v-icon>mdi-content-save</v-icon> Save</v-btn
                >
                <v-btn class="btn-block"  @click="cancelProduct()" dark color="red darker-3"
                    ><v-icon>mdi-cancel</v-icon> Cancel</v-btn
                >
            </div>
        </v-col>
    </v-row>




                     <template>
                    <v-row justify="center">
                        <v-dialog v-model="dialog" persistent max-width="320">
                            <v-card>
                                <v-card-subtitle class="headline">{{
                                    dialogTitle
                                }}</v-card-subtitle>
                                <v-card-text>{{ dialogBody }}</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="red darken-3"
                                        text
                                        @click="disagree"
                                        >Disagree</v-btn
                                    >
                                    <v-btn
                                        color="green darken-2"
                                        text
                                        @click="agree"
                                        >Agree</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                </template>
                    </v-form>
                </div>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
    name: "editorProduct",
    data: vm => {
        return {
            date: new Date().toISOString().substr(0, 10),
            dateFormatted: vm.formatDate(
                new Date().toISOString().substr(0, 10)
            ),
            pid:null,
            radiosStockType: "stock",
            menu1: false,
            //fields
            pbrandcode: null,
            pname: null,
            pcompany: null,
            psupplier: null,
            pcategory: null,
            pbrands: null,
            psizes: null,
            pquantity: null,
            pcost: null,
            pwholesale: null,
            pretailsale: null,
            ptax: null,
            pdescription: null,

            searchSize: null,
            szLoading: false,
            sizeSelection: null,
            sizeSelected: null,

            searchBrand: null,
            brLoading: false,
            brandSelection: null,
            brandSelected: null,

            searchGroup: null,
            gpLoading: false,
            groupSelection: null,
            groupSelected: null,

            searchSupplier: null,
            spLoading: false,
            supplierSelection: null,
            supplierSelected: null,

            filesprofilePicture: [],
            rulesprofilePicture: [],
            rulesStatusprofilePicture: [],
            fileTypeprofilePicture: [],
            groupI: "",

            activeNow:"",
            dialogTitle:"",
            dialogBody:"",
            dialog:false,

            formHasErrors:false,
        };
    },
    computed: {
        ...mapState({

            suppliers: (state) => state.suppliersModule.suppliers,
            sizes: state => state.sizesModule.sizes,
            openWindow: state => state.productsModule.openWindow,
            product: state => state.productsModule.product,
            action_done: state => state.productsModule.action_done,
            message: state => state.productsModule.message,
            brands: state => state.brandsModule.brands,
            groups: state => state.groupsModule.groups
        }),
        sizeItems() {
            let items = this.sizes;
            // if (this.sizeSelection.length > 0) {
            //     items.unshift(...this.sizeSelection);
            // }
            return items;
        },
        brandItems() {
            let items = this.brands;
            // if (this.brandSelection.length > 0) {
            //     items.unshift(...this.brandSelection);
            // }
            return items;
        },
        pcostFormat: {
            get: function() {
                if (this.pcost !== null) {
                    return this.formatAsCurrency(this.pcost, 0);
                }
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                this.pcost = Number(newValue1.replace(/[^0-9\.]/g, ""));
            }
        },

        pwholesaleFormat: {
            get: function() {
                if (this.pwholesale !== null) {
                    return this.formatAsCurrency(this.pwholesale, 0);
                }
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                this.pwholesale = Number(newValue1.replace(/[^0-9\.]/g, ""));
            }
        },
        pretailsaleFormat: {
            get: function() {
                if (this.pretailsale !== null) {
                    return this.formatAsCurrency(this.pretailsale, 0);
                }
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                this.pretailsale = Number(newValue1.replace(/[^0-9\.]/g, ""));
            }
        },
        form() {
            let validate = {

                pcategory: this.pcategory || "",
                pretailsale: this.pretailsale || "",
                pwholesale: this.pwholesale || "",
                pcost: this.pcost || "",
                pname: this.pname || "",
            };

            return validate;
        }
    },
    methods: {
        ...mapMutations({
            GET_OPEN_WINDOW:"productsModule/GET_OPEN_WINDOW",
            HANDLE_EDITOR_STATE:"productsModule/HANDLE_EDITOR_STATE",
            GET_SELECTED_PRODUCT: "productsModule/GET_SELECTED_PRODUCT",
            GET_SELECTED_DESCR_BRAND: "brandsModule/GET_SELECTED_DESCR_BRAND",
            GET_SELECTED_DESCR_SIZE: "sizesModule/GET_SELECTED_DESCR_SIZE",
            GET_SELECTED_DESCR_GROUP: "groupsModule/GET_SELECTED_DESCR_GROUP",
            GET_SELECTED_DESCR_SUPPLIER:"suppliersModule/GET_SELECTED_DESCR_SUPPLIER"
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
                currency: "UGX"
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
                val: this.searchGroup
            };
            this.gpLoading = true;
            this.$store
                .dispatch("groupsModule/GET_GROUPS_ACTION", data)
                .finally(() => {
                    this.gpLoading = false;
                });
        },
        //end brands
        //start suppiers
        textSupplier(item) {
            this.suppierSelected = item;
            return item.name;
        },
        valueSupplier(item) {
            return item;
        },
        handleSuppierSearch() {
            this.suppierSelection = this.suppierSelected;
            if (!this.psupplier) {
                this.psupplier = null;
                this.suppierSelection = null;
            }
        },
        removeSupplier(item) {
            this.psupplier = null;
            this.suppierSelection = null;
        },
        async getSuppliers() {
            let data = {
                val: this.searchSupplier
            };
            this.spLoading = true;
            this.$store
                .dispatch("suppliersModule/GET_SUPPLIERS_ACTION", data)
                .finally(() => {
                    this.spLoading = false;
                });
        },
        //end suppliers
        //start brands
        textBrand(item) {
            this.brandSelected = item;
            return item.name;
        },
        valueBrand(item) {
            return item;
        },
        handleBrandSearch() {

            this.brandSelection = this.brandSelected;
            //   this.psizes = this.sizeSelection;

            if (!this.pbrands) {
                this.pbrands = null;
                this.brandSelection = null;
            }
        },
        removeBrand(item) {
            this.pbrands = null;
            this.brandSelection = null;
        },
        async getBrands() {
            let data = {
                val: this.searchBrand
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



            this.sizeSelection = this.sizeSelected;
            //   this.psizes = this.sizeSelection;

            if (!this.psizes) {
                this.psizes = null;
                this.sizeSelection = null;
            }
        },
        removeSize(item) {
            // const { id } = item;
            // const index = this.psizes.findIndex(p => p.id === id);
            // this.psizes.splice(index, 1);
            this.psizes = null;
                this.sizeSelection = null;
        },
        async getSize() {
            let data = {
                val: this.searchSize
            };
            this.szLoading = true;
            this.$store
                .dispatch("sizesModule/GET_SIZES_ACTION", data)
                .finally(() => {
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
                        hideMethod: "slideOutUp"
                    });
                    this.rulesprofilePicture.push(
                        "file size is " +
                            this.convertBytesToSize(uploadedFiles[i].size) +
                            ", let size be atleast < 2MB"
                    );
                    this.rulesStatusprofilePicture.push(false);
                } else if (uploadedFiles[i].size < 1024 * 1024 * 2) {
                    this.rulesprofilePicture.push(
                        "file size is " +
                            this.convertBytesToSize(uploadedFiles[i].size)
                    );
                    this.rulesStatusprofilePicture.push(true);
                }
            }

            let data = {
                files: this.filesprofilePicture,
                rules: this.rulesprofilePicture,
                rulesStatus: this.rulesStatusprofilePicture
            };
            // this.$store.dispatch('UPLOADED_FILE_ACTION',data);

            this.getImagePreviewsprofilePicture();
        },
        getImagePreviewsprofilePicture() {
            for (let i = 0; i < this.filesprofilePicture.length; i++) {
                if (
                    /\.(jpe?g|png|gif)$/i.test(this.filesprofilePicture[i].name)
                ) {
                    // this.fileType.push(true);
                    let reader = new FileReader();
                    reader.addEventListener(
                        "load",
                        function() {
                            this.$refs[
                                "preview-profilePicture" + parseInt(i)
                            ][0].src = reader.result;
                        }.bind(this),
                        false
                    );
                    reader.readAsDataURL(this.filesprofilePicture[i]);
                } else {
                    this.$nextTick(function() {
                        this.$refs[
                            "preview-profilePicture" + parseInt(i)
                        ][0].src = "/images/generic.png";
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

        //saving product
        saveProduct(){
            this.actionNow = 'save_product';
            this.dialogTitle = "Saving Product!"
            this.dialogBody = "Are you sure about saving this product!"
            this.dialog = true;

        },
        cancelProduct(){
            this.actionNow = 'cancel_product';

            this.dialogTitle = "Cancel Saving Product!"
            this.dialogBody = "Are you sure about cancelling, saving this product!"
            this.dialog = true;
        },
        //end saving product

        //confirm product
        agree(){
            this.dialog = false;
            if(this.actionNow === 'save_product') this.submitProduct();
            else if(this.actionNow === 'cancel_product') this.submitProduct();
        },
        disagree(){
            this.dialog = false;
            if(this.actionNow === 'save_product') this.submitProduct();
            else if(this.actionNow === 'cancel_product') this.submitProduct();
        },
        submitProduct(){
            let data = {};
            if(this.actionNow === 'save_product'){
                this.formHasErrors = false;
                Object.keys(this.form).forEach(f => {
                    if (!this.form[f]) this.formHasErrors = true;

                    this.$refs[f].validate(true);
                });
                if (this.formHasErrors) {
                    this.$toast.error({
                        title: "Invalid Input",
                        message: "Check Through Required Fields!",
                        color: "#D32F2F",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                }else{

                    this.$toast.success({
                        title: "Saving Products",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });

                    Object.assign(data,{
                    id:this.pid,
                    name:this.pname,
                    barcode:this.pbrandcode,
                    brand:this.pbrands !== null ? this.pbrands.name:null,
                    size: this.psizes !== null ? this.psizes.name:null,
                    brand_id:this.pbrands !== null ? this.pbrands.id:null,
                    size_id:this.psizes !== null ? this.psizes.id:null,
                    stockType:this.radiosStockType,
                    prodgroup_id:this.pcategory !== null?this.pcategory.id:null,
                    category:this.pcategory !== null?this.pcategory.name:null,
                    supplier_id:this.psupplier !== null ? this.psupplier.id:null,
                    company_name:this.pcompany,
                    cost_price:this.pcost,
                    wholesale_price:this.pwholesale,
                    retailsale_price:this.pretailsale,
                    quantity:this.pquantity,
                    tax_percentage:this.ptax,
                    description:this.pdescription,
                    avatar:this.filesprofilePicture,
                });
                this.$store.dispatch('productsModule/SAVE_PRODUCT_ACTION',data).then(res=>{

                    const {errors,message} = res;

                    if(message !== undefined){
                        this.$toast.success({
                        title: "Saved Product Well!",
                        message: message,
                        color: "#388E3C",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                     this.$refs.form.reset();
                     if(this.action_done == 'update'){
                         this.GET_OPEN_WINDOW({openWindow:1});
                     }
                    }
                    else if(errors !== undefined){

                        Object.values(errors).forEach(ele =>{
                        ele.forEach(item=>{
                             this.$toast.error({
                            title: "Action Failed!",
                            message: item,
                            color: "#D32F2F",
                            timeOut: 5000,
                            showMethod: "lightSpeedIn",
                            hideMethod: "slideOutUp"
                        });

                        });

                    });

                    }


                }).catch(res=>console.log(res));



                }



            }
            else if(this.actionNow === 'cancel_product'){

                this.$refs.form.reset();
                this.GET_OPEN_WINDOW({openWindow:1});
                this.HANDLE_EDITOR_STATE({action_done:'clear_update'});
                this.GET_SELECTED_DESCR_SIZE({sizes:[]});
                this.GET_SELECTED_DESCR_BRAND({brands:[]});
                this.GET_SELECTED_DESCR_GROUP({});
                this.GET_SELECTED_DESCR_SUPPLIER({});

            }

            this.actionNow = "";
            this.dialogTitle = "";
            this.dialogBody = "";
        }

        //end confirm product
    },
    beforeDestroy() {
        this.sizeSelection = null;
        this.sizeSelected = null;
        this.brandSelection = null;
        this.brandSelected = null;
        this.GET_SELECTED_PRODUCT({ action_done: null });
    },
    destroyed() {
        this.sizeSelection = [];
        this.sizeSelected = [];
        this.brandSelection = [];
        this.brandSelected = [];
        this.GET_SELECTED_PRODUCT({ action_done: null });
    },
    watch: {
        product(val){
        if(val!== null){
                    console.log({val});
                    this.pid = val.id || null;
                    this.HANDLE_EDITOR_STATE({action_done:'update'});
                    this.pname = val.name || null;
                    this.pbrandcode = val.barcode || null;
                    this.pcompany = val.company_name || null;
                    this.pcost = val.cost_price || null;
                    this.pwholesale = val.wholesale_price || null;
                    this.pretailsale = val.retailsale_price || null;
                    this.pdescription = val.description || null;
                    this.pquantity = val.quantity || null;
                    this.ptax = val.tax_percentage || null;
                    this.radiosStockType = val.stock_type || null;
                    this.brandSelection = val.brands.length > 0? val.brands[0] : null;
                    this.brandSelected = val.brands.length > 0? val.brands[0] : null;
                    this.pbrands = val.brands.length > 0? val.brands[0] : null;
                    this.sizeSelection = val.sizes.length > 0? val.sizes[0] : null;
                    this.sizeSelected = val.sizes.length > 0? val.sizes[0] : null;
                    this.psizes = val.sizes.length > 0? val.sizes[0] : null;
                    this.groupSelected = typeof val.prodgroup !== 'undefined' ?val.prodgroup:null;
                    this.groupSelection = typeof val.prodgroup !== 'undefined' ?val.prodgroup:null;
                    this.pcategory = typeof val.prodgroup !== 'undefined' ?val.prodgroup:null;
                    this.suppierSelected = typeof val.supplier !== 'undefined' ?val.supplier:null;
                    this.suppierSelection = typeof val.supplier !== 'undefined' ?val.supplier:null;
                    this.psupplier = typeof val.supplier !== 'undefined' ?val.supplier:null;
                    this.GET_SELECTED_DESCR_SIZE({sizes:val.sizes});
                    this.GET_SELECTED_DESCR_BRAND({brands:val.brands});
                    this.GET_SELECTED_DESCR_GROUP({groups:val.prodgroup});
                    this.GET_SELECTED_DESCR_SUPPLIER({supplier:val.supplier});

        }else{
            this.$refs.form.reset();
            this.GET_OPEN_WINDOW({openWindow:1});
            this.GET_SELECTED_PRODUCT({product:null,message:null});
            this.HANDLE_EDITOR_STATE({action_done:'clear'});
            this.GET_SELECTED_DESCR_SIZE({sizes:[]});
            this.GET_SELECTED_DESCR_BRAND({brands:[]});
            this.GET_SELECTED_DESCR_GROUP({});
            this.GET_SELECTED_DESCR_SUPPLIER({});
        }

        },
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

        searchSupplier(val) {
            if (!this.searchSupplier) {
                this.searchSupplier = "";
            }
            if (this.searchSupplier !== "") {
                this.getSuppliers();
            }
        }
    }
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
