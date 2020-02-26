import Vue from 'vue'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/ja'
import App from './app.vue'
import LiffBody from './pages/LIFF/liff-body.vue'
import 'element-ui/lib/theme-chalk/index.css'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(ElementUI, { locale })

Vue.component('app', App)
Vue.component('liff-body', LiffBody)

const app = new Vue({})
app.$mount('#app')
