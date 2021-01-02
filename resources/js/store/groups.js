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
        group: null,
        message: null,
        action_done: null,
        openWindow: 1,
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
        },
        GET_SELECTED_DESCR_GROUP(currentState, payload) {
            const { groups } = payload;
            if ((typeof groups) !== 'undefined' ) {
                currentState.groups.push(groups)
            }

        },
        GET_SELECTED_GROUP(currentState, payload) {
            currentState.action_done = payload.action_done;
        },
        GET_OPEN_WINDOW(currentState, payload) {

            const { openWindow } = payload;
            if (typeof openWindow !== 'undefined') {
                currentState.openWindow = openWindow;
                if (openWindow === 1) currentState.group = null;
            }


        },
        HANDLE_EDITOR_STATE(currentState, payload) {
            const { action_done } = payload;
            if (action_done === 'clear') {
                currentState.group = null;
            }
            currentState.action_done = action_done || null;
        },
        GET_GROUP(currentState, payload) {
            const { group, message } = payload;

            currentState.group = group;
            currentState.message = message;
        },
    },
    actions: {
        async SAVE_GROUP_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";

                    const groupID = payload.id || "";


                    let formData = new FormData();
                    let url = `/api/save-group`;
                    if (groupID !== "") {
                        url = `/api/save-group/${groupID}`;
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
        async GET_GROUP_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const groupID = payload.id;
                const url = `/api/groups/${groupID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {group,message} = result.data;
                    context.commit('GET_GROUP', {group,message});
                })
            });

        },
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
