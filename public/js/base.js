function getJson(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        var status = xhr.status;
        if (status === 200) {
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
}

function setCookie(name,value,days=30) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function getAllCookies(assoc = false) {
    var ca = document.cookie.split(";");
    var cookies = {};
    for (var i=0; i<ca.length; i++){
        var pair = ca[i].split("=");
        cookies[(pair[0]+'').trim()] = unescape(pair[1]);
    }

    return assoc ? Object.entries(cookies) : cookies;
}

function unsetCookie(name) {
    setCookie(name,"",-1);
}

new Vue({
    delimiters: ['${', '}'],
    el: '#app',
    components: {
        buyProductButton,
        cartTable
    },
    data: function () {
        return {
            total_ordered: 0,
            cart_items_prefix: 'cart_product_'
        }
    },
    created: function () {
        this.updateOrderedCount();
    },
    methods: {
        updateOrderedCount: function () {
            var cookies = getAllCookies(true);
            var totalOrdered = 0;
            for (var i = 0; i < cookies.length; i++){
                var key = cookies[i][0];
                if (key.startsWith(this.cart_items_prefix)) {
                    var cartProduct = JSON.parse(cookies[i][1]);
                    if (cartProduct !== undefined && cartProduct != null) {
                        totalOrdered += cartProduct.count;
                    }
                }
            }
            this.total_ordered = totalOrdered;
        }
    },
});