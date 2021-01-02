import Axios from "axios";

export default {
    namespaced: true,
    state: {
        workers: [],
        workerPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalworkers: 0,
        workerSortRowsBy: "name",
        worker: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_WORKERS(currentState, payload) {
            currentState.workers = payload.workers.data;
            currentState.workerPagination.page = parseInt(
                payload.workers.current_page
            );

            currentState.workerPagination.rowsPerPage = parseInt(
                payload.workers.per_page
            );
            currentState.totalworkers = parseInt(payload.workers.total);
            currentState.workerSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_WORKER(currentState, payload) {
            const { worker, message } = payload;
            currentState.worker = worker;
            currentState.message = message;
        },
        GET_SELECTED_WORKER(currentState, payload) {
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
                currentState.worker = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async SAVE_WORKER_ACTION(context, payload) {
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
                    const workerID = payload.id || "";


                    let formData = new FormData();
                    let url = `api/save-worker`;
                    if (workerID !== "") {
                        url = `/api/save-worker/${workerID}`;
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
        async GET_WORKER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const workerID = payload.id;
                const url = `/api/workers/${workerID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {worker,message} = result.data;
                    context.commit('GET_WORKER', {worker,message});
                })
            });

        },
        async GET_WORKERS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/workers";
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
                            context.commit("GET_WORKERS", result);
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
