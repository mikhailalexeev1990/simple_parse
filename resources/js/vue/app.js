import Vue from 'vue';
import VModal from 'vue-js-modal';
import VueTheMask from 'vue-the-mask';
import Test from './Test';

Vue.use(VModal, {dialog: true});
Vue.use(VueTheMask);

document.addEventListener("DOMContentLoaded", () => {
    let el = document.querySelector('#app');
    console.log(el);
    if (el) {
        new Vue({
            el,
            components: {
                Test
            },
            template: '<Test/>'
        });
    }
});
