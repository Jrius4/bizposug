<template>
    <v-app style="height: 100px">
        <v-main class="my-4">
            <v-card elevation="4">
                <v-card-text>
                    <div class="feautures">
                        <div
                            class="feauture row just-center"
                            v-for="(item, itd) in items"
                            :key="`item-` + itd"
                        >
                            <a :href="(`settings/${item.name}`.toLowerCase()).replace(' ','-')">

                                <p class="text-center">
                                    {{ item.name }}
                                </p>
                            </a>
                        </div>
                    </div>
                </v-card-text>
            </v-card>
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "Settings",
    data: vm => {
        return {
            items: [
                { name: "Brands"},
                { name: "Product Groups" },
                { name: "Sizes" },
                { name: "Services" },
            ]
        };
    }
};
</script>
<style scoped>
.feautures {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    align-content: center;
}
.feautures a {
    text-decoration: none;
}
.feauture {
    padding: 0.5rem;
    transition: 0.3s ease-in-out;
    align-items: center;
    width: auto;
    height: auto;
    flex: 15%;
    max-width: 15%;
    padding: 0 4px;
}

.feauture p {
    font-size: 1rem;
    font-weight: 700;
    color: #004d40;
}
.feauture:hover {
    transform: scale(1.2);
}
.feauture:hover p {
}

.feauture img {
    margin-top: 8px;
    vertical-align: middle;
    align-self: center;
}

/* Responsive layout - makes a two column-layout instead of four columns */
@media (max-width: 800px) {
    .feauture {
        flex: 25%;
        max-width: 25%;
    }
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media (max-width: 600px) {
    .feauture {
        flex: 50%;
        max-width: 50%;
    }
}
</style>
