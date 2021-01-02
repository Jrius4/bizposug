<template>


<div>
<v-form ref="form">
    <v-row>
        <v-col cols="12" md="6">

            <v-text-field
                ref="pname"
                v-model="pname"
                :rules="[() =>!!pname ||'Name is required']"
                prepend-icon="mdi-rename-box"
                label="Size Name"
                clearable
            ></v-text-field>
        </v-col>


        <v-col cols="12" md="12">
            <div class="row d-block justify-content-center">
                <v-btn class="btn-block" @click="savesize()" dark color="teal darker-3">
                    <v-icon>mdi-content-save</v-icon> Save</v-btn
                >
                <v-btn class="btn-block"  @click="cancelsize()" dark color="red darker-3"
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
    name: "editorSize",
    data: vm => {
        return {
            pid:null,
            menu1: false,
            //fields
            pname: null,

            activeNow:"",
            dialogTitle:"",
            dialogBody:"",
            dialog:false,

            formHasErrors:false,

        };
    },
    computed: {
        ...mapState({
            openWindow: state => state.sizesModule.openWindow,
            size: state => state.sizesModule.size,
            action_done: state => state.sizesModule.action_done,
            message: state => state.sizesModule.message,

        }),

        form() {
            let validate = {
                pname: this.pname || "",
            };

            return validate;
        }
    },
    methods: {
        ...mapMutations({
            GET_OPEN_WINDOW:"sizesModule/GET_OPEN_WINDOW",
            HANDLE_EDITOR_STATE:"sizesModule/HANDLE_EDITOR_STATE",
            GET_SELECTED_SIZE: "sizesModule/GET_SELECTED_SIZE",
        }),

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


        //saving size
        savesize(){
            this.actionNow = 'save_size';
            this.dialogTitle = "Saving Size!"
            this.dialogBody = "Are you sure about saving this size!"
            this.dialog = true;

        },
        cancelsize(){
            this.actionNow = 'cancel_size';

            this.dialogTitle = "Cancel Saving Size!"
            this.dialogBody = "Are you sure about cancelling, saving this size!"
            this.dialog = true;
        },
        //end saving size

        //confirm size
        agree(){
            this.dialog = false;
            if(this.actionNow === 'save_size') this.submitsize();
            else if(this.actionNow === 'cancel_size') this.submitsize();
        },
        disagree(){
            this.dialog = false;
            if(this.actionNow === 'save_size') this.submitsize();
            else if(this.actionNow === 'cancel_size') this.submitsize();
        },
        submitsize(){
            let data = {};
            if(this.actionNow === 'save_size'){
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
                        title: "Saving Sizes",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });

                    Object.assign(data,{
                    id:this.pid,
                    name:this.pname,
                });
                this.$store.dispatch('sizesModule/SAVE_SIZE_ACTION',data).then(res=>{

                    const {errors,message} = res;
                    console.log({errors,message})
                    if(message !== undefined){
                        this.$toast.success({
                        title: "Saved size Well!",
                        message: message,
                        color: "#388E3C",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                     this.$refs.form.reset();
                    //  if(this.action_done == 'update'){
                         this.GET_OPEN_WINDOW({openWindow:1});
                    //  }
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
            else if(this.actionNow === 'cancel_size'){

                this.$refs.form.reset();
                this.GET_OPEN_WINDOW({openWindow:1});
                this.HANDLE_EDITOR_STATE({action_done:'clear_update'});

            }

            this.actionNow = "";
            this.dialogTitle = "";
            this.dialogBody = "";
        }

        //end confirm size
    },
    beforeDestroy() {
        this.sizeSelection = null;
        this.sizeSelected = null;
        this.GET_SELECTED_SIZE({ action_done: null });
    },
    destroyed() {
        this.sizeSelection = [];
        this.sizeSelected = [];
        this.sizeSelection = [];
        this.sizeSelected = [];
        this.GET_SELECTED_SIZE({ action_done: null });
    },
    watch: {
        size(val){
            if(val!== null){
                        this.pid = val.id || null;
                        this.HANDLE_EDITOR_STATE({action_done:'update'});
                        this.pname = val.name || null;
            }
            else{
                this.pid = null;
                this.pname = null;
            }

        },


        searchsize(val) {
            if (!this.searchsize) {
                this.searchsize = "";
            }
            if (this.searchsize !== "") {
                this.getsizes();
            }
        }
    }
};
</script>

<style scoped>

</style>
