import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { SnackbarService, Vue3Snackbar } from 'vue3-snackbar';
import 'vue3-snackbar/styles';
import '../css/app.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Notifications from '@kyvg/vue3-notification';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    return pages[`./Pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .component('Link', Link)
      .component('Head', Head)
      .component("vue3-snackbar", Vue3Snackbar)
      .use(plugin)
      .use(SnackbarService)
      .use(Notifications)
      .mount(el);
  },
  title: title => `${title} - Finanx`,
});
