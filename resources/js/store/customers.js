import Axios from "axios";

export default {
    namespaced: true,
    state: {
        customers: [],
        customerPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalcustomers: 0,
        customerSortRowsBy: "name",
        customer: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_CUSTOMERS(currentState, payload) {
            currentState.customers = payload.customers.data;
            currentState.customerPagination.page = parseInt(
                payload.customers.current_page
            );

            currentState.customerPagination.rowsPerPage = parseInt(
                payload.customers.per_page
            );
            currentState.totalcustomers = parseInt(payload.customers.total);
            currentState.customerSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_CUSTOMER(currentState, payload) {
            const { customer, message } = payload;
            currentState.customer = customer;
            currentState.message = message;
        },
        GET_SELECTED_CUSTOMER(currentState, payload) {
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
                currentState.customer = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async SAVE_CUSTOMER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";
                    const address = payload.address || "";
                    const salary = payload.salary || "";
                    const role = payload.role || "";
                    const d_o_b = payload.d_o_b || "";
                    const gender = payload.gender || "";
                    const contact = payload.contact || "";
                    const company = payload.company || "";
                    const balance = payload.balance || "";
                    const biograpy = payload.biograpy || "";
                    const customerID = payload.id || "";


                    let formData = new FormData();
                    let url = `api/save-customer`;
                    if (customerID !== "") {
                        url = `/api/save-customer/${customerID}`;
                    }



                    formData.append('name', name);
                    formData.append('address', address);
                    formData.append('company', company);
                    formData.append('balance', balance);
                    formData.append('biograpy', biograpy);
                    formData.append('salary', salary);
                    formData.append('contact', contact);
                    formData.append('role', role);
                    formData.append('gender', gender);
                    formData.append('d_o_b', d_o_b);


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
        async GET_CUSTOMER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const customerID = payload.id;
                const url = `/api/customers/${customerID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    console.log({ result });
                    const {customer,message} = result.data;
                    context.commit('GET_CUSTOMER', { customer, message });
                    resolve({ customer, message });
                }).catch(err => {
                    reject({ err });
                    console.log({err})
                })
            });

        },
        async GET_CUSTOMERS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/customers";
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
                            context.commit("GET_CUSTOMERS", result);
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
