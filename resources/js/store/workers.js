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
        worker: null
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
        }
    },
    actions: {
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
