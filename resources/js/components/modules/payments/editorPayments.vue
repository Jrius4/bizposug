<template>
    <div>
        <v-card>
            <v-card-text>
                <v-form ref="formMain">
                    <v-row align="baseline" justify="center">
                        <v-col cols="12" md="5">
                            <v-select
                                ref="type_payment"
                                v-model="type_payment"
                                :items="['supplier', 'worker', 'others']"
                                :menu-props="{ bottom: true, offsetY: true }"
                                label="Payment Type"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            md="8"
                            v-if="type_payment === 'supplier'"
                        >
                            <v-autocomplete
                                ref="psupplier"
                                :rules="[
                                    () => !!psupplier || 'supplier is required'
                                ]"
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
                                v-on:change="handlesupplierSearch()"
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
                        </v-col>

                        <v-col
                            cols="12"
                            md="8"
                            v-if="type_payment === 'worker'"
                        >
                            <v-autocomplete
                                ref="pworker"
                                :rules="[
                                    () => !!pworker || 'worker is required'
                                ]"
                                v-model="pworker"
                                prepend-icon="mdi-tag-multiple"
                                label="Worker"
                                clearable
                                dense
                                :loading="wkLoading"
                                :items="workers"
                                :search-input.sync="searchWorker"
                                :item-text="textworker"
                                :item-value="valueworker"
                                autocomplete
                                :menu-props="{ bottom: true, offsetY: true }"
                                hint="Please Search Worker"
                                chips
                                attach
                                v-on:change="handleworkerSearch()"
                            >
                                <template v-slot:selection="data">
                                    <v-chip
                                        v-bind="data.attrs"
                                        :input-value="data.selected"
                                        close
                                        @click="data.select"
                                        @click:close="removeworker(data.item)"
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
                        </v-col>

                        <v-col cols="12" md="8">
                            <v-window v-model="itemsStep">
                                <v-window-item :value="1">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Product/Service Name</th>
                                                <th>QTY</th>
                                                <th>UNITS</th>
                                                <th>RATE</th>
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
                                            ref="prate"
                                            v-model="prateFormat"
                                            label="Rate"
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
                                ref="ppay"
                                v-model="ppayFormat"
                                label="Pay"
                                clearable
                                :rules="[() => !!ppay || 'pay is required']"
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
                            <v-text-field
                                ref="pLastBalance"
                                v-model="pLastBalanceFormat"
                                label="Last Balance"
                                clearable
                            >
                            </v-text-field>

                            <v-card>
                                <v-card-subtitle>
                                    <h5 class="text-center">Summary</h5>
                                </v-card-subtitle>
                                <v-card-text>
                                    <table class="table table-sm">
                                        <tbody v-if="pworker !== null">
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <td>{{ pworker.name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td>{{ pworker.role }}</td>
                                            </tr>
                                            <tr>
                                                <th>Contact</th>
                                                <td>{{ pworker.contact }}</td>
                                            </tr>
                                            <tr>
                                                <th>Salary</th>
                                                <td>
                                                    {{
                                                        pworker.salary
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Prev. Balance</th>
                                                <td>
                                                    {{
                                                        pworker.balance
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-if="psupplier !== null">
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <td>{{ psupplier.name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Company</th>
                                                <td>
                                                    {{
                                                        psupplier.company ||
                                                            "None"
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Contact</th>
                                                <td>{{ psupplier.contact }}</td>
                                            </tr>
                                            <tr>
                                                <th>Prev. Balance</th>
                                                <td>
                                                    {{
                                                        psupplier.balance
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
                                                <th>Currently Paid</th>
                                                <td>{{ppay
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
                                    Payment</v-btn
                                >
                                <v-btn
                                    class="btn-block"
                                    @click="cancelAction()"
                                    dark
                                    color="red darker-3"
                                    ><v-icon>mdi-cancel</v-icon> Cancel
                                    Payment</v-btn
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
        data:()=>{
            return {
                piunit:null,
                selectedItem:null,
                precieved_by:null,
                itemsStep:1,
                wkLoading:false,
                spLoading:false,
                pid:null,
                searchWorker:null,
                type_payment:'supplier',
                ppay:null,
                pbalance:null,
                pdescription:null,
                padescription:null,
                searchSupplier:null,

                pworker:null,
                psupplier:null,
                pLastBalance:null,
                workerSelection:null,
                workerSelected:null,
                supplierSelection:null,
                supplierSelected:null,
                pname:null,
                pqty:0,
                prate:null,
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
                suppliers: (state) => state.suppliersModule.suppliers,
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
            ppayFormat: {
                get: function() {
                    if (this.ppay !== null) {
                        return this.formatAsCurrency(this.ppay,0);
                    }
                },
                set: function(newValue) {
                    const newValue1 = newValue || '0';
                    this.ppay = Number(newValue1.replace(/[^0-9\.]/g, ""));
                }
            },
            pbalanceFormat: {
                get: function() {
                    if(this.psupplier !== null) this.pbalance = this.ppay !== null ?  (this.ppay - (parseInt(this.psupplier.balance) + this.pamount )):null;
                    if(this.pworker !== null) this.pbalance = this.ppay !== null ?  this.ppay - (parseInt(this.pworker.salary) +(parseInt(this.pworker.balance)) + this.pamount ):null;
                    if(this.pworker === null && this.psupplier === null){
                        this.pbalance = this.ppay !== null? (this.ppay - this.pamount):null;
                    }
                    if(this.pLastBalance !== null){
                        this.pbalance -=  this.pLastBalance;
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
                }
            },
            formMain(){
                return {
                    type_payment:this.type_payment || "",
                    precieved_by:this.precieved_by || "",
                    ppay:this.ppay || "",
                }
            }
        },
        watch:{

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
            psupplier(val){
                if(val === null) this.pbalance = null;
            },
            pworker(val){
                if(val === null) this.pbalance = null;
            },
            searchSupplier(val) {
                if (!this.searchSupplier) {
                    this.searchSupplier = "";
                }
                if (this.searchSupplier !== "") {
                    this.getSuppliers();
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
            type_payment(val){
                this.pworker = null;
                this.psupplier = null;
                this.supplierSelection = null;
                this.supplierSelected = null;
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
                GET_OPEN_WINDOW:'paymentsModule/GET_OPEN_WINDOW'
            }),
            disagree() {
                this.agree_status = false;
                this.dialog = false;
                if (this.actionNow === "save_transaction") this.submitPayment();
            },
            agree() {
                this.agree_status = true;
                this.dialog = false;
                if (this.actionNow === "save_transaction") this.submitPayment();
            },
            submitPayment() {
                if (this.agree_status) {
                    this.formHasErrors = false;
                Object.keys(this.formMain).forEach(f => {
                    if (!this.formMain[f]) this.formHasErrors = true;

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
                        title: "Saving Payment",
                        message: "All fields are good",
                        color: "#00ACC1",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                    let received_by = null;
                    let reciever = null;
                   received_by = this.precieved_by
                    if(this.pworker !== null) reciever = {name:this.pworker.name,contact:this.pworker.contact};
                    else if(this.psupplier !== null) reciever = {name:this.psupplier.name,contact:this.psupplier.contact};
                    else if(this.pworker === null && this.psupplier === null) reciever = {name:this.precieved_by,contact:null};
                    let data = {
                        items: this.pitems.length > 0? this.pitems:null,
                        worker_id:this.pworker !== null?this.pworker.id:null,
                        supplier_id:this.psupplier !== null?this.psupplier.id:null,
                        type_payment:this.type_payment,
                        description:this.padescription,
                        received_by:received_by,
                        reciever:reciever!== null?JSON.stringify(reciever):null,
                        balance:this.pbalance,
                        paid:this.ppay
                    };
                    this.$store
                        .dispatch("paymentsModule/SAVE_PAYMENT_ACTION", data)
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
                        title: "Saving suppliers",
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
                            amount:(this.pqty * this.prate),
                            description:this.pdescription,
                        });

                        this.pitems.push(data);

                        this.$refs.form.reset();
                        this.itemsStep = 1;
                        this.qty = 0;
                        this.prate = 0;

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
                                amount:(this.pqty * this.prate),
                                description:this.pdescription,
                          });

                            this.$set(this.pitems,index,item);
                            this.$refs.form.reset();
                            this.itemsStep = 1;
                            this.qty = 0;
                            this.prate = 0;
                        }
                    }


                }

            },
            cancelitem(){
                this.$refs.form.reset();
                this.itemsStep = 1;
                this.qty = 0;
                this.prate = 0;
            },
            //start suppliers
        textSupplier(item) {
            this.supplierSelected = item;
            return item.name;
        },
        tableView(){
            this.itemsStep = 1;
        },
        valueSupplier(item) {
            return item;
        },
        handlesupplierSearch() {
            this.supplierSelection = this.supplierSelected;
            if (!this.psupplier) {
                this.psupplier = null;
                this.supplierSelection = null;
            }
        },
        removeSupplier(item) {
            this.psupplier = null;
            this.supplierSelection = null;
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
            this.dialogTitle = "Confirm Payment?";
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

<style lang="scss" scoped></style>
