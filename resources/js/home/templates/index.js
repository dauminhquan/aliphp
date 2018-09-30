import Vue from 'vue'
import content from './content'
Vue.component('table-content',content)
const app = new Vue({
    el: '#page-content',
})