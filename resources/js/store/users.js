import Axios from "axios";

export default {
    namespaced: true,
    state: {
        users: [],
        userPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalusers: 0,
        userSortRowsBy: "name",
        user: null
    },
    mutations: {
        GET_USERS(currentState, payload) {
            currentState.users = payload.users.data;
            currentState.userPagination.page = parseInt(
                payload.users.current_page
            );

            currentState.userPagination.rowsPerPage = parseInt(
                payload.users.per_page
            );
            currentState.totalusers = parseInt(payload.users.total);
            currentState.userSortRowsBy = payload.sortRowsBy || "name";
        }
    },
    actions: {
        async GET_USERS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/users";
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
