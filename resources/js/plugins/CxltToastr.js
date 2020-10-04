import CxltToastr from "cxlt-vue2-toastr";
import "cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css";
import Vue from "vue";

var toastrConfigs = {
    position: "top right"
};
Vue.use(CxltToastr, toastrConfigs);
