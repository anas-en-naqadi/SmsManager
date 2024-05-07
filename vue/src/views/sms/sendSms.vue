<template>
  <BaseCompo>
    <template v-slot:header> Send SMS </template>

    <Spinner v-if="spinner" />
    <div v-else class="2xl:flex flex-row sm:-mt-12">
      <div class="w-1/2 hidden 2xl:block">
        <img
          src="../../../public/images/smsvector.png"
          alt=""
          class="w-3/3 h-5/5 -mt-16 ml-28 animate-fade-in-down"
        />
      </div>

      <form class="max-w-sm mx-auto w-full xl:ml-46 animate-fade-in-down" id="form">
        <div class="mb-5">
        <div v-if="Object.keys(errors).length">
              <Alert  class="-mt-16" id="alert">
            <ul class="mt-1.5 list-disc list-inside">
              <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <li v-for="(error, i) in errors[field]" :key="i">
                  {{ error }}
                </li>
              </div>
            </ul>
          </Alert>
        </div>
          <Alert v-if="error" class="-mt-16"
            ><ul>
              <li class="mt-2">* {{ error }}</li>
            </ul></Alert
          >
          <label
            for="countries"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-black"
            >Select your SMS service</label
          >
          <select
            id="countries"
            v-model="message.service"
            @change="changeName($event.target.value)"
            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
          >
            <option value="">Choose your service</option>
            <option value="Twilio">Twilio</option>
            <option value="Vonage">Vonage</option>
          </select>
        </div>

        <div class="mb-5">
          <label
            for="number"
            class="block mb-2 text-sm font-medium text-gray-900"
            >To :</label
          >
          <select
            id="phones"
            v-model="message.to"
            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
          >
            <template v-if="filteredContacts">
              <option selected value="">Choose Number</option>
              <option
                v-for="(contact, index) in filteredContacts"
                :key="index"
                :value="contact.phone_number"
              >
                {{ contact.phone_number }}
              </option>
            </template>
            <template v-else>
              <option selected value="">No phone number added yet</option>
            </template>
          </select>
        </div>

        <div class="mb-5">
          <label
            for="email"
            class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
            >From:</label
          >
          <input
            id="input1"
            placeholder="example"
            class="w-full border bg-gray-50 rounded-md border-gray-300 px-4 py-2 focus:border-blue-500 focus:shadow-outline outline-none"
            type="text"
            v-model="message.from"
          />
        </div>

        <div class="mb-5">
          <label
            for="message"
            class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
            >Your message</label
          >
          <textarea
            id="message"
            rows="4"
            v-model="message.body"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Leave a message..."
          ></textarea>
        </div>
        <div class="flex flex-row justify-between items-center mb-5">
          <label
            for="email"
            class="flex flex-row gap-4 text-sm font-medium leading-6 text-gray-900"
            >Schedule Message
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-6"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"
              />
            </svg>
          </label>
          <input
            type="checkbox"
            disabled
            v-model="Schedule"
            name="remember-me"
            id="remember_me"
            class="font-semibold text-indigo-600 hover:text-indigo-500"
          />
        </div>
        <div v-if="Schedule">
          <div class="mb-5">
            <label
              for="scheduled_date"
              class="block mb-2 text-sm font-medium text-gray-900"
              >Scheduled Date:</label
            >
            <input
              type="date"
              id="scheduled_date"
              v-model="message.scheduled_date"
              class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />
          </div>

          <div class="mb-5">
            <label
              for="scheduled_time"
              class="block mb-2 text-sm font-medium text-gray-900"
              >Scheduled Time:</label
            >
            <input
              type="time"
              id="scheduled_time"
              v-model="message.scheduled_time"
              class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />
          </div>
        </div>
        <Button
          :loading="loading"
          @trigger-event="sendSms()"
          :string="'send message'"
        />
      </form>
    </div>
  </BaseCompo>
</template>

<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import Alert from "../../components/Alert.vue";
import store from "../../store";
import Spinner from "../../components/Spinner.vue";
import { useRouter, useRoute } from "vue-router";
import { ref, computed, watch, onMounted } from "vue";
import { debounce } from "lodash";
import Button from "../../components/Button.vue";
const router = useRouter();
const Schedule = ref(false);
const route = useRoute();
const spinner = ref(true);

const loading = ref(false);
const filteredContacts = ref([]);
const message = ref({
  service: "",
  to: "",
  from: null,
  body: null,
});
let draftId;

