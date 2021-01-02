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
        supplier: null,
        message: null,
        action_done: null,
        openWindow: 1,
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

        },
        GET_SUPPLIER(currentState, payload) {
            const { supplier, message } = payload;
            currentState.supplier = supplier;
            currentState.message = message;
        },
        GET_SELECTED_SUPPLIER(currentState, payload) {
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
                currentState.supplier = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async SAVE_SUPPLIER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";
                    const address = payload.address || "";
                    const contact = payload.contact || "";
                    const company = payload.company || "";
                    const salary = payload.salary || "";
                    const balance = payload.balance || "";
                    const biograpy = payload.biograpy || "";
                    const suppID = payload.id || "";


                    let formData = new FormData();
                    let url = `api/save-supplier`;
                    if (suppID !== "") {
                        url = `/api/save-supplier/${suppID}`;
                    }



                    formData.append('name', name);
                    formData.append('address', address);
                    formData.append('company', company);
                    formData.append('salary', salary);
                    formData.append('balance', balance);
                    formData.append('biograpy', biograpy);
                    formData.append('contact', contact);


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
        async GET_SUPPLIER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const supplierID = payload.id;
                const url = `/api/suppliers/${supplierID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {supplier,message} = result.data;
                    context.commit('GET_SUPPLIER', {supplier,message});
                })
            });

        },
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
