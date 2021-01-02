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
        brand: null,
        message: null,
        action_done: null,
        openWindow: 1,
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
        },
        GET_SELECTED_DESCR_BRAND(currentState, payload) {

            const { brands } = payload;
            currentState.brands = brands || [];
        },
        GET_SELECTED_BRAND(currentState, payload) {
            currentState.action_done = payload.action_done;
        },
        GET_OPEN_WINDOW(currentState, payload) {

            const { openWindow } = payload;
            if (typeof openWindow !== 'undefined') {
                currentState.openWindow = openWindow;
                if (openWindow === 1) currentState.brand = null;
            }


        },
        HANDLE_EDITOR_STATE(currentState, payload) {
            const { action_done } = payload;
            if (action_done === 'clear') {
                currentState.brand = null;
            }
            currentState.action_done = action_done || null;
        },
        GET_BRAND(currentState, payload) {
            const { brand, message } = payload;

            currentState.brand = brand;
            currentState.message = message;
        },
    },
    actions: {
        async SAVE_BRAND_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";

                    const brandID = payload.id || "";


                    let formData = new FormData();
                    let url = `/api/save-brand`;
                    if (brandID !== "") {
                        url = `/api/save-brand/${brandID}`;
                    }



                    formData.append('name', name);


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
        async GET_BRAND_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const brandID = payload.id;
                const url = `/api/brands/${brandID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const { brand, message } = result.data;
                    context.commit('GET_BRAND', { brand, message });
                });
            });

        },
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
