var buyProductButton = Vue.component('buy-product', {
    data: function () {
        return {
            count: 0
        }
    },
    props: {
        pid: {
            type: String,
            required: true
        }
    },
    created: function () {
        var productKey = this.$root.cart_items_prefix+this.pid;
        var cartProduct = JSON.parse(window.localStorage.getItem(productKey));
        if (cartProduct !== undefined && cartProduct != null) {
            this.count = cartProduct.count;
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
        }
    },
    template: '<button @click="buyProduct"><slot></slot></button>'
});