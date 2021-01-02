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
                label="group Name"
                clearable
            ></v-text-field>
        </v-col>


        <v-col cols="12" md="12">
            <div class="row d-block justify-content-center">
                <v-btn class="btn-block" @click="savegroup()" dark color="teal darker-3">
                    <v-icon>mdi-content-save</v-icon> Save</v-btn
                >
                <v-btn class="btn-block"  @click="cancelgroup()" dark color="red darker-3"
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
    name: "editorGroup",
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
            openWindow: state => state.groupsModule.openWindow,
            group: state => state.groupsModule.group,
            action_done: state => state.groupsModule.action_done,
            message: state => state.groupsModule.message,

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
            GET_OPEN_WINDOW:"groupsModule/GET_OPEN_WINDOW",
            HANDLE_EDITOR_STATE:"groupsModule/HANDLE_EDITOR_STATE",
            GET_SELECTED_GROUP: "groupsModule/GET_SELECTED_GROUP",
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


        //saving group
        savegroup(){
            this.actionNow = 'save_group';
            this.dialogTitle = "Saving group!"
            this.dialogBody = "Are you sure about saving this group!"
            this.dialog = true;

        },
        cancelgroup(){
            this.actionNow = 'cancel_group';

            this.dialogTitle = "Cancel Saving group!"
            this.dialogBody = "Are you sure about cancelling, saving this group!"
            this.dialog = true;
        },
        //end saving group

        //confirm group
        agree(){
            this.dialog = false;
            if(this.actionNow === 'save_group') this.submitgroup();
            else if(this.actionNow === 'cancel_group') this.submitgroup();
        },
        disagree(){
            this.dialog = false;
            if(this.actionNow === 'save_group') this.submitgroup();
            else if(this.actionNow === 'cancel_group') this.submitgroup();
        },
        submitgroup(){
            let data = {};
            if(this.actionNow === 'save_group'){
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
                        title: "Saving group",
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
                this.$store.dispatch('groupsModule/SAVE_GROUP_ACTION',data).then(res=>{

                    const {errors,message} = res;

                    if(message !== undefined){
                        this.$toast.success({
                        title: "Saved group Well!",
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
            else if(this.actionNow === 'cancel_group'){

                this.$refs.form.reset();
                this.GET_OPEN_WINDOW({openWindow:1});
                this.HANDLE_EDITOR_STATE({action_done:'clear_update'});

            }

            this.actionNow = "";
            this.dialogTitle = "";
            this.dialogBody = "";
        }

        //end confirm group
    },
    beforeDestroy() {
        this.groupSelection = null;
        this.groupSelected = null;
        this.GET_SELECTED_GROUP({ action_done: null });
    },
    destroyed() {
        this.sizeSelection = [];
        this.sizeSelected = [];
        this.groupSelection = [];
        this.groupSelected = [];
        this.GET_SELECTED_GROUP({ action_done: null });
    },
    watch: {
        group(val){
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


        searchgroup(val) {
            if (!this.searchgroup) {
                this.searchgroup = "";
            }
            if (this.searchgroup !== "") {
                this.getgroups();
            }
        }
    }
};
</script>

<style scoped>

</style>
