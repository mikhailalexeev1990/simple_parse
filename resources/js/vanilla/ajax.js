import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export default {
    orderCreate(data) {
        return axios({
            method: 'POST',
            url: '/order/create',
            data,
        })
    },
}
