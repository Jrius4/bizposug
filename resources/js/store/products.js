import Axios from "axios";

export default {
    namespaced: true,
    state: {
        products: [],
        productPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalproducts: 0,
        productSortRowsBy: "name",
        product: null
    },
    mutations: {
        GET_PRODUCTS(currentState, payload) {
            currentState.products = payload.products.data;
            currentState.productPagination.page = parseInt(
                payload.products.current_page
            );

            currentState.productPagination.rowsPerPage = parseInt(
                payload.products.per_page
            );
            currentState.totalproducts = parseInt(payload.products.total);
            currentState.productSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_SELECTED_PRODUCT(currentState, payload) {
            currentState.product = payload.product;
        }
    },
    actions: {
        async GET_PRODUCTS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/products";
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
                            context.commit("GET_PRODUCTS", result);
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
