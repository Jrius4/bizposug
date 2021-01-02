import Axios from "axios";

export default {
    namespaced: true,
    state: {
        payments: [],
        paymentPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalpayments: 0,
        paymentSortRowsBy: "created_at",
        payment: null,
        type: 'daily',
        payment: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_PAYMENTS(currentState, payload) {
            currentState.payments = payload.payments.data;
            currentState.paymentPagination.page = parseInt(
                payload.payments.current_page
            );

            currentState.paymentPagination.rowsPerPage = parseInt(
                payload.payments.per_page
            );
            currentState.totalpayments = parseInt(payload.payments.total);
            currentState.paymentSortRowsBy = payload.sortRowsBy || "created_at";
            currentState.type = payload.queryType || "daily"
        },
        GET_PAYMENT(currentState, payload) {
            const { payment, message } = payload;
            currentState.payment = payment;
            currentState.message = message;
        },
        GET_SELECTED_PAYMENT(currentState, payload) {
            currentState.action_done = payload.action_done;
        },
        GET_OPEN_WINDOW(currentState, payload) {

            const { openWindow } = payload;
            if (typeof openWindow !== 'undefined') {
                currentState.openWindow = openWindow;
            }


        },
        HANDLE_EDITOR_STATE(currentState, payload) {
            const { action_done } = payload;
            if (action_done === 'clear') {
                currentState.payment = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async GET_PAYMENT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const paymentID = payload.id;
                const url = `/api/payments/${paymentID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {payment,message} = result.data;
                    context.commit('GET_PAYMENT', {payment,message});
                })
            });

        },
        async GET_PAYMENTS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {

                    const keywords = payload.val || "";
                    const queryType = payload.queryType || "";
                    const start = payload.start || "";
                    const end = payload.end || "";
                    const data_type = payload.data_type || false;
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";
                    let url = "/api/payments";
                    if (data_type) {
                     url = "/api/summary-payments"
                    }

                    Axios.get(url, {
                        headers: {
                            Authorization: "Bearer " + context.rootState.token
                        },
                        params: {
                            rowsPerPage,
                            page,
                            sortDesc,
                            sortRowsBy,
                            keywords,
                            queryType,
                            start,
                            end
                        }
                    })
                        .then(response => {
                            const result = response.data;
                            context.commit("GET_PAYMENTS", result);
                            resolve(result);
                        })
                        .catch(err => {
                            console.log({ err });
                            reject(err);
                        });
                } else {
                    const err = "not loggedIn";
                    console.log({ err });
                    reject(err);
                }
            });
        },

        async SAVE_PAYMENT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    console.log({payload})
                    const paymentID = payload.id || "";
                    const items = payload.items || "";
                    const worker_id = payload.worker_id || "";
                    const supplier_id = payload.supplier_id || "";
                    const type_payment = payload.type_payment || "";
                    const description = payload.description || "";
                    const reciever = payload.reciever || "";
                    const received_by = payload.received_by || "";
                    const balance = payload.balance || "";
                    const paid = payload.paid || "";



                    let formData = new FormData();
                    let url = `api/store-payment`;
                    if (paymentID !== "") {
                        url = `/api/store-payment/${workerID}`;
                    }



                    formData.append('items',  JSON.stringify(items));
                    formData.append('worker_id', worker_id);
                    formData.append('supplier_id', supplier_id);
                    formData.append('balance', balance);
                    formData.append('type_payment', type_payment);
                    formData.append('description', description);
                    formData.append('received_by', received_by);
                    formData.append('reciever', reciever);
                    formData.append('paid', paid);


                    Axios.post(url, formData, {
                        headers: {
                            Authorization: "Bearer " + context.rootState.token,
                            "Content-Type":
                                "multipart/form-data; charset=utf-8; boundary=" +
                                Math.random()
                                    .toString()
                                    .substr(2)
                        }
                    }).then(result => {
                        const response = result.data;
                        resolve(response)
                    }).catch(err => {
                        reject({ err })
                    })


                } else {
                    const Error = 'Not Logged In';
                    reject(Error);
                }
            });
        },

    }
};
