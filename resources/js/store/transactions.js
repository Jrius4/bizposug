import Axios from "axios";

export default {
    namespaced: true,
    state: {
        transactions: [],
        transactionPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totaltransactions: 0,
        transactionSortRowsBy: "created_at",
        transaction: null,
        type:'daily'
    },
    mutations: {
        GET_TRANSACTIONS(currentState, payload) {
            currentState.transactions = payload.transactions.data;
            currentState.transactionPagination.page = parseInt(
                payload.transactions.current_page
            );

            currentState.transactionPagination.rowsPerPage = parseInt(
                payload.transactions.per_page
            );
            currentState.totaltransactions = parseInt(payload.transactions.total);
            currentState.transactionSortRowsBy = payload.sortRowsBy || "created_at";
            currentState.type = payload.queryType || "daily"
        }
    },
    actions: {
        async GET_TRANSACTIONS_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const queryType = payload.queryType || "";
                    const data_type = payload.data_type || false;
                    const start = payload.start || "";
                    const end = payload.end || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";


                    let url = "/api/transactions";
                    if (data_type) {
                     url = "/api/summary-transactions"
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
                            context.commit("GET_TRANSACTIONS", result);
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
        }
    }
};
