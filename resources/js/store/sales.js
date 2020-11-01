import Axios from "axios";

export default {
    namespaced: true,
    state: {
        sales: [],
        salePagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalsales: 0,
        saleSortRowsBy: "created_at",
        sale: null,
        type: 'daily',
        saleSortDesc: 'true',
        saleSortRowsBy:'created_at'
    },
    mutations: {
        GET_SALES(currentState, payload) {
            currentState.sales = payload.sales.data;
            currentState.salePagination.page = parseInt(
                payload.sales.current_page
            );

            currentState.salePagination.rowsPerPage = parseInt(
                payload.sales.per_page
            );
            currentState.totalsales = parseInt(payload.sales.total);
            currentState.saleSortRowsBy = payload.sortRowsBy || "created_at";
            currentState.type = payload.queryType || "daily"
            currentState.saleSortDesc = payload.sortDesc || "true"
        }
    },
    actions: {
        async GET_SALES_ACTION(context, payload) {
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


                    let url = "/api/product-sales";
                    if (data_type) {
                     url = "/api/summary-product-sales"
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

                            context.commit("GET_SALES", result);
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
        async GET_SALES_CATEGORIES_ACTION(context, payload) {
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


                    let url = "/api/product-sales";
                    if (data_type) {
                     url = "/api/summary-product-sales-categories"
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

                            context.commit("GET_SALES", result);
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
