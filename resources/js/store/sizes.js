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
        size: null
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
        }
    },
    actions: {
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
