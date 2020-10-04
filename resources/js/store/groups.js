import Axios from "axios";

export default {
    namespaced: true,
    state: {
        groups: [],
        groupPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalgroups: 0,
        groupSortRowsBy: "name",
        group: null
    },
    mutations: {
        GET_GROUPS(currentState, payload) {
            currentState.groups = payload.groups.data;
            currentState.groupPagination.page = parseInt(
                payload.groups.current_page
            );

            currentState.groupPagination.rowsPerPage = parseInt(
                payload.groups.per_page
            );
            currentState.totalgroups = parseInt(payload.groups.total);
            currentState.groupSortRowsBy = payload.sortRowsBy || "name";
        }
    },
    actions: {
        async GET_GROUPS_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/groups";
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
                            context.commit("GET_GROUPS", result);
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
