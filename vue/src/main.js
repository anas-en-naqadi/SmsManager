import { createApp } from "vue";
import "./index.css";
import App from "./App.vue";
import store from "./store";
import router from "./router";
// Import Echo and initialize it
// import Pusher from "pusher-js";
// import Echo from "laravel-echo";

// Pusher.logToConsole = true;

const app = createApp(App);
// app.use(VueEcho, {
//   broadcaster: "pusher",
//   key: import.meta.env.VITE_PUSHER_APP_KEY,
//   cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//   forceTLS: true, // This is important for secure connections
//   encrypted: true,
//   logToStdout: true, // Enable Pusher debugging
// });
app.use(store);
app.use(router);


app.mount("#app");
