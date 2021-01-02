<template>
    <v-app>
        <v-main>
            <v-container fluid>
                <v-row align="baseline" justify="space-around">
                    <v-col cols="12" lg="7" md="7" class="sticky">
                        <v-card>
                            <v-card-title> Products </v-card-title>
                            <v-card-text>
                                <v-data-iterator
                                    :items="items"
                                    :items-per-page.sync="itemsPerPage"
                                    :page="page"
                                    :search="search"
                                    :sort-by="sortBy.toLowerCase()"
                                    :sort-desc="sortDesc"
                                    hide-default-footer
                                >
                                    <template v-slot:header>
                                        <v-toolbar
                                            dark
                                            color="teal darken-3"
                                            class="mb-1"
                                        >
                                            <v-text-field
                                                autofocus
                                                v-model="search"
                                                clearable
                                                flat
                                                solo-inverted
                                                hide-details
                                                prepend-inner-icon="mdi-database-search"
                                                label="Search"
                                            ></v-text-field>
                                            <template
                                                v-if="
                                                    $vuetify.breakpoint.mdAndUp
                                                "
                                            >
                                                <v-spacer></v-spacer>
                                                <v-select
                                                    v-model="sortBy"
                                                    flat
                                                    solo-inverted
                                                    hide-details
                                                    :items="keys"
                                                    :item-text="textName"
                                                    :item-value="textValue"
                                                    prepend-inner-icon="mdi-sort-alphabetical-ascending"
                                                    label="Sort by"
                                                ></v-select>
                                                <v-spacer></v-spacer>
                                                <v-btn-toggle
                                                    v-model="sortDesc"
                                                    mandatory
                                                >
                                                    <v-btn
                                                        large
                                                        depressed
                                                        color="teal"
                                                        :value="false"
                                                    >
                                                        <v-icon
                                                            >mdi-arrow-up</v-icon
                                                        >
                                                    </v-btn>
                                                    <v-btn
                                                        large
                                                        depressed
                                                        color="teal"
                                                        :value="true"
                                                    >
                                                        <v-icon
                                                            >mdi-arrow-down</v-icon
                                                        >
                                                    </v-btn>
                                                </v-btn-toggle>
                                            </template>
                                        </v-toolbar>
                                    </template>
                                    <template v-slot:default="props">
                                        <v-container fluid>
                                            <v-row dense>
                                                <v-col
                                                    v-for="(card,
                                                    ind) in props.items"
                                                    :key="`item-${ind}`"
                                                    cols="12"
                                                    lg="4"
                                                    md="4"
                                                    sm="6"
                                                    xl="4"
                                                >
                                                    <v-card>
                                                        <v-img
                                                            :src="card.avatar"
                                                            class="white--text align-end"
                                                            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                            height="200px"
                                                        >
                                                            <v-card-title>
                                                                <h5>
                                                                    <sup
                                                                        class="teal darken-3 white--text pa-1"
                                                                    >
                                                                        {{
                                                                            card.category
                                                                        }}
                                                                    </sup>
                                                                    {{
                                                                        card.name
                                                                    }}
                                                                </h5>
                                                            </v-card-title>
                                                        </v-img>

                                                        <v-card-actions>
                                                            <table
                                                                class="table table-sm"
                                                            >
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <v-btn
                                                                                color="teal darken-4"
                                                                                dark
                                                                                small
                                                                                @click="
                                                                                    addCart(
                                                                                        card
                                                                                    )
                                                                                "
                                                                            >
                                                                                <v-icon
                                                                                    >mdi-cart-plus</v-icon
                                                                                >
                                                                                Add
                                                                                to
                                                                                cart
                                                                            </v-btn>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                           Qty Available: {{
                                                                                card.quantity
                                                                            }} products
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Cost:{{
                                                                                card.cost_price
                                                                                    | currency
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Whole
                                                                            Sale:{{
                                                                                card.wholesale_price
                                                                                    | currency
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Retail
                                                                            Sale:{{
                                                                                card.retailsale_price
                                                                                    | currency
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <v-spacer></v-spacer>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </template>
                                    <template v-slot:footer>
                                        <v-row
                                            class="mt-2"
                                            align="center"
                                            justify="center"
                                        >
                                            <span class="grey--text"
                                                >Items per page</span
                                            >
                                            <v-menu offset-y>
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs
                                                    }"
                                                >
                                                    <v-btn
                                                        dark
                                                        text
                                                        color="primary"
                                                        class="ml-2"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    >
                                                        {{ itemsPerPage }}
                                                        <v-icon
                                                            >mdi-chevron-down</v-icon
                                                        >
                                                    </v-btn>
                                                </template>
                                                <v-list>
                                                    <v-list-item
                                                        v-for="(number,
                                                        index) in itemsPerPageArray"
                                                        :key="index"
                                                        @click="
                                                            updateItemsPerPage(
                                                                number
                                                            )
                                                        "
                                                    >
                                                        <v-list-item-title>
                                                            {{ number.name }}
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>

                                            <v-spacer></v-spacer>

                                            <span class="mr-4 grey--text">
                                                Page {{ page }} of
                                                {{ numberOfPages }}
                                            </span>
                                            <v-btn
                                                fab
                                                dark
                                                color="teal darken-3"
                                                class="mr-1"
                                                @click="formerPage"
                                            >
                                                <v-icon
                                                    >mdi-chevron-left</v-icon
                                                >
                                            </v-btn>
                                            <v-btn
                                                fab
                                                dark
                                                color="teal darken-3"
                                                class="ml-1"
                                                @click="nextPage"
                                            >
                                                <v-icon
                                                    >mdi-chevron-right</v-icon
                                                >
                                            </v-btn>
                                        </v-row>
                                    </template>
                                </v-data-iterator>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" lg="5" md="5" sm="12" class="sticky">
                        <v-form ref="form">
                            <v-card elevation="4">
                                <v-card-title>
                                    In Cart
                                    <sup
                                        class="badge badge-secondary text-white"
                                        >{{ inCartItems.length }}</sup
                                    >
                                </v-card-title>
                                <v-card-text>
                                    <v-select
                                        ref="typeofSale"
                                        :rules="[
                                            () =>
                                                !!typeofSale ||
                                                'Type is required'
                                        ]"
                                        :menu-props="{
                                            bottom: true,
                                            offsetY: true
                                        }"
                                        label="Type of Sale"
                                        v-model="typeofSale"
                                        :items="['Whole Sale', 'Retail Sale']"
                                    >
                                    </v-select>
                                    <v-data-table
                                        dense
                                        item-key="id"
                                        v-model="selectedinCartItems"
                                        :headers="headers"
                                        :loading="loadingCart"
                                        :items="inCartItemProducts"
                                        :single-expand="singleExpand"
                                        :expanded.sync="expanded"
                                        show-expand
                                        :options.sync="options"
                                        :items-per-page="incartItemsPerPage"
                                        :server-items-length="totalitemsCart"
                                    >
                                        <template
                                            v-slot:expanded-item="{
                                                headers,
                                                item
                                            }"
                                        >
                                            <td :colspan="headers.length">
                                                {{ item.name }}
                                                <table class="table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <th>Qty Available:</th>
                                                            <td>
                                                                {{
                                                                    item.quantity
                                                                }} products
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cost Price:</th>
                                                            <td>
                                                                {{
                                                                    item.cost_price
                                                                        | currency
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Retail Sale
                                                                Price:
                                                            </th>
                                                            <td>
                                                                {{
                                                                    item.retailsale_price
                                                                        | currency
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Whole Sale
                                                                Price:
                                                            </th>
                                                            <td>
                                                                {{
                                                                    item.wholesale_price
                                                                        | currency
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <p>
                                                                    {{
                                                                        `${item.description}`.substr(
                                                                            0,
                                                                            20
                                                                        )
                                                                    }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </template>

                                        <template v-slot:item.avatar="{ item }">
                                            <v-avatar
                                                tile
                                                size="50"
                                                class="elevation-4 shadow-md"
                                            >
                                                <img
                                                    :src="`${item.avatar}`"
                                                    alt=""
                                                />
                                            </v-avatar>
                                        </template>

                                        <template v-slot:item.amount="{ item }">
                                            <span
                                                v-if="
                                                    typeofSale === 'Whole Sale'
                                                "
                                            >
                                                {{
                                                    (item.wholesale_price *
                                                        item.qty)
                                                        | currency
                                                }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    typeofSale === 'Retail Sale'
                                                "
                                            >
                                                {{
                                                    (item.retailsale_price *
                                                        item.qty)
                                                        | currency
                                                }}
                                            </span>
                                        </template>
                                        <template
                                            v-slot:item.actions="{ item }"
                                        >
                                            <div class="row d-flex">
                                                <v-btn
                                                    icon
                                                    color="blue"
                                                    small
                                                    @click="addQty(item)"
                                                >
                                                    <v-icon small>
                                                        mdi-clipboard-edit-outline
                                                    </v-icon>
                                                </v-btn>
                                                <!-- <v-btn icon color="blue" small @click="minusQty(item)">
                          <v-icon small> mdi-minus </v-icon>
                        </v-btn> -->
                                                <v-btn
                                                    icon
                                                    color="red"
                                                    small
                                                    @click="removeItem(item)"
                                                >
                                                    <v-icon small>
                                                        mdi-cart-off
                                                    </v-icon>
                                                </v-btn>
                                            </div>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                            <div class="my-6 clear-fix"></div>
                            <v-card>
                                <v-card-text>
                                    <v-autocomplete
                                        ref="pcustomer"
                                        v-model="pcustomer"
                                        prepend-icon="mdi-account-cash"
                                        label="Customer"
                                        clearable
                                        dense
                                        :loading="spLoading"
                                        :items="customers"
                                        :search-input.sync="searchCustomer"
                                        :item-text="textCustomer"
                                        :item-value="valueCustomer"
                                        autocomplete
                                        :menu-props="{ bottom: true, offsetY: true }"
                                        hint="Please Search Customer"
                                        chips
                                        attach
                                        v-on:change="handleCustomerSearch()"
                                    >
                                        <template v-slot:selection="data">
                                            <v-chip
                                                v-bind="data.attrs"
                                                :input-value="data.selected"
                                                close
                                                @click="data.select"
                                                @click:close="removeCustomer(data.item)"
                                            >
                                                {{ `${data.item.name}/${data.item.contact} ` }}
                                            </v-chip>
                                        </template>

                                        <template v-slot:item="{ item }">
                                            <v-row align="center" justify="center">
                                                <v-col cols="12" sm="6" class="mx-1">
                                                    <h6 v-html="`${item.name }/${item.contact}`" />
                                                </v-col>
                                            </v-row>
                                        </template>
                                    </v-autocomplete>
                                </v-card-text>
                            </v-card>
                            <div class="my-6 clear-fix"></div>
                            <v-card>
                                <v-card-text>
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td>Sub Total:</td>
                                                <td colspan="2">
                                                    {{
                                                        inCartItemsTotals
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Discount:</td>
                                                <td v-if="!showInput">
                                                    {{ tdiscount | currency }}
                                                </td>
                                                <td v-if="showInput">
                                                    <input
                                                        class="dis-input"
                                                        type="text"
                                                        v-model="
                                                            tdiscountFormat
                                                        "
                                                    />
                                                </td>

                                                <td v-if="tdiscount === 0">
                                                    <v-btn
                                                        icon
                                                        color="teal darken-4"
                                                        dark
                                                        small
                                                        @click="addDiscount"
                                                    >
                                                        <v-icon
                                                            >mdi-plus-circle-outline</v-icon
                                                        >
                                                    </v-btn>
                                                </td>
                                            </tr>
                                            <tr class="total-bg">
                                                <td>Total:</td>
                                                <td colspan="2">
                                                    {{
                                                        (inCartItemsTotals -
                                                            tdiscount)
                                                            | currency
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="row d-block">
                                                        <v-btn
                                                            class="btn-block"
                                                            color="teal darken-4"
                                                            elevation="2"
                                                            dark
                                                            @click="
                                                                confirmCart()
                                                            "
                                                        >
                                                            <v-icon
                                                                >mdi-cart-arrow-up</v-icon
                                                            >
                                                            {{ " " }}Check Out
                                                        </v-btn>
                                                        <v-btn
                                                            dark
                                                            class="btn-block"
                                                            color="red darken-4"
                                                            elevation="2"
                                                            @click="
                                                                cancelCart()
                                                            "
                                                        >
                                                            <v-icon
                                                                >mdi-cart-off</v-icon
                                                            >
                                                            {{ " " }} Cancel
                                                        </v-btn>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </v-card-text>
                            </v-card>
                        </v-form>
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
                <template>
                    <v-row justify="center">
                        <v-dialog v-model="editor" persistent max-width="320">
                            <v-card>
                                <v-form ref="productEditor">
                                    <v-card-subtitle class="headline">{{
                                        editorTitle
                                    }}</v-card-subtitle>

                                    <v-card-text>
                                        <v-row align="center" justify="center">
                                            <v-col cols="12">
                                                <v-text-field
                                                    label="No. Of Items"
                                                    ref="qty"
                                                    v-model="qtyFormat"
                                                    placeholder="No. Of Items"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            color="red darken-3"
                                            text
                                            @click="cancelProduct"
                                            >Disagree</v-btn
                                        >
                                        <v-spacer></v-spacer>

                                        <v-btn
                                            color="green darken-2"
                                            text
                                            @click="editProduct"
                                            >Agree</v-btn
                                        >
                                    </v-card-actions>
                                </v-form>
                            </v-card>
                        </v-dialog>
                    </v-row>
                </template>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    name: "CashOut",
    data: () => {
        return {
            incartItemsPerPage:5,
            loadingCart:false,
            options: {},
            editor: false,
            qty: 0,
            editorTitle: "",
            selectedProduct: null,
            // start dialog
            actionNow: "",
            dialog: false,
            dialogTitle: "",
            dialogBody: "",
            agree_status: false,
            // end dialog
            tdiscount: 0,
            itemsPerPageArray: [
                { name: 5, value: 5 },
                { name: 15, value: 15 },
                { name: 25, value: 25 },
                { name: "All", value: -1 }
            ],
            search: "",
            filter: {},
            sortDesc: false,
            page: 1,
            itemsPerPage: 5,
            sortBy: "name",
            // itelator start
            keys: [
                { name: "Name", value: "name" },
                { name: "Barcode", value: "barcode" },
                { name: "Quantity", value: "quantity" }
            ],
            // itelator end
            errorMessages: "",
            formHasErrors: false,
            typeofSale: null,
            expanded: [],
            singleExpand: false,
            showInput: false,
            selectedinCartItems: [],
            inCartItems: [],
            headers: [
                {
                    text: "Avatar",
                    align: "start",
                    value: "avatar",
                    sortable: false
                },
                { text: "Name", align: "start", value: "name", sortable: true },
                { text: "QTY", align: "left", value: "qty", sortable: true },
                {
                    text: "Amount",
                    align: "left",
                    value: "amount",
                    sortable: true
                },
                { text: "", value: "data-table-expand", sortable: false },
                {
                    text: "Actions",
                    align: "left",
                    value: "actions",
                    sortable: false
                }
            ],
            incartPage:1,
            pcustomer:null,
            ptotalprofit:0,
            ptotalloss:0,
            searchCustomer: null,
            spLoading: false,
            customerSelection: null,
            customerSelected: null,
        };
    },
    mounted() {
        this.getProducts();
        this.getCustomers();
        this.GET_CUSTOMER_ACTION({id:1});
    },
    computed: {
        ...mapState({
            products: state => state.productsModule.products,
            totalrowsPerPageProducts: state =>
                state.productsModule.productPagination.rowsPerPage,
            totalproducts: state => state.productsModule.totalproducts,
            productSortRowsBy: state => state.productsModule.productSortRowsBy,
            customer: state => state.customersModule.customer,
            customers: (state) => state.customersModule.customers,
            // customerCart: (state) => state.cartModule.customer,
        }),
        totalitemsCart(){
            let item_no = this.inCartItems.length;
            return item_no;
        },

        items() {
            let items = this.products;
            return items;
        },

        qtyFormat: {
            get: function() {
                return this.qty;
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                this.qty = Number(newValue1.replace(/[^0-9\.]/g, ""));
            }
        },
        tdiscountFormat: {
            get: function() {
                if (this.tdiscount !== null) {
                    return this.formatAsCurrency(this.tdiscount, 0);
                }
            },
            set: function(newValue) {
                const newValue1 = newValue || "UGX 0";
                let disct = Number(newValue1.replace(/[^0-9\.]/g, ""));
                if(disct < this.finProduct_Loss.profit){
                    this.tdiscount =disct
                }else{
                    this.$toast.error({
                        title: `Discount exceeds you profits of ${this.finProduct_Loss.profit}`,
                        message: "Change this Please!",
                        color: "#D32F2F",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                     this.tdiscount = 0
                }
            }
        },
        inCartItemProducts(){
            return this.inCartItems.slice((this.incartPage - 1) * this.incartItemsPerPage, this.incartPage * this.incartItemsPerPage)
        },
        inCartItemsTotals() {
            let total = 0;
            this.inCartItems.forEach(item => {
                if (this.typeofSale === "Whole Sale") {
                    total += item.wholesale_price * item.qty;
                } else if (this.typeofSale === "Retail Sale") {
                    total += item.retailsale_price * item.qty;
                }
            });
            return total;
        },

        //start iterator
        numberOfPages() {
            //   return Math.ceil(this.items.length / this.itemsPerPage);
            return Math.ceil(
                this.totalproducts / this.totalrowsPerPageProducts
            );
        },
        filteredKeys() {
            return this.keys.filter(
                key => key.value !== `student_name` || key.value !== `indexno`
            );
        },
        //end iterator
        form() {
            let validate = {
                typeofSale: this.typeofSale || ""
            };

            return validate;
        },
        finProduct_Loss(){
            let loss= 0
            let profit = 0;
            this.inCartItems.forEach(ele=>{
                if(this.typeofSale === "Retail Sale"){
                    const amt = (ele.qty *(ele.retailsale_price-ele.cost_price));
                    if(amt>0 ) profit+=amt;
                    if(amt<0 ) loss+=amt;
                }
                else if(this.typeofSale === "Whole Sale"){
                     const amt = (ele.qty *(ele.wholesale_price-ele.cost_price));
                    if(amt>0 ) profit+=amt;
                    if(amt<0 ) loss+=amt;
                }
            });
            return {loss,profit};
        }
    },
    methods: {
        ...mapActions({
            GET_CUSTOMER_ACTION:"customersModule/GET_CUSTOMER_ACTION"
        }),
        getProductsInCart(){
            this.loadingCart = true
            return new Promise((resolve,reject)=>{
                if(!itemsPerPage) this.options.itemsPerPage = 5;
                const { sortBy, sortDesc, page, itemsPerPage } = this.options;

                this.incartItemsPerPage = !itemsPerPage?5:itemsPerPage;
                this.incartPage = page;
                this.loadingCart = false;

            })
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
        addDiscount() {
            this.showInput = true;
        },
        async getProducts() {
            this.loading = true;
            let pageNew = this.page;
            let pagination = {};
            let search = this.search;

            pagination = {
                val: search,
                page: pageNew,
                sortRowsBy: this.sortBy,
                rowsPerPage: this.itemsPerPage,
                sortDesc: this.sortDesc
            };

            this.$store
                .dispatch("productsModule/GET_PRODUCTS_ACTION", pagination)
                .finally(() => {
                    this.loading = false;
                });
        },
        addCart(item) {
            const index = this.inCartItems.findIndex(p => p.id === item.id);
            if (index !== -1) {
                Object.assign(item, { qty: 1 + parseInt(item.qty || 0) });
                Object.assign(item, { profit: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))>0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))>0?(item.qty *(item.wholesale_price-item.cost_price)):0)});
                Object.assign(item, { loss: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))<0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))<0?(item.qty *(item.wholesale_price-item.cost_price)):0)});
                if(item.qty > parseInt(item.quantity)){
                    this.$toast.error({
                        title: `You exceeded the inventory of ${item.quantity} products`,
                        message: "There is lesser Products, please!",
                        color: "#D32F2F",
                        timeOut: 8000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                }
                else if (item.qty < parseInt(item.quantity)){
                    this.$set(this.inCartItems, index, item);
                }



            } else {
                Object.assign(item, { qty: 1 });
                 Object.assign(item, { profit: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))>0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))>0?(item.qty *(item.wholesale_price-item.cost_price)):0)});
                Object.assign(item, { loss: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))<0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))<0?(item.qty *(item.wholesale_price-item.cost_price)):0)});

                this.inCartItems.push(item);
            }
        },

        addQty(item) {
            this.editorTitle = "Edit Product Form";
            this.selectedProduct = item;
            this.qty = item.qty;
            this.editor = true;
        },
        minusQty(item) {
            const index = this.inCartItems.findIndex(p => p.id === item.id);
            if (index !== -1) {
                Object.assign(item, { qty: (item.qty || 0) - 1 });
                 Object.assign(item, { profit: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))>0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))>0?(item.qty *(item.wholesale_price-item.cost_price)):0)});
                Object.assign(item, { loss: this.typeofSale === "Retail Sale" ? ((item.qty *(item.retailsale_price-item.cost_price))<0?(item.qty *(item.retailsale_price-item.cost_price)):0):((item.qty *(item.wholesale_price-item.cost_price))<0?(item.qty *(item.wholesale_price-item.cost_price)):0)});
                if (item.qty === 0 || item.qty < 0) {
                    this.inCartItems.splice(index, 1);
                } else {
                    this.$set(this.inCartItems, index, item);
                }
            }
        },
        removeItem(item) {
            const index = this.inCartItems.findIndex(p => p.id === item.id);
            if (index !== -1) {
                this.inCartItems.splice(index, 1);
            }
        },

        // itelator start

        textName(val) {
            return val.name;
        },
        textValue(val) {
            return val.value;
        },
        updateItemsPerPage(number) {
            this.itemsPerPage = number.value;
        },
        nextPage() {
            if (this.page + 1 <= this.numberOfPages) this.page += 1;
        },
        formerPage() {
            if (this.page - 1 >= 1) this.page -= 1;
        },

        // itelator end
        setType() {
            if (
                this.typeofSale === "Whole Sale" ||
                this.typeofSale === "Retail Sale"
            ) {
                if (this.inCartItems.length === 0) {
                this.$toast.error({
                        title: "Invalid No Product Yet",
                        message: "Cart is empty!",
                        color: "#D32F2F",
                        timeOut: 5000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                    return false;
                } else {
                    return true;
                }
            } else {
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
                }
                this.$toast.error({
                    title: "Invalid Transaction Type",
                    message: "Check whether you have selected type!",
                    color: "#D32F2F",
                    timeOut: 5000,
                    showMethod: "lightSpeedIn",
                    hideMethod: "slideOutUp"
                });
                return false;
            }
        },

        confirmCart() {
            if (this.setType()) {
                this.dialogTitle = "Confirm Transaction?";
                this.dialogBody = "By agreeing to continue, you agree";

                this.actionNow = "save_transaction";
                this.dialog = true;
            } else {
                this.$toast.error({
                    title: "Invalid Cart",
                    message:
                        "Check whether you have selected items & type of Transaction!",
                    color: "#D32F2F",
                    timeOut: 5000,
                    showMethod: "lightSpeedIn",
                    hideMethod: "slideOutUp"
                });
            }
        },
        async cancelCart() {
            this.$refs.form.reset();
            this.inCartItems = [];
             this.GET_CUSTOMER_ACTION({id:1});
             this.getProducts();
        },

        // start dialog
        disagree() {
            this.agree_status = false;
            this.dialog = false;
            if (this.actionNow === "save_transaction") this.submitTrans();
        },
        agree() {
            this.agree_status = true;
            this.dialog = false;
            if (this.actionNow === "save_transaction") this.submitTrans();
        },
        // end dialog
        submitTrans() {
            if (this.agree_status) {
                if(this.pcustomer === null || typeof this.pcustomer === 'undefined'){
                    this.GET_CUSTOMER_ACTION({id:1});
                }
                const {profit,loss} = this.finProduct_Loss
                let data = {
                    items: this.inCartItems,
                    customer_id: this.pcustomer !== null?this.pcustomer.id:null,
                    subtotal: this.inCartItemsTotals,
                    discount: this.tdiscount,
                    total: this.inCartItemsTotals - this.tdiscount,
                    type_of_transaction: this.typeofSale,
                    profit,
                    loss,
                };
                this.$store
                    .dispatch("cartModule/SAVE_TRANSACTION_ACTION", data)
                    .finally(() => {
                        this.typeofSale = "";
                        this.$refs.form.reset();
                        this.inCartItems = [];
                        this.tdiscount = 0;
                        this.cancelCart();
                    });
            } else {
                this.inCartItems = [];
                // this.inCartItemsTotals = 0;

                this.tdiscount = 0;
                this.typeofSale = "";
                this.$refs.form.reset();
            }
        },
        editProduct() {
            const item = this.selectedProduct;
            const qty = parseInt(this.qty);
            const index = this.inCartItems.findIndex(p => p.id === item.id);
            if (index !== -1) {
                Object.assign(item, { qty: qty });
                if(item.qty > parseInt(item.quantity)){
                    this.$toast.error({
                        title: `You exceeded the inventory of ${item.quantity} products` ,
                        message: "There is lesser Products, please!",
                        color: "#D32F2F",
                        timeOut: 8000,
                        showMethod: "lightSpeedIn",
                        hideMethod: "slideOutUp"
                    });
                }
                else if(item.qty < parseInt(item.quantity)){
                    this.$set(this.inCartItems, index, item);
                }

            }
            this.qty = 0;
            this.editor = false;
        },
        cancelProduct() {
            this.qty = 0;
            this.editor = false;
        },

        //start Customers
        textCustomer(item) {
            this.customerSelected = item;
            return item.name;
        },
        valueCustomer(item) {
            return item;
        },
        handleCustomerSearch() {
            this.customerSelection = this.customerSelected;
            if (!this.pcustomer) {
                this.pcustomer = null;
                this.customerSelection = null;
            }
        },
        removeCustomer(item) {
            this.pcustomer = null;
            this.customerSelection = null;
        },
        async getCustomers() {
            let data = {
                val: this.searchCustomer
            };
            this.spLoading = true;
            this.$store
                .dispatch("customersModule/GET_CUSTOMERS_ACTION", data)
                .finally(() => {
                    this.spLoading = false;
                });
        },
        //end Customers

    },
    watch: {
        searchCustomer(){
            if (!this.searchCustomer) {
                this.searchCustomer = "";
            }
            if (this.searchCustomer !== "") {
                this.getCustomers();
            }
        },
        customer(val){
            let cst = null;
            if(!val) cst = null;
            else cst = val;
            this.getCustomers();
            this.customerSelected = cst;
            this.customerSelection = cst;
            this.pcustomer = cst;
        },
        search(value) {
            if (!this.search) {
                this.search = "";
            }
            if (this.search !== "") {
                this.getProducts();
            }
            this.getProducts();
        },
        itemsPerPage() {
            this.getProducts();
        },
        page() {
            this.getProducts();
        },
        sortDesc() {
            this.getProducts();
        },
        sortBy() {
            this.getProducts();
        },
        options: {
      handler() {
        this.getProductsInCart();
      },
      deep: true,
    },
    },
    filters: {
        currency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "UGX"
            }).format(value);
        }
    }
};
</script>

<style scoped>
/* .sticky {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
} */
.dis-input {
    border: 0.5px solid teal;
    -webkit-box-shadow: inset 0 0 0.4rem rgba(0, 0, 0, 0.1),
        0 0 0.8rem rgba(0, 0, 0, 0.1);
    -moz-box-shadow: inset 0 0 0.4rem rgba(0, 0, 0, 0.1),
        0 0 0.8rem rgba(0, 0, 0, 0.1);
    box-shadow: inset 0 0 0.4rem rgba(0, 0, 0, 0.1),
        0 0 0.8rem rgba(0, 0, 0, 0.1);
    padding: 0.4rem;
    background: rgba(255, 255, 255, 0.5);
    margin: 0 0 0.4rem 0;
}
.total-bg {
    background: #00796b;
    color: #ffff;
}
</style>
