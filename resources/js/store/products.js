import Axios from "axios";

export default {
    namespaced: true,
    state: {
        products: [],
        productPagination: {
            page: 1,
            rowsPerPage: 15
        },
        totalproducts: 0,
        productSortRowsBy: "name",
        product: null,
        message: null,
        action_done: null,
        openWindow: 1,
    },
    mutations: {
        GET_PRODUCTS(currentState, payload) {
            currentState.products = payload.products.data;
            currentState.productPagination.page = parseInt(
                payload.products.current_page
            );

            currentState.productPagination.rowsPerPage = parseInt(
                payload.products.per_page
            );
            currentState.totalproducts = parseInt(payload.products.total);
            currentState.productSortRowsBy = payload.sortRowsBy || "name";
        },
        GET_PRODUCT(currentState, payload) {
            const { product, message } = payload;
            currentState.product = product;
            currentState.message = message;
        },
        GET_SELECTED_PRODUCT(currentState, payload) {
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
                currentState.product = null;
            }
            currentState.action_done = action_done || null;
        }
    },
    actions: {
        async SAVE_PRODUCT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {
                    const name = payload.name || "";
                    const prodID = payload.id || "";
                    const brand = payload.brand || "";
                    const size = payload.size || "";
                    const brand_id = payload.brand_id || "";
                    const size_id = payload.size_id || "";
                    const barcode = payload.barcode || "";
                    const stockType = payload.stockType || "";
                    const category = payload.category || "";
                    const prodgroup_id = payload.prodgroup_id || "";
                    const supplier_id = payload.supplier_id || "";
                    const company_name = payload.company_name || "";
                    const cost_price = payload.cost_price || "";
                    const wholesale_price = payload.wholesale_price || "";
                    const retailsale_price = payload.retailsale_price || "";
                    const quantity = payload.quantity || "";
                    const tax_percentage = payload.tax_percentage || "";
                    const description = payload.description || "";


                    let formData = new FormData();
                    let url = `api/save-product`;
                    if (prodID !== "") {
                        url = `/api/save-product/${prodID}`;
                    }



                    formData.append('name', name);
                    formData.append('category', category);
                    formData.append('brand', brand);
                    formData.append('quantity', quantity);
                    formData.append('size', size);
                    formData.append('brand_id', brand_id);
                    formData.append('size_id', size_id);
                    formData.append('barcode', barcode);
                    formData.append('stockType', stockType);
                    formData.append('prodgroup_id', prodgroup_id);
                    formData.append('supplier_id', supplier_id);
                    formData.append('company_name', company_name);
                    formData.append('cost_price', cost_price);
                    formData.append('wholesale_price', wholesale_price);
                    formData.append('retailsale_price', retailsale_price);
                    formData.append('tax_percentage', tax_percentage);
                    formData.append('description', description);


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
        async GET_PRODUCT_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                const productID = payload.id;
                const url = `/api/products/${productID}`;
                Axios.get(url, {
                    headers: {
                        Authorization: "Bearer " + context.rootState.token
                    }
                }).then(result => {
                    const {product,message} = result.data;
                    context.commit('GET_PRODUCT', {product,message});
                })
            });

        },
        async GET_PRODUCTS_ACTION(context, payload) {
            return new Promise((resolve, reject) => {
                if (context.rootGetters.loggedIn) {

                    const keywords = payload.val || "";
                    const page = payload.page || "";
                    const rowsPerPage = payload.rowsPerPage || 5;
                    const sortDesc = payload.sortDesc || null;
                    const sortRowsBy = payload.sortRowsBy || "";

                    const url = "/api/products";
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
