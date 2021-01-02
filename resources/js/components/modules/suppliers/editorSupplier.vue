<template>


                <div>

                    <v-form ref="form">
                     <v-row>
        <v-col cols="12" md="6">

            <v-text-field
                ref="pname"
                v-model="pname"
                :rules="[
                                            () =>
                                                !!pname ||
                                                'Name is required'
                                        ]"
                prepend-icon="mdi-rename-box"
                label="Supplier Name"
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
                ref="paddress"
                v-model="paddress"
                prepend-icon="mdi-rename-box"
                label="Address"
                clearable
            ></v-text-field>
            <v-text-field
                ref="pcontact"
                v-model="pcontact"
                prepend-icon="mdi-card-account-phone"
                label="Contact"
                clearable
            ></v-text-field>






        </v-col>

        <v-col cols="12" md="6">


            <v-text-field
                ref="pbalance"
                :rules="[
                        () =>
                            !!pbalance ||
                            'Balance is required'
                    ]"
                v-model="pbalanceFormat"
                prepend-icon="mdi-cash-multiple"
                label="Balance"
                clearable
            ></v-text-field>


            <v-textarea
                ref="pbiograpy"
                v-model="pbiograpy"
                prepend-icon="mdi-image-text"
                label="Supplier Biograpy"
                cols="30"
                rows="4"
            ></v-textarea>
        </v-col>
        <div class="clear-fix"></div>
        <!-- <v-col cols="12" md="8">
            <h5>supplier Picture</h5>
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
                <v-btn class="btn-block" @click="savesupplier()" dark color="teal darker-3">
                    <v-icon>mdi-content-save</v-icon> Save</v-btn
                >
                <v-btn class="btn-block"  @click="cancelsupplier()" dark color="red darker-3"
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
    name: "editorsupplier",
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
            pname: null,
            pcompany: null,
            pcontact: null,
            pbiograpy: null,
            paddress: null,
            pbalance: null,

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
            openWindow: state => state.suppliersModule.openWindow,
            supplier: state => state.suppliersModule.supplier,
            action_done: state => state.suppliersModule.action_done,
            message: state => state.suppliersModule.message,

        }),
        pbalanceFormat: {
            get: function() {
                if (this.pbalance !== null) {
                    return this.formatAsCurrency(this.pbalance, 0);
                }
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                this.pbalance = Number(newValue1.replace(/[^0-9\.]/g, ""));
            }
        },


        form() {
            let validate = {
                pcontact: this.pcontact || "",
                pname: this.pname || "",
            };

            return validate;
        }
    },
    methods: {
        ...mapMutations({
            GET_OPEN_WINDOW:"suppliersModule/GET_OPEN_WINDOW",
            HANDLE_EDITOR_STATE:"suppliersModule/HANDLE_EDITOR_STATE",
            GET_SELECTED_SUPPLIER: "suppliersModule/GET_SELECTED_SUPPLIER",
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

        //saving supplier
        savesupplier(){
            this.actionNow = 'save_SUPPLIER';
            this.dialogTitle = "Saving supplier!"
            this.dialogBody = "Are you sure about saving this supplier!"
            this.dialog = true;

        },
        cancelsupplier(){
            this.actionNow = 'cancel_SUPPLIER';

            this.dialogTitle = "Cancel Saving supplier!"
            this.dialogBody = "Are you sure about cancelling, saving this supplier!"
            this.dialog = true;
        },
        //end saving supplier

        //confirm supplier
        agree(){
            this.dialog = false;
            if(this.actionNow === 'save_SUPPLIER') this.submitsupplier();
            else if(this.actionNow === 'cancel_SUPPLIER') this.submitsupplier();
        },
        disagree(){
            this.dialog = false;
            if(this.actionNow === 'save_SUPPLIER') this.submitsupplier();
            else if(this.actionNow === 'cancel_SUPPLIER') this.submitsupplier();
        },
        submitsupplier(){
            let data = {};
            if(this.actionNow === 'save_SUPPLIER'){
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
                        title: "Saving suppliers",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });

                    Object.assign(data,{
                    id:this.pid,
                    name:this.pname,
                    contact:this.pcontact,
                    company:this.pcompany,
                    address:this.paddress,
                    balance:this.pbalance,
                    biograpy:this.pbiograpy,

                });
                this.$store.dispatch('suppliersModule/SAVE_SUPPLIER_ACTION',data).then(res=>{

                    const {errors,message} = res;

                    if(message !== undefined){
                        this.$toast.success({
                        title: "Saved supplier Well!",
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
            else if(this.actionNow === 'cancel_SUPPLIER'){

                this.$refs.form.reset();
                this.GET_OPEN_WINDOW({openWindow:1});
                this.HANDLE_EDITOR_STATE({action_done:'clear_update'});
                this.GET_SELECTED_DESCR_SIZE({sizes:[]});
                this.GET_SELECTED_DESCR_BRAND({brands:[]});
                this.GET_SELECTED_DESCR_GROUP({});
                this.GET_SELECTED_DESCR_SUPPLIER({})

            }

            this.actionNow = "";
            this.dialogTitle = "";
            this.dialogBody = "";
        }

        //end confirm supplier
    },
    beforeDestroy() {
        this.sizeSelection = null;
        this.sizeSelected = null;
        this.brandSelection = null;
        this.brandSelected = null;
        this.GET_SELECTED_SUPPLIER({ action_done: null });
    },
    destroyed() {
        this.sizeSelection = [];
        this.sizeSelected = [];
        this.brandSelection = [];
        this.brandSelected = [];
        this.GET_SELECTED_SUPPLIER({ action_done: null });
    },
    watch: {
        supplier(val){
            if(val!== null){
                        this.pid = val.id || null;
                        this.HANDLE_EDITOR_STATE({action_done:'update'});
                        this.pname = val.name || null;
                        this.pcontact = val.contact || null;
                        this.pcompany = val.company || null;
                        this.pbiograpy = val.biograpy || null;
                        this.paddress = val.address || null;
                        this.pbalance = val.balance || null;


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
