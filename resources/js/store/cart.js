import Axios from "axios";

export default {
    namespaced: true,
    state: {
        customer:null,
        message:null,
    },
    mutations: {
        GET_CUSTOMER(currentState, payload) {
            const { customer, message } = payload;
            currentState.customer = customer;
        }
    },
    actions: {
        async GET_CUSTOMER_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                const custID = payload.custID || 1;
                let url = `/api/customer/${custID}`;
                Axios.get(url,{
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(res => {
                    const { customer, message } = res.data;
                    resolve({ customer, message });
                    context.commit('GET_CUSTOMER', { customer, message });

                }).catch(err => {
                    reject({ err });
                    console.log({err})
                })
             });
         },
        async SAVE_TRANSACTION_ACTION(context, payload) {
            new Promise((resolve, reject) => {

                const items = payload.items || [];
                const customer_id = payload.customer_id || "";
                const discount = payload.discount || 0;
                const subtotal = payload.subtotal || 0;
                const profit = payload.profit || 0;
                const loss = payload.loss || 0;
                const total = payload.total || 0;
                const type_of_transaction = payload.type_of_transaction || 0;
                const url = `/api/save-sale`;
                let formData = new FormData();
                let arrItems = [];

                formData.append("items", JSON.stringify(items));
                formData.append("discount", discount);
                formData.append("customer_id", customer_id);
                formData.append("subtotal", subtotal);
                formData.append("profit", profit);
                formData.append("loss", loss);
                formData.append("total", total);
                formData.append("type_of_transaction", type_of_transaction);
                Axios.post(url, formData, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(response => {
                    const transID = response.data.transID;

                    window.open(`/print/reciept/?transID=${transID}`, "_blank");
                }).catch(err => {
                    console.log(err);
                    reject(err)
                });
            });
        }
    }
};
