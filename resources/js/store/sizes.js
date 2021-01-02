import Axios from "axios";

export default {
    namespaced: true,
    state: {
        sizes: [],
        sizePagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalsizes: 0,
        sizeSortRowsBy: "name",
        size: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_SIZES(currentState, payload) {
            currentState.sizes = payload.sizes.data;
            currentState.sizePagination.page = parseInt(
                payload.sizes.current_page
            );

            currentState.sizePagination.rowsPerPage = parseInt(
                payload.sizes.per_page
            );
            currentState.totalsizes = parseInt(payload.sizes.total);
            currentState.sizeSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_SELECTED_DESCR_SIZE(currentState, payload) {
            const { sizes } = payload;
            currentState.sizes = sizes || [];
        },
        GET_SELECTED_SIZE(currentState, payload) {
            currentState.action_done = payload.action_done;
        },
        GET_OPEN_WINDOW(currentState, payload) {

            const { openWindow } = payload;
            if (typeof openWindow !== 'undefined') {
                currentState.openWindow = openWindow;
                if (openWindow === 1) currentState.size = null;
            }


        },
        HANDLE_EDITOR_STATE(currentState, payload) {
            const { action_done } = payload;
            if (action_done === 'clear') {
                currentState.size = null;
            }
            currentState.action_done = action_done || null;
        },
        GET_SIZE(currentState, payload) {
            const { size, message } = payload;

            currentState.size = size;
            currentState.message = message;
        },
    },
    actions: {
        async SAVE_SIZE_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";

                    const sizeID = payload.id || "";


                    let formData = new FormData();
                    let url = `/api/save-size`;
                    if (sizeID !== "") {
                        url = `/api/save-size/${sizeID}`;
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
                        console.log({result})
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
        async GET_SIZE_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const sizeID = payload.id;
                const url = `/api/sizes/${sizeID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    console.log({result})
                    const {size,message} = result.data;
                    context.commit('GET_SIZE', {size,message});
                })
            });

        },
        async GET_SIZES_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || 1;
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/sizes";
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
                            context.commit("GET_SIZES", result);
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
