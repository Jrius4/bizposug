<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <v-row align="baseline" justify="space-around">
          <v-col cols="12" lg="8" md="8">
            <v-card>
              <v-card-title> Products </v-card-title>
              <v-card-text>
                <v-container fluid>
                  <v-row dense>
                    <v-col
                      v-for="(card, ind) in items"
                      :key="`item-${ind}`"
                      cols="12"
                      lg="4"
                      md="4"
                      sm="6"
                      xl="4"
                    >
                      <v-card>
                        <v-img
                          :src="card.avatar"
                          class="white--text align-end"
                          gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                          height="200px"
                        >
                          <v-card-title v-text="card.name"></v-card-title>
                        </v-img>

                        <v-card-actions>
                          <span
                            class="teal--text text--darken-4 font-weight-medium"
                          >
                            {{ card.cost | currency }}
                          </span>
                          <v-spacer></v-spacer>
                          <v-btn
                            color="teal darken-4"
                            dark
                            small
                            @click="addCart(card)"
                          >
                            <v-icon>mdi-cart-plus</v-icon>
                            Add to cart
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" lg="4" md="4" sm="12">
            <v-card elevation="4">
              <v-card-title> In Cart </v-card-title>
              <v-card-text>
                <v-data-table
                  dense
                  item-key="name"
                  v-model="selectedinCartItems"
                  :headers="headers"
                  :items="inCartItems"
                >
                  <template v-slot:item.avatar="{ item }">
                    <v-avatar tile size="50" class="elevation-4 shadow-md">
                      <img :src="`${item.avatar}`" alt="" />
                    </v-avatar>
                  </template>
                  <template v-slot:item.actions="{ item }">
                    <div class="row d-flex">
                      <v-btn icon color="blue" small @click="addQty(item)">
                        <v-icon small> mdi-plus </v-icon>
                      </v-btn>
                      <v-btn icon color="blue" small @click="minusQty(item)">
                        <v-icon small> mdi-minus </v-icon>
                      </v-btn>
                      <v-btn icon color="red" small @click="removeItem(item)">
                        <v-icon small> mdi-cart-off </v-icon>
                      </v-btn>
                    </div>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "CashOut",
  data: () => {
    return {
      selectedinCartItems: [],
      inCartItems: [],
      headers: [
        { text: "Avatar", align: "left", value: "avatar", sortable: false },
        { text: "Name", align: "left", value: "name", sortable: true },
        { text: "QTY", align: "left", value: "qty", sortable: true },
        { text: "Amount", align: "left", value: "amount", sortable: true },
        { text: "Actions", align: "left", value: "actions", sortable: false },
      ],
    };
  },
  computed: {
    items() {
      let items = [];
      for (let index = 0; index < 200; index++) {
        const element = {
          name: `item#${index + 1}`,
          avatar: `/products/product_avatar_${index % 3}.jpg`,
          cost: 150 * (index + 1) * 8550,
        };
        items.push(element);
      }
      return items;
    },
  },
  methods: {
    addCart(item) {
      const index = this.inCartItems.findIndex((p) => p.name === item.name);
      if (index !== -1) {
        Object.assign(item, { qty: 1 + (item.qty || 0) });
        this.$set(this.inCartItems, index, item);
      } else {
        Object.assign(item, { qty: 1 });

        this.inCartItems.push(item);
      }
    },
    addQty(item) {
      const index = this.inCartItems.findIndex((p) => p.name === item.name);
      if (index !== -1) {
        Object.assign(item, { qty: 1 + (item.qty || 0) });
        this.$set(this.inCartItems, index, item);
      }
    },
    minusQty(item) {
      const index = this.inCartItems.findIndex((p) => p.name === item.name);
      if (index !== -1) {
        Object.assign(item, { qty: (item.qty || 0) - 1 });
        if (item.qty === 0 || item.qty < 0) {
          this.inCartItems.splice(index, 1);
        } else {
          this.$set(this.inCartItems, index, item);
        }
      }
    },
    removeItem(item) {
      const index = this.inCartItems.findIndex((p) => p.name === item.name);
      if (index !== -1) {
        this.inCartItems.splice(index, 1);
      }
    },
  },
  filters: {
    currency(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "UGX",
      }).format(value);
    },
  },
};
</script>

<style>
.namep {
  justify-content: space-around;
}
</style>
