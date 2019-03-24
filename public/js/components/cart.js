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
        var vm = this;
        for (var i = 0; i < window.localStorage.length; i++){
            var key = window.localStorage.key(i);
            if (key.startsWith(this.$root.cart_items_prefix)) {
                var cartProduct = JSON.parse(window.localStorage.getItem(key));
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
                            if (i === window.localStorage.length-1) {
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
            window.localStorage.setItem(productKey, JSON.stringify({
                pid: this.pid,
                count: this.count
            }));

            this.$root.updateOrderedCount();
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
                    window.localStorage.setItem(productKey, JSON.stringify({
                        pid: this.products[i].id,
                        count: this.products[i].ordered_count
                    }));
                    totalSum += this.products[i].price * this.products[i].ordered_count;
                }
                this.total_sum = totalSum;
                this.$root.updateOrderedCount();
            },
            deep: true
        }
    },
    template:
        '<div class="cart-table">' +
        '   <div class="preloader" v-bind:class="{ hidden: isPreloaderHidden }"><div class="preloader__status"></div></div>' +
        '   <table>' +
        '      <tr v-for="product in products">' +
        '          <td v-html="product.name"></td><td><input type="number" v-model="product.ordered_count"></td><td></td>' +
        '      </tr>' +
        '   </table>' +
        '   <p>Total sum: <span v-html="total_sum"></span> UAH</p>' +
        '   <button @click="placeOrder"><slot></slot></button>' +
        '</div>'
});