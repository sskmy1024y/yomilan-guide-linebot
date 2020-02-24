import Vue from "vue";
import App from "./app.vue";

require("./bootstrap");

window.Vue = require("vue");
Vue.component("app", App);

const app = new Vue({
    el: "#app"
});
