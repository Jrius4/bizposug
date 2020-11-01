import Axios from "axios";

export default {
    namespaced: true,
    state: {},
    mutations: {},
    actions: {
        async SAVE_TRANSACTION_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                console.log({ payload });
                const items = payload.items || [];
                const discount = payload.discount || 0;
                const subtotal = payload.subtotal || 0;
                const total = payload.total || 0;
                const type_of_transaction = payload.type_of_transaction || 0;
                const url = `/api/save-payment`;
                let formData = new FormData();
                let arrItems = [];
                // items.forEach((item,i) => {
                //     formData.append(`item${i}`, item);
                // })
                formData.append("items", JSON.stringify(items));
                formData.append("discount", discount);
                formData.append("subtotal", subtotal);
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