if (route.params.id) {
  store.dispatch("getDraftDetails", route.params.id).then((res) => {
    if (res.data.draft.to)
      filteredContacts.value = [{ phone_number: res.data.draft.to }];
    if (res.data.draft.from) message.value.from = res.data.draft.from;
    message.value.body = res.data.draft.body;

    spinner.value = false;

    setTimeout(() => {
      if (res.data.draft.server) {
        message.value.service = res.data.draft.server;
        if (res.data.draft.server === "Vonage") {
          draft("Vonage");
        } else {
          draft("Twilio");
        }
      } else {
        message.value.service = "";
      }
      if (res.data.draft.scheduled_at) {
        Schedule.value = true;
        message.value.scheduled_date =
          res.data.draft.scheduled_at.slice(0, 10) || "";
        message.value.scheduled_time =
          res.data.draft.scheduled_at.slice(11, 16) || "";
      }
    }, 200);
  });
} else {
//   const debouncedMessageHandler = debounce(() => {
//     message.value.draft = true; // Set draft property to true when debounce triggers
//     if (!draftId) {
//       store.dispatch("storeDrafts", message.value).then((res) => {
//         if (res.status === 200) draftId = res.data.id;
//       }); // Dispatch message directly
//     } else {
//       store.dispatch("updateDraft", { id: draftId, message: message.value });
//     }
//   }, 1500);

//   // Watch for changes in the message object and trigger the debounced handler
//   watch(message.value, () => {
//     debouncedMessageHandler();
//   });

  spinner.value = false;
}

onMounted(() => {
  store.dispatch("getAllContacts");

  store.dispatch("getsmStatusNotifications");
});
watch(
  () => Schedule.value,
  (newVal) => {
    if (newVal) {

      document.querySelector("#form").classList.add("-mt-20");
       document.querySelector("#alert").classList.remove("-mt-16");
    } else {
      delete message.value.scheduled_date;
      delete message.value.scheduled_time;
        document.querySelector("#alert").classList.add("-mt-16");
      document.querySelector("#form").classList.remove("-mt-20");
    }
  }
);
let contacts = ref(computed(() => store.state.contacts.data.data));

const errors = ref({});

function draft(ev) {
  message.value.to = "";

  if (ev === "Twilio") {
    filteredContacts.value = [message.value.to];

    document.getElementById("remember_me").disabled = false;
  } else if (ev === "Vonage") {
    document.getElementById("remember_me").disabled = false;

    filteredContacts.value = [message.value.to];
  }
}

function changeName(ev) {
  document
    .querySelector("select")
    .classList.add("border-gray-600", "text-black");

  document
    .querySelector("select")
    .classList.remove("border-red-500", "text-red-900");

  if (ev === "Twilio") {
    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Twilio"
    );
    message.value.to = "";

    store.dispatch("getTwilioPhoneNumber").then((res) => {
      message.value.service = "Twilio";
      message.value.from = res.data;
    });
    document.getElementById("remember_me").disabled = false;
  } else if (ev === "Vonage") {
    document.getElementById("remember_me").disabled = false;

    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Vonage"
    );
    message.value.service = "Vonage";

    message.value.from = "";
    message.value.to = "";
  } else {
    message.value.to = "";

    filteredContacts.value = [];
  }
  errors.value = {};
  error.value = "";
}
const error = ref("");
function sendSms() {
    loading.value=true;
  if (document.querySelector("select").selectedIndex === 0) {
    document
      .querySelector("select")
      .classList.remove("border-gray-600", "text-black");

    document
      .querySelector("select")
      .classList.add("border-red-500", "text-red-900");
      store.commit("setNotification",{message:"Please choose a service !!",type:"danger"});
      loading.value=false;
  } else {
    if (document.querySelector("input[type=checkbox]").checked) {


      store
        .dispatch("scheduleNotif", message.value)
        .then((res) => {
          if (res.status === 200 || res.response.status === "success") {
            store.dispatch("deleteDraft", route.params.id).then((res) => {
              router.push({
                name: "notif",
              });
            });
          } else if (res.response.data.errors) {
            errors.value = res.response.data.errors;
          } else {
            error.value = res.response.data.message;
          }
          loading.value=false;
        })
        .catch((error) => console.error(error));
    } else {

      if (message.value.service === "Twilio") {
        store
          .dispatch("sendWithTwilio", message.value)
          .then((res) => {
            if (res.status === 200) {
              store.dispatch("deleteDraft", route.params.id).then((res) => {
                router.push({
                  path: "/sms/notifications",
                });
              });
            } else if (res.response.data.errors) {
              errors.value = res.response.data.errors;
            } else {
              error.value = res.response.data.error;
            }
            loading.value = false;
          })
          .catch((error) => console.error(error));
      } else if (message.value.service === "Vonage") {
        store
          .dispatch("sendWithVonage", message.value)
          .then((res) => {
            if (res.status === 200) {
              store.dispatch("deleteDraft", route.params.id).then((res) => {
                router.push({
                  name: "notif",
                });
              });
            } else if (res.response.data.errors) {
              errors.value = res.response.data.errors;
            } else {
              error.value = res.response.data.message;
            }
            loading.value = false;
          })
          .catch((error) => console.error(error));
      }
    }
  }

  errors.value = {};
  error.value = "";
}
</script>
