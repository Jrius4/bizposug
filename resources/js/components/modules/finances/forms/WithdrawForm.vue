<template>
    <div>
        <v-form ref="form">
            <v-row align="baseline" justify="center">
                <v-col cols="12" md="12">
                    <v-card dark color="rgb(38,166,154)">
                        <v-card-title>
                            <h3 class="text-center">Withdraw Form</h3>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card dark  color="grey darken-3" elevation="2">
                        <v-card-text>
                            <h4 class="text-center">Details</h4>
                            <table class="table table-sm table-dark" v-if="account !== null">
                                <tbody>
                                    <tr>
                                        <th>
                                            Account
                                        </th>
                                        <td>
                                            {{account.name}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Balance
                                        </th>
                                        <td>
                                            {{account.balance | currency}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Description
                                        </th>
                                        <td>
                                            {{account.description}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="6">
                    <v-card elevation="2">
                        <v-card-text>
                            <v-text-field
                                ref="pamount"
                                v-model="pamountFormat"
                                prepend-icon="mdi-cash-multiple"
                                label="Withdraw Amount"
                                clearable
                                :rules="[
                                            () =>
                                                !!pamount ||
                                                'Amount is required'
                                        ]"
                            ></v-text-field>

                            <v-textarea
                                ref="pdetails"
                                v-model="pdetails"
                                prepend-icon="mdi-image-text"
                                label="Details"
                                cols="30"
                                rows="4"
                            ></v-textarea>

                            <div class="row d-block justify-content-center">
                                <v-btn class="btn-block" @click="saveAmount()" dark color="teal darker-3">
                                    <v-icon>mdi-content-save</v-icon> Save</v-btn
                                >
                                <v-btn class="btn-block"  @click="cancelAmount()" dark color="red darker-3"
                                    ><v-icon>mdi-cancel</v-icon> Cancel</v-btn
                                >
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>

            </v-row>
        </v-form>

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
    </div>
</template>

<script>
import { mapState,mapActions, mapMutations } from 'vuex'
    export default {
        name:'WithdrawForm',
        data:(vm)=>{
            return {
                pdetails:null,
                pamount:null,
                actionNow:"",
                dialogTitle:"",
                dialogBody:"",
                dialog:false,
                formHasErrors:false,
            }
        },
        methods:{
            ...mapActions({
                GET_ACCOUNT_DETAILS_ACTION : 'accountsModule/GET_ACCOUNT_DETAILS_ACTION',
                SAVE_AMOUNT_ACTION : 'accountsModule/SAVE_AMOUNT_ACTION',
            }),
            ...mapMutations({
                GET_ACCOUNT_DETAILS : 'accountsModule/GET_ACCOUNT_DETAILS'
            }),
            saveAmount(){
                this.actionNow = 'save_amount';
                this.dialogTitle = "Saving amount!"
                this.dialogBody = "Are you sure about depositing this amount!"
                this.dialog = true;
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
            submitAmount(){
                let data = {};
                if(this.actionNow === 'save_amount'){
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
                            title: "Saving amount",
                            message: "All fields are good",
                            color: "#00ACC1",
                            timeOut: 5000,
                            showMethod: "lightSpeedIn",
                            hideMethod: "slideOutUp"
                        });
                        const {balance} = this.account;
                        if(balance > this.pamount){
                            Object.assign(data,{
                                balance : this.pamount,
                                type : 'withdraw',
                                details:this.pdetails
                            });



                                this.SAVE_AMOUNT_ACTION(data).then((result) => {
                                    const {errors,message} = result;

                                        if(message !== undefined){
                                            this.$toast.success({
                                            title: "Deposited Well!",
                                            message: message,
                                            color: "#388E3C",
                                            timeOut: 5000,
                                            showMethod: "lightSpeedIn",
                                            hideMethod: "slideOutUp"
                                        });
                                        this.pdetails = "";
                                        this.pamount = "";
                                        this.$refs.form.reset();

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
                                }).catch((err) => {
                                    console.log(err);
                                });
                        }
                        else if(balance < this.pamount){
                            this.$toast.error({
                                title: "Low Account Balance",
                                message: "Please there is less balance to withdraw from!",
                                color: "#D32F2F",
                                timeOut: 5000,
                                showMethod: "lightSpeedIn",
                                hideMethod: "slideOutUp"
                            });
                        }


                    }
                }
                else if(this.actionNow === 'cancel_action')
                {
                    this.$refs.form.reset();

                    this.$refs['pamount'].validate(false);
                }
            },
            cancelAmount(){
                this.actionNow = 'cancel_action';

                this.dialogTitle = "Cancel depositing action!"
                this.dialogBody = "Are you sure about cancelling, depositing this amount!"
                this.dialog = true;
            },
            agree(){
                this.dialog = false;
                if(this.actionNow === 'save_amount') this.submitAmount();
                else if(this.actionNow === 'cancel_action') this.submitAmount();
            },
            disagree(){
                this.dialog = false;
                this.actionNow ='cancel_action';
                if(this.actionNow === 'cancel_action') this.submitAmount();
            },

        },
        computed:{
            ...mapState({
                message:state=>state.accountsModule.message,
                account:state=>state.accountsModule.account,
            }),
            form(){
                    let validate = {
                    pamount: this.pamount || "",
                };

                return validate;
            },
            pamountFormat: {
                get: function() {
                    if (this.pamount !== null) {
                        return this.formatAsCurrency(this.pamount,0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.pamount = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },

        },
        mounted(){
            this.GET_ACCOUNT_DETAILS_ACTION();
        },
        destroyed(){
            this.GET_ACCOUNT_DETAILS({});
        },

        filters: {
            currency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX",
            }).format(value);
            },
        },

    }
</script>

<style lang="scss" scoped>

</style>

