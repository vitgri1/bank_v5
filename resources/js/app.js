import 'bootstrap';
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (document.querySelector('.--client1--id')) {
    const select = document.querySelector('.--client1--id');
    select.addEventListener('change', _ => {
        axios.get(select.dataset.url + '?cl=' + select.value + '&acc=1')
            .then(res => {
                const bin = document.querySelector('.--test--selector1');
                bin.innerHTML = res.data.html;
            })
    })
}

if (document.querySelector('.--client2--id')) {
    const select = document.querySelector('.--client2--id');
    select.addEventListener('change', _ => {
        axios.get(select.dataset.url + '?cl=' + select.value + '&acc=2')
            .then(res => {
                const bin = document.querySelector('.--test--selector2');
                bin.innerHTML = res.data.html;
            })
    })
}