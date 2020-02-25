import Vue from "vue";
import App from "./app.vue";

require("./bootstrap");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("app", App);

const app = new Vue({});
app.$mount("#app");
