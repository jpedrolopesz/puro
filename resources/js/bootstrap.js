import axios from "axios";
window.axios = axios;

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
