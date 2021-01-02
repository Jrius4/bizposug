import Axios from "axios";

export default {
    namespaced: true,
    state: {
        services: [],
        servicePagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalservices: 0,
        serviceSortRowsBy: "created_at",
        service: null,
        type: 'daily',
        service: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_SERVICES(currentState, payload) {
            currentState.services = payload.services.data;
            currentState.servicePagination.page = parseInt(
                payload.services.current_page
            );

            currentState.servicePagination.rowsPerPage = parseInt(
                payload.services.per_page
            );
            currentState.totalservices = parseInt(payload.services.total);
            currentState.serviceSortRowsBy = payload.sortRowsBy || "created_at";
            currentState.type = payload.queryType || "daily"
        },
        GET_SERVICE(currentState, payload) {
            const { service, message } = payload;
            currentState.service = service;
            currentState.message = message;
        },
        GET_SELECTED_SERVICE(currentState, payload) {
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
                currentState.service = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async GET_SERVICE_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const serviceID = payload.id;
                const url = `/api/services/${serviceID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {service,message} = result.data;
                    context.commit('GET_SERVICE', {service,message});
                })
            });

        },
        async GET_SERVICES_ACTION(context, payload) {
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
                    let url = "/api/services";
                    if (data_type) {
                     url = "/api/summary-services"
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
                            context.commit("GET_SERVICES", result);
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

        async SAVE_SERVICE_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    console.log({payload})
                    const serviceID = payload.id || "";
                    const customer_id = payload.customer_id || "";
                    const items = payload.items || "";
                    const client_details = payload.client_details || "";
                    const cost = payload.cost || "";
                    const prevBalance = payload.prevBalance || "";
                    const profit = payload.profit || "";
                    const item_amount = payload.item_amount || "";
                    const type_service = payload.type_service || "";
                    const serviceName = payload.serviceName || "";
                    const description = payload.description || "";
                    const received_by = payload.received_by || "";
                    const balance = payload.balance || "";
                    const amountPaid = payload.amountPaid || "";
                    const amountAgreed = payload.amountAgreed || "";



                    let formData = new FormData();
                    let url = `/api/store-service`;
                    if (serviceID !== "") {
                        url = `/api/store-service/${serviceID}`;
                    }



                    formData.append('items',  JSON.stringify(items));
                    formData.append('balance', balance);
                    formData.append('customer_id', customer_id);
                    formData.append('type_service', type_service);
                    formData.append('serviceName', serviceName);
                    formData.append('description', description);
                    formData.append('received_by', received_by);
                    formData.append('amountPaid', amountPaid);
                    formData.append('amountAgreed', amountAgreed);
                    formData.append('client_details', client_details);
                    formData.append('prevBalance', prevBalance);
                    formData.append('cost', cost);
                    formData.append('profit', profit);
                    formData.append('item_amount', item_amount);



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
