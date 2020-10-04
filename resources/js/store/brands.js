import Axios from "axios";

export default {
    namespaced: true,
    state: {
        brands: [],
        brandPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalbrands: 0,
        brandSortRowsBy: "name",
        brand: null
    },
    mutations: {
        GET_BRANDS(currentState, payload) {
            currentState.brands = payload.brands.data;
            currentState.brandPagination.page = parseInt(
                payload.brands.current_page
            );

            currentState.brandPagination.rowsPerPage = parseInt(
                payload.brands.per_page
            );
            currentState.totalbrands = parseInt(payload.brands.total);
            currentState.brandSortRowsBy = payload.sortRowsBy || "name";
        }
    },
    actions: {
        async GET_BRANDS_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/brands";
                    Axios.get(url, {
                        headers: {
                            Authorization: "Bearer " + context.rootState.token
                        },
                        params: {
                            rowsPerPage,
                            page,
                            sortDesc,
                            sortRowsBy,
                            keywords
                        }
                    })
                        .then(response => {
                            const result = response.data;
                            context.commit("GET_BRANDS", result);
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
