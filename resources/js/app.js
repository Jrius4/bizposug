/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import store from "./store";

let token = null;
token = localStorage.getItem("access_token");
if (token !== null) {
    store
        .dispatch("GET_USER_TOKEN_ACTION", {})
        .then(result => {})
        .catch(err => {
            console.log({ err });
        });
} else {
    store
        .dispatch("GET_USER_TOKEN_ACTION", { token: "remove" })
        .then(result => {})
        .catch(err => {
            console.log({ err });
        });
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "dashboard-component",
    require("./components/dashboard/Dashboard.vue").default
);
Vue.component(
    "auto-logout",
    require("./components/Auth/AutoLogout.vue").default
);
Vue.component(
    "app-feautures",
    require("./components/Navbar/AppFeautures.vue").default
);

//products
Vue.component(
    "view-inventory",
    require("./components/modules/products/ViewInventory.vue").default
);
//suppliers
Vue.component("view-suppliers", require("./components/modules/suppliers/ViewSuppliers.vue").default)
//suppliers
Vue.component("view-workers",require("./components/modules/workers/ViewWorkers.vue").default)
Vue.component("view-customers",require("./components/modules/customers/ViewCustomers.vue").default)
//cashout
Vue.component(
    "cash-out",
    require("./components/modules/cashout/CashOut.vue").default
);
//payments
Vue.component(
    "payment-index",
    require("./components/modules/payments/PaymentIndex.vue").default
);

//reports
Vue.component(
    "transaction-summary-graphical",
    require("./components/modules/reports/transactions/TransactionsSummaryGraphical.vue")
        .default
);
// resources\js\components\modules\reports\payments\PaymentBarChartGroups.vue
// resources\js\components\modules\reports\payments\PaymentsSummaryGraphical.vue
Vue.component(
    "payments-summary-graphical",
    require("./components/modules/reports/payments/PaymentsSummaryGraphical.vue")
        .default
);
Vue.component(
    "sales-summary-graphical",
    require("./components/modules/reports/sales/ProductSalesSummaryGraphical.vue")
        .default
);

Vue.component(
    "sales-categories-summary-graphical",
    require("./components/modules/reports/sales/ProductCategoriesSalesSummaryGraphical.vue")
        .default
);

//graphs
Vue.component(
    "first-graph",
    require("./components/modules/graphs/FirstGraph.vue").default
);
Vue.component(
    "graph-index",
    require("./components/modules/graphs/Samples/GraphIndex.vue").default
);



//settings
Vue.component(
    "settings-index",
    require("./components/modules/settings/Settings.vue").default
);
Vue.component(
    "brands-index",
    require("./components/modules/settings/brands/ViewBrands.vue").default
);
Vue.component(
    "sizes-index",
    require("./components/modules/settings/sizes/ViewSizes.vue").default
);
Vue.component(
    "groups-index",
    require("./components/modules/settings/groups/ViewGroups.vue").default
);

//finances
Vue.component(
    "finance-links",
    require("./components/modules/finances/FinanceLinks.vue").default
);

Vue.component(
    "index-services",
    require("./components/modules/services/IndexServices.vue").default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import "./plugins/base";

import vuetify from "./plugins/vuetify";
import "./plugins/chartist";
import "./plugins/CxltToastr";
import "./plugins/echarts";
const app = new Vue({
    store,
    vuetify,
    data: {
        eventBus: new Vue()
    },
    provide: function() {
        return {
            eventBus: this.eventBus
        };
    },
    el: "#app"
});
