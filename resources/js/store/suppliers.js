import Axios from "axios";

export default {
    namespaced: true,
    state: {
        suppliers: [],
        supplierPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalsuppliers: 0,
        supplierSortRowsBy: "name",
        supplier: null
    },
    mutations: {
        GET_SUPPLIERS(currentState, payload) {
            currentState.suppliers = payload.suppliers.data;
            currentState.supplierPagination.page = parseInt(
                payload.suppliers.current_page
            );

            currentState.supplierPagination.rowsPerPage = parseInt(
                payload.suppliers.per_page
            );
            currentState.totalsuppliers = parseInt(payload.suppliers.total);
            currentState.supplierSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_SELECTED_DESCR_SUPPLIER(currentState, payload) {
            const { supplier } = payload;
            if ((typeof supplier) !== 'undefined' ) {
                currentState.suppliers.push(supplier)
            }

        }
    },
    actions: {
        async GET_SUPPLIERS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/suppliers";
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
                            context.commit("GET_SUPPLIERS", result);
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
