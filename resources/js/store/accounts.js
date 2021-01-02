import Axios from "axios";

export default {
    namespaced: true,
    state: {
        accounts: [],
        accountPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalaccounts: 0,
        accountSortRowsBy: "name",
        account: null,
        message: null,
        action_done: null,
        openWindow: 1,

        items: [],
        itemPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalitems: 0,
        itemSortRowsBy: "created_at",
        item: null,
    },
    mutations: {
        GET_ACCOUNTS(currentState, payload) {

            currentState.accounts = payload.accounts.data;
            currentState.accountPagination.page = parseInt(
                payload.accounts.current_page
            );

            currentState.accountPagination.rowsPerPage = parseInt(
                payload.accounts.per_page
            );
            currentState.totalaccounts = parseInt(payload.accounts.total);
            currentState.accountSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_SELECTED_DESCR_ACCOUNT(currentState, payload) {

            const { accounts } = payload;
            currentState.accounts = accounts || [];
        },
        GET_SELECTED_ACCOUNT(currentState, payload) {
            currentState.action_done = payload.action_done;
        },
        GET_OPEN_WINDOW(currentState, payload) {

            const { openWindow } = payload;
            if (typeof openWindow !== 'undefined') {
                currentState.openWindow = openWindow;
                if (openWindow === 1) currentState.account = null;
            }


        },
        HANDLE_EDITOR_STATE(currentState, payload) {
            const { action_done } = payload;
            if (action_done === 'clear') {
                currentState.account = null;
            }
            currentState.action_done = action_done || null;
        },
        GET_ACCOUNT(currentState, payload) {
            const { account, message } = payload;

            currentState.account = account;
            currentState.message = message;
        },
        GET_ACCOUNT_DETAILS(currentState, payload)
        {
            const { account, message } = payload;
            currentState.account = account || null;
            currentState.message = message || null;
        },
        GET_ITEMS(currentState, payload)
        {
            const { items } = payload;
            if (typeof items !== 'undefined')
            {
                currentState.items = payload.items.data;
                currentState.itemPagination.page = parseInt(
                    payload.items.current_page
                );

                currentState.itemPagination.rowsPerPage = parseInt(
                    payload.items.per_page
                );
                currentState.totalitems = parseInt(payload.items.total);
                currentState.itemSortRowsBy = payload.sortRowsBy || "created_at";
            }
            else {
                currentState.items = [];
                currentState.itemPagination.page = 1;

                currentState.itemPagination.rowsPerPage = 15;
                currentState.totalitems = 0;
                currentState.itemSortRowsBy = 'created_at';
            }

        }
    },
    actions: {
        async GET_ITEMS_ACTION(context, payload) {
            console.log({payload});
            return new Promise((resolve,reject) => {
                if (context.rootGetters.loggedIn)
                {

                    const keywords = payload.val || "";
                    const page = payload.page || 1;
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";
                    let url = `/api/financial-list`;

                    Axios.get(url,{
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
                    }).then((result) => {
                        const { items, sortRowsBy } = result.data;
                        resolve({ items, sortRowsBy })
                        context.commit('GET_ITEMS', { items, sortRowsBy });
                    }).catch((err) => {
                        console.log(err);
                        reject(err);
                    });
                }
                else {
                    const Error = 'Not Logged In';
                    reject(Error);
                }
            });

         },
        async GET_ACCOUNT_DETAILS_ACTION(context, payload)
        {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    let url = `/api/accounts-details`;
                    Axios.get(url, {
                        headers: {
                            Authorization: "Bearer " + context.rootState.token
                        }
                    }).then(result => {
                        const { account, message } = result.data;
                        resolve({ account, message })
                        context.commit('GET_ACCOUNT_DETAILS', { account, message });
                    });
                }
                else {
                    const Error = 'Not Logged In';
                    reject(Error);
                }
            });
        },
        async SAVE_AMOUNT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    let url = `/api/deposit`;

                    let details = payload.details || "";
                    let type = payload.type || "";
                    let balance = payload.balance || "";


                    let formData = new FormData();

                    formData.append('type', type);
                    formData.append('details', details);
                    formData.append('balance', balance);
                    Axios.post(url, formData, {
                        headers: {
                            Authorization: "Bearer " + context.rootState.token
                        }
                    }).then(result => {

                        const { account, message,errors } = result.data;
                        resolve({ account, message,errors })
                        context.commit('GET_ACCOUNT_DETAILS', { account, message,errors });
                    });
                }
                else {
                    const Error = 'Not Logged In';
                    reject(Error);
                }
            });
        },

        async GET_ACCOUNT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const accountID = payload.id;
                const url = `/api/accounts/${accountID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {account,message} = result.data;
                    context.commit('GET_ACCOUNT', {account,message});
                })
            });

        },
        async GET_ACCOUNTS_ACTION(context, payload) {
            new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/accounts";
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
                            context.commit("GET_ACCOUNTS", result);
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
