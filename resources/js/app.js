/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require("vue");

require("./bootstrap");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "add-transaction-form",
    require("./components/AddTransactionForm.vue").default
);

Vue.component(
    "add-lending-form",
    require("./components/AddLendingForm.vue").default
);

Vue.component(
    "add-borrowing-form",
    require("./components/AddBorrowingForm.vue").default
);

Vue.component("add-cash-form", require("./components/AddCashForm.vue").default);

Vue.component("people-page", require("./components/PeoplePage.vue").default);

Vue.component(
    "add-account-form",
    require("./components/AddAccountForm.vue").default
);

Vue.component("login-log", require("./components/LoginLog.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
