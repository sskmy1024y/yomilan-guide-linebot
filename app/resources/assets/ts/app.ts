import Vue from "vue";

require("./bootstrap");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from "./app.vue";
Vue.component("app", App);

import LiffBody from "./pages/LIFF/liff-body.vue";
Vue.component("liff-body", LiffBody);

const app = new Vue({});
app.$mount("#app");
