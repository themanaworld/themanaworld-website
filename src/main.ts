import Vue from "vue"
import App from "./App.vue"
import router from "./router"
import VS2 from "vue-script2"
import "normalize.css"

Vue.config.productionTip = false
Vue.use(VS2)

new Vue({
	router,
	render: h => h(App)
}).$mount("#app")
