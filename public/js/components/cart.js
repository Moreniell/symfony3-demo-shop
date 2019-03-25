var cartTable = Vue.component('cart', {
    data: function () {
        return {
            products: [],
            total_sum: 0,
            isPreloaderHidden: false
        }
    },
    props: {
        action: {
            type: String,
            required: true
        }
    },
    created: function () {
        if (this.$root.total_ordered < 1) {
            this.isPreloaderHidden = true;
            return;
        }
        var vm = this;
        var cookies = getAllCookies(true);
        for (var i = 0; i < cookies.length; i++){
            var key = cookies[i][0];
            if (key.startsWith(this.$root.cart_items_prefix)) {
                var cartProduct = JSON.parse(cookies[i][1]);
                if (cartProduct !== undefined && cartProduct != null) {
                    (function (cartProduct, i) {
                        getJson('/api/v1/products/'+cartProduct.pid, function (error, product) {
                            vm.products.push({
                                id: product.id,
                                category: product.category.name,
                                name: product.name,
                                price: product.price,
                                ordered_count: cartProduct.count
                            });
                            if (i === cookies.length-1) {
                                vm.isPreloaderHidden = true;
                            }
                        });
                    }(cartProduct, i));
                }
            }
        }
    },
    methods: {
        buyProduct: function () {
            this.count++;
            var productKey = this.$root.cart_items_prefix+this.pid;
            setCookie(productKey, JSON.stringify({
                pid: this.pid,
                count: this.count
            }));

            this.$root.updateOrderedCount();
        },
        removeFromCart: function(product) {
            var index = this.products.indexOf(product);
            if (index > -1) {
                var productKey = this.$root.cart_items_prefix+product.id;
                unsetCookie(productKey);
                this.products.splice(index, 1);
            }
        },
        placeOrder: function () {
            window.location.href = this.action;
        }
    },
    watch: {
        products: {
            handler: function () {
                var totalSum = 0;
                for (var i = 0; i < this.products.length; i++) {
                    var productKey = this.$root.cart_items_prefix+this.products[i].id;
                    var orderedCount = this.products[i].ordered_count;
                    if (orderedCount < 1) {
                        orderedCount = 1;
                    }
                    setCookie(productKey, JSON.stringify({
                        pid: this.products[i].id,
                        count: typeof orderedCount == "string" ? parseInt(orderedCount) : orderedCount
                    }));
                    totalSum += this.products[i].price * orderedCount;
                }
                this.total_sum = totalSum;
                this.$root.updateOrderedCount();
            },
            deep: true
        }
    },
    template:
        '<div class="cart-table">' +
        '   <div class="preloader" v-bind:class="{ hidden: isPreloaderHidden }">' +
        '       <div class="preloader__status"></div>' +
        '   </div>' +
        '   <div v-if="products.length > 0">' +
        '      <table>' +
        '         <tr v-for="product in products">' +
        '             <td v-html="product.name"></td>' +
        '             <td><input type="number" min="1" v-model="product.ordered_count"></td>' +
        '             <td><button @click="removeFromCart(product)">X</button></td>' +
        '         </tr>' +
        '       </table>' +
        '       <p>Total sum: <span v-html="total_sum"></span> UAH</p>' +
        '       <button @click="placeOrder"><slot></slot></button>' +
        '   </div>' +
        '   <div v-else>' +
        '      Корзина покупок пуста.' +
        '   </div>' +
        '</div>'
});