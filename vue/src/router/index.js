import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import DefaultLayout from "../components/DefaultLayout.vue";
import Dashboard from "../views/admin/Dashboard.vue";
import Login from "../views/auth/Login.vue";
import Profile from "../views/profile/Profile.vue";
import Drafts from "../views/profile/Drafts.vue";
import sendSms from "../views/sms/sendSms.vue";
import smsSettings from "../views/sms/SmsSettings.vue";
import Register from "../views/auth/Register.vue";
import AuthBlade from "../components/AuthBlade.vue";
import NotifList from "../views/admin/NotifList.vue";
import ServiceList from "../views/admin/SmsServiceList.vue";
import NotFound from "../components/NotFound.vue";
import ContactList from "../views/admin/ContactList.vue";
import AddContact from "../views/contact/ContactForm.vue";
import SendToGroup from "../views/contact/SendToGroup.vue";
import SmsHome from "../views/sms/SmsHome.vue";
import Home from "../views/home/HomePage.vue";
import Calendar from "../views/calendar/Calendar.vue";
import EmailVerif from "../views/auth/EmailVerificaiton.vue";
import codeVerif from "../views/auth/CodeVerification.vue";
import passUpdate from "../views/auth/PasswordReset.vue";

const routes = [
  {
    path: "/dashboard",
    redirect: "/dashboard",
    component: DefaultLayout,
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: "/profile",
        name: "Profile",
        component: Profile,
      },
      { path: "/dashboard", name: "Dashboard", component: Dashboard },
      { path: "/sms/send-one", name: "sendSms", component: sendSms },
      { path: "/sms/send-one/:id", name: "edit_sms", component: sendSms },

      { path: "/sms/settings", name: "smsSettings", component: smsSettings },
      {
        path: "/service/update/:id",
        name: "smsupdate",
        component: smsSettings,
      },
      { path: "/contacts", name: "contacts", component: ContactList },
      { path: "/contact/add", name: "addContact", component: AddContact },
      {
        path: "/contact/update/:id",
        name: "updateContact",
        component: AddContact,
      },
      {
        path: "/sms/send-group/:id",
        name: "edit_group",
        component: SendToGroup,
      },

      { path: "/sms/send-group", name: "sendTogroup", component: SendToGroup },
      { path: "/sms/home", name: "home", component: SmsHome },
      { path: "/sms/calendar", name: "calendar", component: Calendar },
      { path: "/sms/drafts", name: "drafts", component: Drafts },

      { path: "/sms/notifications", name: "notif", component: NotifList },
      { path: "/sms/services", name: "service", component: ServiceList },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    component: AuthBlade,
    meta: {
      isGuest: true,
    },
    children: [
      {
        path: "/login",
        name: "login",
        component: Login,
      },
      {
        path: "/register",
        name: "register",
        component: Register,
      },
      {
        path: "/email/verification",
        name: "email",
        component: EmailVerif,
      },
      {
        path: "/code/verification",
        name: "code",
        component: codeVerif,
      },
      {
        path: "/password/update",
        name: "pass_reset",
        component: passUpdate,
      },
    ],
  },
  {
    path: "/:pathMatch(.*)",
    component: NotFound,
    name: "notFound",
    meta: {
      requiresAuth: false,
      title: "Page Not Found",
    },
  },
  {
    path: "/",
    component: Home,
    name: "homepage",
    meta: {
      isGuest: true,
      title: "Home",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !sessionStorage.getItem("TOKEN")) {
    next({ name: "homepage" });
  } else if (sessionStorage.getItem("TOKEN") && to.meta.isGuest) {
    next({ name: "Dashboard" });
  } else if (sessionStorage.getItem("TOKEN") && to.name === "login") {
    next({ name: "Dashboard" });
  } else {
    next();
  }
});

export default router;
