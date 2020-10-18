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
        type:'daily'
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
        }
    },
    actions: {
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

    }
};
