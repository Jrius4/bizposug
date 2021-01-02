import Axios from "axios";
import Vue from "vue";
import Vuex from "vuex";

import usersModule from "./users";
import productsModule from "./products";
import brandsModule from "./brands";
import sizesModule from "./sizes";
import groupsModule from "./groups";
import cartModule from "./cart";
import paymentsModule from "./payments";
import workersModule from "./workers";
import suppliersModule from "./suppliers";
import transactionsModule from "./transactions";
import salesModule from "./sales";
import customersModule from "./customers";
import accountsModule from "./accounts";
import servicesModule from "./services";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        usersModule,
        productsModule,
        brandsModule,
        groupsModule,
        sizesModule,
        cartModule,
        paymentsModule,
        workersModule,
        suppliersModule,
        transactionsModule,
        salesModule,
        customersModule,
        accountsModule,
        servicesModule
    },
    state: {
        user: null,
        token: localStorage.getItem("access_token") || null
    },
    mutations: {
        GET_USER(currentState, payload) {
            currentState.user = payload;
        },
        GET_USER_TOKEN(currentState, payload) {
            const { remove_token, token } = payload;
            currentState.token = payload.token;
            if (remove_token === "remove") {
                currentState.token = null;
            }
        }
    },
    getters: {
        loggedIn(currentState) {
            return currentState.token !== null;
        }
    },
    actions: {
        async GET_USER_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.getters.loggedIn) {
                    const url = `/api/user`;
                    Axios.get(url, {
                        headers: {
                            Authorization: "Bearer " + context.state.token
                        }
                    })
                        .then(response => {
                            const user = response.data.user;
                            context.commit("GET_USER", user);
                            resolve(user);
                        })
                        .catch(err => {
                            console.log({ err });
                            reject(err);
                        });
                } else {
                    const err = "Not Logged In";
                    console.log({ err });
                    reject({ err });
                }
            });
        },
        async GET_USER_TOKEN_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (Object.keys(localStorage).includes("access_token")) {
                    const remove_token = payload.token || "";

                    const token = localStorage.access_token;
                    context.commit("GET_USER_TOKEN", { token, remove_token });
                    resolve(token);
                } else {
                    const error = "No Token";
                    console.log({ error });
                    reject(error);
                }
            });
        }
    }
});
