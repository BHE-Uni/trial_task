import { createApp } from 'vue';
import AppComponent from './components/AppComponent.vue';
import router from './router';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';

const app = createApp(AppComponent);
app.config.globalProperties.$appUrl = window.APP_URL;

app.use(router);
app.use(Antd);

app.mount('#app');