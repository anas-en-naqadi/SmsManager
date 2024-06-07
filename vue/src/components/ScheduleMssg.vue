<template>
  <div v-if="isModalOpen">
    <!-- The modal -->
    <div
      class="fixed inset-0 z-40 flex items-center justify-center bg-gray-500 bg-opacity-75"
    >
      <div class="bg-white p-5 rounded shadow-lg w-[30rem]">
        <!-- Input field -->
        <div>
          <div class="flex flex-row mb-2 items-center gap-24 justify-between">
            <label
              class="block mb-2 text-lg font-bold text-gray-900 white:text-white"
              for="file_input"
              >Message Details</label
            >
            <button @click="closeModel()" class="relative top-0 right-0 mb-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
          <Alert v-if="Object.keys(errors).length">
            <ul class="mt-1.5 list-disc list-inside">
              <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <li v-for="(error, i) in errors[field]" :key="i">
                  {{ error }}
                </li>
              </div>
            </ul>
          </Alert>
          <Alert v-if="error"
            ><ul>
              <li class="mt-2">* {{ error }}</li>
            </ul></Alert
          >
          <div class="">
            <div class="mb-5">
              <label
                for="email"
                class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
                >Service:</label
              >
              <select
                id="countries"
                :disabled="message.status === 'sent' || message.status ==='not sent' || message.status ==='pending'"
                @change="changeName($event)"
                v-model="message.service"
                class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
              >
                <option value="" selected>Choose your service</option>
                <option
              v-for="(service, index) in services"
              :key="index"
              :value="service.service_name"
            >
              {{ service.service_name }}
            </option>
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
                v-if="event == null"
                v-model="message.to"
                class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
              >
                <template v-if="filteredContacts">
                  <option value="" selected>Choose Number</option>
                  <option
                    v-for="(contact, index) in filteredContacts"
                    :key="index"
                    :value="contact.phone_number"
                  >
                    {{ contact.phone_number }}
                  </option>
                </template>
                <template v-else>
                  <option value="" selected>No phone number added yet</option>
                </template>
              </select>
              <ul
                v-else
                id="phones"
                class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
              >
                <template v-if="filteredContacts.length">
                  <li v-for="(contact, index) in filteredContacts" :key="index">
                    * {{ contact }}
                  </li>
                </template>

                <template v-else>
                  <li>{{ message.to }}</li>
                </template>
              </ul>
            </div>

            <div class="mb-5">
              <label
                for="email"
                class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
                >From:</label
              >
              <input
                id="input1"
                :readonly="event !== null"
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
                :readonly="event !== null"
                v-model="message.body"
                class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Leave a message..."
              ></textarea>
            </div>

            <div>
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
                  :disabled="message.status === 'sent'"
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
                  :disabled="message.status === 'sent'"
                  v-model="message.scheduled_time"
                  class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-row items-center justify-between">
          <!-- Submit button -->
          <button
            v-if="message.status !== 'sent'"
            @click="event != null ? updateEvent() : addEvent()"
            type="button"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2.5 text-sm w-24 px-2.5 rounded mt-4"
          >
            Submit
          </button>
          <!-- Submit button -->

          <button
            v-if="message.status === 'not sent'"
            @click="deleteEvent(event.id)"
            type="button"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded mt-4"
          >
            delete
          </button>
          <router-link
            v-if="message.status !== 'sent'"
            :to="{ name: 'sendTogroup' }"
            class="text-white bg-blue-500 font-bold hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 mt-4 rounded text-sm px-2.5 py-2.5 text-center white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
          >
            Send To Group
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, defineProps, onMounted, computed, watch } from "vue";
import store from "../store";
import Alert from "./Alert.vue";
const emit = defineEmits(["close-event"]);
const message = ref({
  to: "",
  from: "",
  body: "",
  service: "",
  scheduled_date: "",
  scheduled_time: "",
});
const services = ref([]);
onMounted(() => {
  store.dispatch("getAllContacts");
   store.dispatch("getAllServices").then((res) => {
    console.log(res);
    if (res.status == 200) {
      services.value = [...res.data.data];
    }
  });
});
const props = defineProps({
  isModalOpen: Boolean,
  start: String,
});
const errors = ref({});
const error = ref("");
const event = ref(null);

watch(
  () => props.start,
  (newVal) => {
    message.value.to = "";
    message.value.service = "";
    message.value.scheduled_date = newVal.slice(0, 10) || "";
    message.value.scheduled_time = newVal.slice(11, 16) || "";
  }
);
const filteredContacts = ref(null);
watch(
  () => store.state.event,
  (newVal) => {
    event.value = newVal;
    filteredContacts.value = Array.isArray(event.value.to)
      ? event.value.to
      : [];

    updateMessage();
  }
);
let contacts = ref(computed(() => store.state.contacts.data.data));

function changeName(ev) {
  document
    .querySelector("select")
    .classList.add("border-gray-600", "text-black");

  document
    .querySelector("select")
    .classList.remove("border-red-500", "text-red-900");
  message.value.to = "";

  if (ev.target.value === "Twilio") {
    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Twilio"
    );
    store.dispatch("getTwilioPhoneNumber").then((res) => {
      message.value.service = "Twilio";
      message.value.from = res.data;
    });
  } else if (ev.target.value === "Vonage") {
    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Vonage"
    );
    message.value.service = "Vonage";

    message.value.from = "";
  }
  errors.value = {};
  error.value = "";
}
function closeModel() {
  message.value = {};
  event.value = null;
  filteredContacts.value = [];
  errors.value = {};
  error.value = "";
  contacts.value = {};
  message.value.to = "";
  message.value.service = "";
  emit("close-event");
}

function updateMessage() {
  message.value = {
    to: event.value.to,
    from: event.value.from,
    body: event.value.message,
    service: event.value.service,
    status: event.value.status,
    scheduled_date: event.value.scheduled_at.slice(0, 10),
    scheduled_time: event.value.scheduled_at.slice(11, 16),
  };
}

function updateEvent() {
  const type = Array.isArray(event.value.to) ? "many" : "one";
  const newEvent = {
    start: message.value.scheduled_date + message.value.scheduled_time,
    id: event.value.id,
    type: type,
  };

  store.dispatch("updateSmsEvents", newEvent).then(res=>{
      if (res.status === 200) store.dispatch("getAllEvents");
  });

  closeModel();
}
function deleteEvent(id) {
  const type = Array.isArray(event.value.to) ? "many" : "one";
  if (confirm("Are you sure you delete this")) {
    store.dispatch("deleteEvent", { type: type, id: id }).then((res) => {
      if (res.status === 200) store.dispatch("getAllEvents");
    });
  }
  closeModel();
}
function scheduleNotif() {
  store
    .dispatch("scheduleNotif", message.value)
    .then((res) => {
      if (res.status === 200 || res.response.status === "success") {
        store.dispatch("getAllEvents");

        closeModel();
      } else if (res.response.data.errors) {
        errors.value = res.response.data.errors;
      } else {
        error.value = res.response.data.message;
      }
    })
    .catch((error) => console.error(error));
}
function addEvent() {
  if (message.value.service === "") {
    document
      .querySelector("select")
      .classList.add("border", "border-red-500", "text-red-500");
  } else {
    const now = new Date().toISOString().substring(0, 10);

    if (message.value.scheduled_date < now) {
      store.commit("setNotification", {
        message: "You can't add event on a past day !!!",
        type: "danger",
      });
      document
        .getElementById("scheduled_date")
        .classList.add("border", "border-red-500", "text-red-500");
    } else {
      scheduleNotif();
    }
  }
}
</script>
