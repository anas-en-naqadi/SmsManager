import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      token: sessionStorage.getItem("TOKEN"),
      data: {},
    },
    events: {
      data: {},
      loading: false,
    },
    notification: {
      show: false,
      message: null,
      type: null,
    },
    dashboard: {
      data: {},
      loading: false,
    },
    contacts: {
      data: {},
      loading: true,
    },
    event: {},
    smStatusNotifications: {},
  },
  actions: {
    setReadAt({ commit }, now) {
      return axiosClient.post("/setReadAt", now);
    },
    deleteEvent({ commit }, { type, id }) {
      const set = {
        type: type,
        id: id,
      };
      return axiosClient
        .post("/deleteEvent", set)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    getEventById({ commit }, { type, id }) {
      const set = {
        type: type,
        id: id,
      };
      return axiosClient
        .post("/getEvent/", set)
        .then((res) => {
          commit("setEvent", res.data);
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },

    async getAllEvents({ commit }) {
      return await axiosClient
        .get("/getSmsEvents")
        .then((res) => {
          commit("setSmsEvents", res.data.events);
          return res ;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    updateSmsEvents({ commit }, event) {
      return axiosClient
        .put("/updateSmsEvent", event)
        .then((res) => {
          if (res.data.status === "success") {

            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
            commit("setSmsEvents", res.data.events);
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }

          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    deleteAllNotifiable({ commit }) {
      return axiosClient
        .delete("/deleteAllNotifiable")
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
   async getsmStatusNotifications({ commit }) {
      return await axiosClient
        .get("/smStatusNotifications")
        .then((res) => commit("setSmStatusNotifications", res.data));
    },
    scheduleNotifGroup({ commit }, message) {
      return axiosClient
        .post("/scheduleNotifGroup", message)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    updateDraft({ commit }, { id, message }) {
      return axiosClient
        .put(`/draft/${id}`, message)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oopps something went wrong ",
            type: "danger",
          });
          return error;
        });
    },
    storeDrafts({ commit }, message) {
      return axiosClient
        .post("/draft", message)
        .then((res) => {

          return res;
        })
        .catch((error) => {

          return error;
        });
    },
    scheduleNotif({ commit }, message) {
      return axiosClient
        .post("/scheduleNotif", message)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    sendByCat({ commit }, message) {
      return axiosClient
        .post("/sendToCategory", message)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    sendCsvFile({ commit }, file) {
      return axiosClient
        .post("/contacts/csv", file, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res ;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    exportCsvNotification({ commit }) {
      return axiosClient
        .get("/exportNotification")
        .then((res) => {
          if (res.data.csv) {
            const csvBase64 = res.data.csv;
            const csvData = atob(csvBase64); // Decode base64 string
            const blob = new Blob([csvData], { type: "text/csv" });
            const filename = "Notification.csv";
            if (window.navigator.msSaveBlob) {
              // For IE11
              window.navigator.msSaveBlob(blob, filename);
            } else {
              // For other browsers
              const link = document.createElement("a");
              link.href = window.URL.createObjectURL(blob);
              link.download = filename;
              link.click();
            }
          }
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
   async getDrafts({ commit }, { url = null } = {}) {
      url = url || "/draft";
      return await axiosClient.get(url).then((res) => res);
    },
   async getAllNotificationGroup({ commit }) {
      return await axiosClient.get("/getNotifGroup").then((res) => res);
    },
    async getTwilioPhoneNumber({ commit }) {
      return await axiosClient.get("/getTwilioNumber").then((res) => res);
    },
    updateContact({ commit }, { contact, id }) {
      return axiosClient
        .put(`/updateContact/${id}`, contact)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
   async getContactDetails({ commit }, id) {
      return await axiosClient
        .get(`/getContact/${id}`)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    async getDraftDetails({ commit }, id) {
      return await axiosClient
        .get(`/draft/${id}`)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },

    sendTogroup({ commit }, { contacts, type }) {
      let service =
        type === "Vonage" ? "/sendToVonageGroup/" : "/sendToTwilioGroup/";
      return axiosClient
        .post(`${service}`, contacts)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: "Oops, something went wrong!",
              type: "danger",
            });
          }

          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });

          return error;
        });
    },
    deleteContact({ commit }, id) {
      return axiosClient
        .delete("/destroyContact/" + id)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    deleteDraft({ commit }, id) {
      return axiosClient
        .delete(`/draft/${id}`)
        .then((res) => {
         
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    saveContact({ commit }, contact) {
      return axiosClient
        .post("/saveContact", contact)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    sendVerificationCode({ commit }, email) {
      return axiosClient
        .post("/send-email", { email })
        .then((res) => {
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    updatePassword({ commit }, data) {
      return axiosClient
        .post("/reset-pass", data)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    checkVerificationCode({ commit }, code) {
      return axiosClient
        .post("/check-code", code)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    resendEmailVerification({ commit }, code) {
      return axiosClient
        .post("/resend-code", code)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    async getAllContacts({ commit }, { url = null } = {}) {
      url = url || "/allContacts";
      return await axiosClient
        .get(url)
        .then((res) => {
          console.log(res);
          commit("setContacts", res.data);
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    updateService({ commit }, { client, id, type }) {
      const lien = type === "Vonage" ? "/updateVonage/" : "/updateTwilio/";
      return axiosClient
        .put(`${lien}+${id}`, client)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    async getServiceDetails({ commit }, id) {
      return await axiosClient
        .get(`/getService/${id}`)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    deleteNotif({ commit }, id) {
      return axiosClient
        .delete(`/notification/${id}`)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    deleteService({ commit }, id) {
      return axiosClient
        .delete(`/service/${id}`)
        .then((res) => {
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops, something went wrong!",
            type: "danger",
          });
          return error;
        });
    },
    async getAllNotifications() {
      return await axiosClient
        .get("/allNotifications")
        .then((res) => res)
        .catch((error) => error);
    },
   async getAllNotificationss({ commit }, { url = null } = {}) {
      url = url || "/allNotificationss";
      return await axiosClient
        .get(url)
        .then((res) => res)
        .catch((error) => error);
    },
    async getAllServices() {
      return await axiosClient
        .get("/allServices")
        .then((res) => res)
        .catch((error) => error);
    },
    async getUser({ commit }) {
      return await axiosClient
        .get("/getUser")
        .then((res) => {
          if (res.status === 200) {
            commit("setUser", res.data);
          } else {
          }
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    async getDashboard({ commit }) {
      return await axiosClient
        .get("/getDashboard")
        .then((res) => {
          commit("setDashboard", res.data);
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    logout({ commit }) {
      return axiosClient
        .post("/logout")
        .then((res) => {
          commit("deleteUser");
          commit("setNotification", {
            message: res.data.message,
            type: "success",
          });
          sessionStorage.removeItem("TOKEN");
          return res;
        })
        .catch((error) => {
          return error;
        });
    },

    login({ commit, dispatch }, user) {
      return axiosClient
        .post("/login", user)
        .then(async (res) => {
          if (res.data.status === "success" && res.status === 200)
            sessionStorage.setItem("TOKEN", res.data.token);

          return res;
        })
        .catch((err) => {
          return err;
        });
    },
    register({ commit }, form) {
      return axiosClient
        .post("/register", form)
        .then((res) => {
          return res;
        })
        .catch((error) => {
          return error;
        });
    },
    checkClient({ commit }, { credentials, type }) {
      let api = type === "Twilio" ? "/checkTwilio" : "checkVonage";
      return axiosClient
        .post(api, credentials)
        .then((res) => {
          commit("setNotification", {
            message: "Service Saved successfully",
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops something went wrong !!",
            type: "danger",
          });
          return error;
        });
    },
    sendWithTwilio({ commit }, message) {
      return axiosClient
        .post("/sendSmsWithTwilio", message)
        .then((res) => {
          commit("setNotification", {
            message: res.response.data.success,
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops something went wrong !!",
            type: "danger",
          });
          return error;
        });
    },
    sendWithVonage({ commit }, message) {
      return axiosClient
        .post("/sendSmsWithVonage", message)
        .then((res) => {
          if (res.data.status === "success") {
            commit("setNotification", {
              message: res.data.message,
              type: "success",
            });
          } else {
            commit("setNotification", {
              message: res.data.message,
              type: "danger",
            });
          }
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oops something went wrong !!",
            type: "danger",
          });
          return error;
        });
    },
    updatePass({ commit }, newPass) {
      return axiosClient
        .put(`/updatePass`, newPass)
        .then((res) => {
          commit("setNotification", {
            message: "Password Updated successffully",
            type: "success",
          });
          return res;
        })
        .catch((error) => {
          commit("setNotification", {
            message: "Oopps something went wrong ",
            type: "danger",
          });
          return error;
        });
    },
    deleteUser({ commit }, id) {
      return axiosClient
        .delete("/deleteUser/" + id)
        .then((res) => {
          if (res.data === "successfful") {
            commit("deleteUser");
            return res;
          }
          commit("setNotification", {
            message: "deleted successffully Redirecting...",
            type: "success",
          });
        })
        .catch(() => {
          commit("setNotification", {
            message: "Oops it was an error !!",
            type: "danger",
          });
        });
    },
    updateUser({ commit }, newUser) {
      return axiosClient
        .put(`/updateUser/${newUser.id}`, newUser)
        .then((res) => {
          commit("setUser", res.data);
          commit("setNotification", {
            message: "Profile Updated successffully !!",
            type: "success",
          });
          return res;
        })
        .catch((error) => error);
    },
  },
  mutations: {
    setNotification: (state, { message, type }) => {
      state.notification.message = message;
      state.notification.type = type;
      state.notification.show = true;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000);
    },
    setSmsEvents(state, events) {
      state.events.data = events;
      state.events.loading = false;
    },
    setNotificationOff(state) {
      state.notification.show = false;
    },
    setUser(state, user) {
      state.user.data = user;
    },
    setDashboard(state, data) {
      state.dashboard.data = data;
      state.dashboard.loading = true;
    },
    deleteUser(state) {
      state.user.data = {};
      state.user.token = null;
      sessionStorage.removeItem("TOKEN");
    },

    setContacts(state, contacts) {
      state.contacts.data = contacts;
      state.contacts.loading = false;
    },
    setEvent(state, event) {
      state.event = event;
    },
    setSmStatusNotifications(state, smsNotifications) {
      state.smStatusNotifications = smsNotifications;
    },
  },
  getters: {},
  modules: {},
});

export default store;
