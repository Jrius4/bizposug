<template>
    <div>
        <v-card>
            <v-card-text>
                <v-form ref="formMain">
                    <v-row align="baseline" justify="center">
                        <v-col cols="12" md="5">
                            <v-select
                                ref="type_service"
                                v-model="type_service"
                                :items="['Field Service', 'Workshop Service', 'Others']"
                                :menu-props="{ bottom: true, offsetY: true }"
                                label="service Type"
                            ></v-select>
                        </v-col>

                        <v-col>
                            <v-text-field
                                ref="serviceName"
                                v-model="serviceName"
                                label="Service To Do"
                                clearable
                                :rules="[
                                    () =>
                                        !!serviceName ||
                                        'Service To Do Field is required'
                                ]"
                            >
                            </v-text-field>
                        </v-col>

                        <v-col
                        cols="12"
                            md="12"
                             class="borderline"
                        >

                        <v-container>
                            <v-row  align="baseline" justify="center">
                                <v-col
                                cols="12"
                                md="4"
                                >
                                    <v-radio-group ref="radiosStockType" v-model="radiosStockType">
                                        <template v-slot:label>
                                            <div>
                                                <v-icon>mdi-account-search</v-icon>
                                                <strong>Choice to Search For Client</strong>
                                            </div>
                                        </template>
                                        <v-radio value="Yes">
                                            <template v-slot:label>
                                                <div>
                                                    <strong class="success--text">Yes</strong>
                                                </div>
                                            </template>
                                        </v-radio>
                                        <v-radio value="No">
                                            <template v-slot:label>
                                                <div>
                                                    <strong class="red--text">No</strong>
                                                </div>
                                            </template>
                                        </v-radio>
                                    </v-radio-group>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="8"
                                     class="cyan lighten-4"
                                    v-if="radiosStockType === 'No'"
                                >
                                <v-text-field label="Client Name" ref="cname" v-model="cname" :rules="[() => !!cname || 'name is required']"></v-text-field>
                                <v-text-field label="Client Contact" ref="ccontact" v-model="ccontact" :rules="[() => !!ccontact || 'contact is required']"></v-text-field>
                                <v-text-field label="Client Address" ref="caddress" v-model="caddress" ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="8"
                                    class="teal lighten-4"
                                    v-else-if="radiosStockType === 'Yes'"
                                >
                                    <v-autocomplete
                                ref="ccustomer"

                                v-model="ccustomer"
                                prepend-icon="mdi-tag-multiple"
                                label="Search Customer"
                                clearable
                                dense
                                :loading="spLoading"
                                :items="customers"
                                :rules="[() => !!ccustomer || 'customer is required']"
                                :search-input.sync="searchcustomer"
                                :item-text="textcustomer"
                                :item-value="valuecustomer"
                                autocomplete
                                :menu-props="{ bottom: true, offsetY: true }"
                                hint="Please Search customer"
                                chips
                                attach
                                v-on:change="handlecustomerSearch()"
                            >
                                <template v-slot:selection="data">
                                    <v-chip
                                        v-bind="data.attrs"
                                        :input-value="data.selected"
                                        close
                                        @click="data.select"
                                        @click:close="removecustomer(data.item)"
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
                            <div class="row justify-content-center">
                                <h4 class="display-1 col-12">Details</h4>
                                <div class="col-12" v-if="ccustomer !== null">
                                    <table class="table table-sm table-striped">
                                        <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ccustomer.name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Contact</td>
                                                <td>{{ccustomer.contact}}</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>{{ccustomer.address}}</td>
                                            </tr>
                                            <tr>
                                                <td>Gender</td>
                                                <td>{{ccustomer.gender}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                </v-col>
                            </v-row>
                        </v-container>

                        </v-col>

                        <v-col cols="12" md="8">
                            <v-window v-model="itemsStep">
                                <v-window-item :value="1">
                                    <h4>Tools Cost</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Item</th>
                                                <th>QTY</th>
                                                <th>UNITS</th>
                                                <th>RATE</th>
                                                <th>COST_RATE</th>
                                                <th>AMOUNT</th>
                                                <th>
                                                    <v-btn
                                                        small
                                                        @click="addItem()"
                                                        dark
                                                        elevation="3"
                                                        color="#00ACC1"
                                                    >
                                                        ADD ITEM
                                                    </v-btn>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(it, i) in pitems"
                                                :key="`${i}-it`"
                                            >
                                                <td>{{ ++i }}</td>
                                                <td>{{ it.name }}</td>
                                                <td>{{ it.qty }}</td>
                                                <td>{{ it.unit }}</td>
                                                <td>
                                                    {{ it.rate | currency }}
                                                </td>
                                                <td>
                                                    {{ it.costrate | currency }}
                                                </td>
                                                <td>
                                                    {{ it.amount | currency }}
                                                </td>
                                                <td>
                                                    <div class="row d-flex">
                                                        <v-btn
                                                            @click="editIT(it)"
                                                            icon
                                                            dark
                                                            small
                                                            color="#2196F3"
                                                        >
                                                            <v-icon
                                                                >mdi-clipboard-edit-outline</v-icon
                                                            >
                                                        </v-btn>
                                                        <v-btn
                                                            @click="
                                                                deleteIT(it)
                                                            "
                                                            icon
                                                            dark
                                                            small
                                                            color="#D32F2F"
                                                        >
                                                            <v-icon
                                                                >mdi-delete-circle-outline</v-icon
                                                            >
                                                        </v-btn>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody>

                                            <tr>
                                                <td>Total</td>
                                                <td colspan="5">
                                                    {{ ptotal | currency }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Cost</td>
                                                <td colspan="5">
                                                    {{ pcosttotal | currency }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Profits</td>
                                                <td colspan="5">
                                                    {{ (ptotal - pcosttotal)| currency }}
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </v-window-item>
                                <v-window-item :value="2">
                                    <table class="table">
                                        <tbody>
                                            <th colspan="3">
                                                <v-btn
                                                    small
                                                    @click="tableView()"
                                                    dark
                                                    elevation="3"
                                                    color="#455A64"
                                                >
                                                    <v-icon
                                                        >mdi-table-arrow-left</v-icon
                                                    >
                                                    BACK TO TABLE
                                                </v-btn>
                                            </th>
                                        </tbody>
                                    </table>
                                    <v-form ref="form">
                                        <v-text-field
                                            ref="pname"
                                            v-model="pname"
                                            label="Name"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!pname ||
                                                    'Name is required'
                                            ]"
                                        >
                                        </v-text-field>
                                        <v-text-field
                                            ref="pqty"
                                            v-model="pqtyFormat"
                                            label="Quantity"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!pqty ||
                                                    'Quantity is required'
                                            ]"
                                        >
                                        </v-text-field>
                                        <v-text-field
                                            ref="pcostrate"
                                            v-model="pcostrateFormat"
                                            label="Cost Rate"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!pcostrate ||
                                                    'Rate is required'
                                            ]"
                                        >
                                        </v-text-field>
                                        <v-text-field
                                            ref="prate"
                                            v-model="prateFormat"
                                            label="Sale Rate"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!prate ||
                                                    'Rate is required'
                                            ]"
                                        >
                                        </v-text-field>

                                        <v-text-field
                                            ref="piunit"
                                            v-model="piunit"
                                            label="Item Units"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!piunit ||
                                                    'unit is required'
                                            ]"
                                        >
                                        </v-text-field>
                                        <v-textarea
                                            ref="pdescription"
                                            v-model="pdescription"
                                            label="Description"
                                            clearable
                                            :rules="[
                                                () =>
                                                    !!pdescription ||
                                                    'Description is required'
                                            ]"
                                            cols="30"
                                            rows="4"
                                        >
                                        </v-textarea>

                                        <div
                                            class="row d-block justify-content-center"
                                        >
                                            <v-btn
                                                class="btn-block"
                                                @click="saveitem()"
                                                small
                                                dark
                                                color="teal darker-3"
                                            >
                                                <v-icon
                                                    >mdi-content-save</v-icon
                                                >
                                                Save Item</v-btn
                                            >
                                            <v-btn
                                                class="btn-block"
                                                @click="cancelitem()"
                                                small
                                                dark
                                                color="red darker-3"
                                                ><v-icon>mdi-cancel</v-icon>
                                                Cancel Item</v-btn
                                            >
                                        </div>
                                    </v-form>
                                </v-window-item>
                            </v-window>
                        </v-col>
                        <v-col cols="12" md="8">
                            <v-text-field
                                ref="amountAgreed"
                                v-model="amountAgreedFormat"
                                label="Charge Agreed"
                                clearable
                                :rules="[() => !!amountAgreed || 'charge agreed is required']"
                            >
                            </v-text-field>
                            <v-text-field
                                ref="prevBalance"
                                v-model="prevBalanceFormat"
                                label="Previous Balance"
                                clearable
                            >
                            </v-text-field>
                            <v-text-field
                                ref="amountPaid"
                                v-model="amountPaidFormat"
                                label="Amount Paid"
                                clearable
                                :rules="[() => !!amountPaid || 'Amount Paid is required']"
                            >
                            </v-text-field>


                            <v-text-field
                                id="pbalance"
                                ref="pbalance"
                                disabled
                                v-model="pbalanceFormat"
                                label="Balance"
                                clearable
                                :rules="[
                                    () => !!pbalance || 'balance is required'
                                ]"
                            >
                            </v-text-field>
                            <v-card>
                                <v-card-subtitle>
                                    <h5 class="text-center">Summary</h5>
                                </v-card-subtitle>
                                <v-card-text>
                                    <table class="table table-sm">

                                        <tbody v-if="ccustomer !== null">
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <td>{{ ccustomer.name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Company</th>
                                                <td>
                                                    {{
                                                        ccustomer.company ||
                                                            "None"
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Contact</th>
                                                <td>{{ ccustomer.contact }}</td>
                                            </tr>


                                        </tbody>
                                        <tbody v-if="ccustomerVal !== null" >
                                            <tr>
                                                <td>Name</td>
                                                <td>{{cname}}</td>
                                            </tr>
                                            <tr>
                                                <td>Contact</td>
                                                <td>{{ccontact}}</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>{{caddress}}</td>
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <th>Prev. Balance</th>
                                                <td>
                                                    {{
                                                        prevBalance
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Active Balance</th>
                                                <td>{{pbalance
                                                            | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Currently Agreed Payment</th>
                                                <td>{{amountAgreed
                                                            | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Currently Paid</th>
                                                <td>{{amountPaid
                                                            | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </v-card-text>
                            </v-card>

                            <v-text-field
                                ref="precieved_by"
                                v-model="precieved_by"
                                label="Recieved By"
                                clearable
                            >
                            </v-text-field>

                            <v-textarea
                                ref="padescription"
                                v-model="padescription"
                                label="Description"
                                clearable
                                :rules="[
                                    () =>
                                        !!padescription ||
                                        'Description is required'
                                ]"
                                cols="30"
                                rows="4"
                            ></v-textarea>
                            <div class="row d-block justify-content-center">
                                <v-btn
                                    class="btn-block"
                                    @click="saveAction()"
                                    dark
                                    color="teal darker-3"
                                >
                                    <v-icon>mdi-content-save</v-icon> Save
                                    service</v-btn
                                >
                                <v-btn
                                    class="btn-block"
                                    @click="cancelAction()"
                                    dark
                                    color="red darker-3"
                                    ><v-icon>mdi-cancel</v-icon> Cancel
                                    service</v-btn
                                >
                            </div>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>

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
                            <v-btn color="red darken-3" text @click="disagree"
                                >Disagree</v-btn
                            >
                            <v-btn color="green darken-2" text @click="agree"
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
import { mapMutations, mapState } from 'vuex'
    export default {
        name:'AlphaEditor',
        data:()=>{
            return {
                radiosStockType: "Yes",
                piunit:null,
                cname:null,
                ccontact:null,
                caddress:null,
                selectedItem:null,
                precieved_by:null,
                itemsStep:1,
                wkLoading:false,
                spLoading:false,
                pid:null,
                searchWorker:null,
                type_service:'Field Service',

                amountAgreed:null,
                amountPaid:null,
                prevBalance:null,
                pbalance:null,
                pdescription:null,
                padescription:null,
                searchcustomer:null,
                serviceName:null,

                pworker:null,
                ccustomer:null,
                pLastBalance:null,
                workerSelection:null,
                workerSelected:null,
                customerSelection:null,
                customerSelected:null,
                pname:null,
                pqty:0,
                prate:null,
                pcostrate:null,
                pitems:[],
                pamount:null,
                formHasErrors:false,

                actionNow: "",
                dialog: false,
                dialogTitle: "",
                dialogBody: "",
                agree_status: false,
            }
        },
        computed:{
            ...mapState({
                customers: (state) => state.customersModule.customers,
                workers: (state) => state.workersModule.workers,
            }),
            ptotal(){
                let total = 0;

               this.pitems.forEach(item=>{
                    total += item.amount
                });
                this.pamount = total;
                return total;
            },
            pcosttotal(){
                let total = 0;

               this.pitems.forEach(item=>{
                    total += item.costamount
                });
                this.pcostamount = total;
                return total;
            },

            prateFormat: {
                get: function() {
                    if (this.prate !== null) {
                        return this.formatAsCurrency(this.prate, 0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || "UGX 0";
                    this.prate = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            pcostrateFormat: {
                get: function() {
                    if (this.pcostrate !== null) {
                        return this.formatAsCurrency(this.pcostrate, 0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || "UGX 0";
                    this.pcostrate = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            pLastBalanceFormat: {
                get: function() {

                    if (this.pLastBalance !== null) {
                        return this.formatAsCurrency(this.pLastBalance, 0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || "UGX 0";
                    this.pLastBalance = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            pqtyFormat: {
                get: function() {
                    if (this.pqty !== null) {
                        return this.pqty;
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.pqty = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            amountAgreedFormat: {
                get: function() {
                    if (this.amountAgreed !== null) {
                        return this.formatAsCurrency(this.amountAgreed,0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.amountAgreed = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            amountPaidFormat: {
                get: function() {
                    if (this.amountPaid !== null) {
                        return this.formatAsCurrency(this.amountPaid,0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.amountPaid = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            prevBalanceFormat: {
                get: function() {
                    if (this.prevBalance !== null) {
                        return this.formatAsCurrency(this.prevBalance,0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.prevBalance = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            ccustomerVal(){
                let client = {}

                    Object.assign(client,{name:this.cname});
                    Object.assign(client,{contact:this.ccontact});
                    Object.assign(client,{address:this.caddress});
                if(client.name === null || client.contact === null ){
                   if(this.ccustomer === null) this.$toast.error({
                        title: "Customer Details",
                        message: "Name and Contact required",
                        color: "#D32F2F",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                    client = null;
                }
                return client
            },
            pbalanceFormat: {
                get: function() {
                     this.pbalance = 0;
                    if(this.prevBalance !== null){
                        this.pbalance -=  (parseInt(this.prevBalance) || 0);
                    }
                    if(this.amountPaid !== null){
                        this.pbalance -=  (parseInt(this.amountAgreed) || 0) - (parseInt(this.amountPaid) || 0);
                    }

                    if (this.pbalance !== null) {
                        return this.formatAsCurrency(this.pbalance, 0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.pbalance = Number(newValue1.replace(/[^0-9\.]/g, ""));

                }
            },
            form(){
                return {
                    piunit:this.piunit||"",
                    pname:this.pname || "",
                    pqty:this.pqty || "",
                    prate:this.prate || "",
                    pcostrate:this.pcostrate || "",
                }
            },
            formMain(){
                let form = {
                    type_service:this.type_service || "",
                    precieved_by:this.precieved_by || "",
                    amountAgreed:this.amountAgreed || "",
                    amountPaid:this.amountPaid || "",
                    serviceName:this.serviceName || "",
                }

                if(this.radiosStockType === 'No'){
                    Object.assign(form,{ccontact:this.ccontact || ""});
                    Object.assign(form,{cname:this.cname || ""});
                }
                else if(this.radiosStockType === 'Yes'){
                    Object.assign(form,{ccustomer:this.ccustomer || ""});
                }



                return form;
            }
        },
        watch:{
            type_service(val){
                if(val){
                    this.removecustomer();
                }

            },

            radiosStockType(val){
                if(val){
                    this.removecustomer();
                }

            },
            pbalance(val){
                if(val > 0){
                    this.$toast.error({
                        title: "Check Out!",
                        message: "The balance is in excess!",
                        color: "#D32F2F",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                }
            },
            ccustomer(val){
                if(val === null) this.pbalance = null;
            },
            pworker(val){
                if(val === null) this.pbalance = null;
            },
            searchcustomer(val) {
                if (!this.searchcustomer) {
                    this.searchcustomer = "";
                }
                if (this.searchcustomer !== "") {
                    this.getcustomers();
                }
            },
            searchWorker(val) {
                if (!this.searchWorker) {
                    this.searchWorker = "";
                }
                if (this.searchWorker !== "") {
                    this.getworkers();
                }
            },
            type_service(val){
                this.pworker = null;
                this.ccustomer = null;
                this.customerSelection = null;
                this.customerSelected = null;
                this.workerSelection = null;
                this.workerSelected = null;
                this.ppay = null;
                this.pbalance = null;
                // this.$refs.formMain.reset();


            }
        },
        filters: {
    currency(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "UGX",
      }).format(value);
    },
  },
        methods:{
            ...mapMutations({
                GET_OPEN_WINDOW:'servicesModule/GET_OPEN_WINDOW'
            }),
            disagree() {
                this.agree_status = false;
                this.dialog = false;
                if (this.actionNow === "save_transaction") this.submitservice();
            },
            agree() {
                this.agree_status = true;
                this.dialog = false;
                if (this.actionNow === "save_transaction") this.submitservice();
            },
            submitservice() {
                if (this.agree_status) {
                    this.formHasErrors = false;

                    console.log({vals:this.formMain});
                Object.keys(this.formMain).forEach(f => {
                    if (!this.formMain[f]) this.formHasErrors = true;
                    console.log({val:f});
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
                }
                else{
                    this.$toast.success({
                        title: "Saving service",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                    let client_details = null;
                    if(this.ccustomer === null){
                        client_details = JSON.stringify({
                            name:this.cname,
                            contact:this.ccontact,
                            address:this.caddress,
                        })
                    }else if(this.ccustomer !== null){
                        client_details = JSON.stringify({
                            name:this.ccustomer.name,
                            contact:this.ccustomer.contact,
                            address:this.ccustomer.address,
                        })
                    }
                    const item_amount = this.ptotal;
                    const cost = this.pcosttotal;
                    const profit = (this.ptotal-this.pcosttotal);

                    let data = {
                        items: this.pitems.length > 0? this.pitems:null,
                        customer_id:this.ccustomer !== null?this.ccustomer.id:null,
                        type_service:this.type_service,
                        description:this.padescription,
                        amountPaid:this.amountPaid,
                        serviceName:this.serviceName,
                        amountAgreed:this.amountAgreed,
                        received_by:this.precieved_by,
                        balance:this.pbalance,
                        client_details:client_details,
                        prevBalance:this.prevBalance,
                        cost:cost,
                        profit:profit,
                        item_amount:item_amount,
                    };
                    this.$store
                        .dispatch("servicesModule/SAVE_SERVICE_ACTION", data)
                        .then(res=>{
                            const {errors,message} = res;
                            console.log({errors,message})

                            if(message !== undefined){
                                this.$toast.success({
                                title: "Saved worker Well!",
                                message: message,
                                color: "#388E3C",
                                timeOut: 5000,
                                showMethod: "lightSpeedIn",
                                hideMethod: "slideOutUp"
                            });
                            this.$refs.formMain.reset();
                            this.GET_OPEN_WINDOW({openWindow:1})
                            this.pitems = [];
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
                        });
                }

                } else {
                    this.pitems = [];
                    // this.inCartItemsTotals = 0;

                    this.pamount = 0;
                    this.$refs.formMain.reset();
                }
            },
            addItem(){
                this.selectedItem =null;
                this.itemsStep = 2;

            },
            editIT(item){
                this.piunit = item.unit;
                this.selectedItem = item;
                this.pname = item.name;
                this.pqty = item.qty;
                this.prate = item.rate;
                this.pcostrate = item.costrate;
                this.a = item.description;
                this.itemsStep = 2;
            },
            deleteIT(item){
                const index = this.pitems.findIndex(p => p.id === item.id);
                if (index !== -1) {
                    this.pitems.splice(index, 1);
                }
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
            saveitem(){
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
                        title: "Saving customers",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                    if(this.selectedItem === null){

                        let data = {};
                        const id = this.pitems.length +1;
                        Object.assign(data,{
                            id,
                            name:this.pname,
                            qty:this.pqty,
                            rate:this.prate,
                            costrate:this.pcostrate,
                            amount:(this.pqty * this.prate),
                            costamount:(this.pqty * this.pcostrate),
                            description:this.pdescription,
                        });

                        this.pitems.push(data);

                        this.$refs.form.reset();
                        this.itemsStep = 1;
                        this.qty = 0;
                        this.prate = 0;
                        this.pcostrate = 0;

                    }
                    else if(this.selectedItem !== null){
                        const index = this.pitems.findIndex(p =>p.id === this.selectedItem.id);
                        if(index !== -1){
                          let item = this.selectedItem;
                          Object.assign(item,{
                                unit:this.piunit,
                                name:this.pname,
                                qty:this.pqty,
                                rate:this.prate,
                                costrate:this.pcostrate,
                                amount:(this.pqty * this.prate),
                                costamount:(this.pqty * this.pcostrate),
                                description:this.pdescription,
                          });

                            this.$set(this.pitems,index,item);
                            this.$refs.form.reset();
                            this.itemsStep = 1;
                            this.qty = 0;
                            this.prate = 0;
                            this.pcostrate = 0;
                        }
                    }


                }

            },
            cancelitem(){
                this.$refs.form.reset();
                this.itemsStep = 1;
                this.qty = 0;
                this.prate = 0;
                this.pcostrate = 0;
            },
            //start customers
        textcustomer(item) {
            this.customerSelected = item;
            return item.name;
        },
        tableView(){
            this.itemsStep = 1;
        },
        valuecustomer(item) {
            return item;
        },
        handlecustomerSearch() {
            this.customerSelection = this.customerSelected;
            if (!this.ccustomer) {
                this.ccustomer = null;
                this.customerSelection = null;
            }
        },
        removecustomer(item) {
            this.ccustomer = null;
            this.customerSelection = null;
        },
        async getcustomers() {
            let data = {
                val: this.searchcustomer
            };
            this.spLoading = true;
            this.$store
                .dispatch("customersModule/GET_CUSTOMERS_ACTION", data)
                .finally(() => {
                    this.spLoading = false;
                });
        },
        //end customers

        //start workers
        textworker(item) {
            this.workerSelected = item;
            return item.name;
        },
        valueworker(item) {
            return item;
        },
        handleworkerSearch() {
            this.workerSelection = this.workerSelected;
            if (!this.pworker) {
                this.pworker = null;
                this.workerSelection = null;
            }
        },
        removeworker(item) {
            this.pworker = null;
            this.workerSelection = null;
        },
        async getworkers() {
            let data = {
                val: this.searchWorker
            };
            this.wkLoading = true;
            this.$store
                .dispatch("workersModule/GET_WORKERS_ACTION", data)
                .finally(() => {
                    this.wkLoading = false;
                });
        },
        //end workers
        saveAction(){
            this.dialogTitle = "Confirm service?";
                this.dialogBody = "By agreeing to continue, you agree";

                this.actionNow = "save_transaction";
                this.dialog = true;
        },
        cancelAction(){
            this.$refs.formMain.reset();
        }
        }

    }
</script>

<style lang="scss" scoped>
$color1:#009688;
.borderline{
border: $color1 solid 3px;
}
</style>
