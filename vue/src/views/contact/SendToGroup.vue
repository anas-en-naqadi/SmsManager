<template>
  <BaseCompo>
    <template v-slot:header> Send To Group </template>

    <Spinner v-if="route.params.id || spinner" />

    <div v-else class="2xl:flex flex-row">
      <div class="w-1/2 hidden 2xl:block">
        <img
          src="../../../public/images/16717261_2002.i039.018_remote_management_distant_work_isometric_icons-13.jpg"
          alt=""
          class="w-[800] h-3/3 -mt-10 ml-16 animate-fade-in-down"
        />
      </div>
      <div class="2xl:ml-52 2xl:-mt-3 flex flex-row w-full gap-24">
        <form class="max-w-sm mx-auto w-full xl:ml-46 animate-fade-in-down">
          <div class="mb-5">
            <Alert v-if="Object.keys(errors).length" id="alert" class="-mt-12">
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
            <select
              @change="
                filteredContacts = [];
                errors = {};
              "
              v-model="sendOption"
              class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
              <option selected value="">Choose Sending Option</option>

              <option value="category">Send to Category</option>
              <option value="contacts">Send to Contacts</option>
            </select>
          </div>
          <div class="mb-5" v-if="sendOption === 'contacts'">
            <label
              for="countries"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-black"
              >Select your SMS service</label
            >
            <select
              id="countries"
              v-model="message.service"
              @change="changeName($event)"
              class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
              <option selected value="">Choose your service</option>
              <option value="Twilio">Twilio</option>
              <option value="Vonage">Vonage</option>
            </select>
          </div>
          <div class="mb-5" v-else-if="sendOption === 'category'">
            <label
              for="categories"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-black"
              >Select your category</label
            >
            <select
              id="categories"
              v-model="message.category"
              @change="filterCategory($event.target.value)"
              class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
              <option selected value="">Choose your Category</option>
              <option
                v-for="(category, index) in categories"
                :key="index"
                :value="category"
              >
                {{ category }}
              </option>
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
              class="w-full border bg-gray-50 rounded-md border-gray-300 px-4 py-2 focus:border-blue-500 focus:shadow-outline outline-none"
              type="text"
              v-model="message.from"
              placeholder="second Params.."
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
              :disabled="isScheduledDisabled"
              v-model="Schedule"
              name="remember-me"
              id="scheduled"
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
            @trigger-event="sendTogroup()"
            :string="'send message'"
          />
        </form>
        <div class="w-full animate-fade-in-down overflow-y-scroll">
          <dl class="max-w-md text-gray-900 mt-4 divide-y divide-gray-200">
            <div class="flex my-4 flex-row justify-between items-center">
              <span>All Contacts</span>
              <a
                class="text-blue-400 text-md cursor-pointer"
                @click="selectAll()"
                >Select all</a
              >
            </div>
            <div
              class="flex flex-row justify-between items-center mb-3"
              v-for="contact in filteredContacts"
              :key="contact.id"
            >
              <div class="flex flex-col pb-3">
                <dt class="mb-1 text-gray-500 md:text-lg">
                  {{ contact.name }}
                </dt>
                <dd class="text-lg font-semibold">
                  {{ contact.phone_number }}
                </dd>
              </div>
              <div>
                <input
                  id="default-checkbox"
                  type="checkbox"
                  value=""
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2"
                />
              </div>
            </div>
          </dl>
          <div
            v-if="Object.keys(filteredContacts).length === 0"
            class="text-red-500 font-bold mr-32 mt-12 text-center"
          >
            Pls Choose an option !!
          </div>
        </div>
      </div>
    </div>
  </BaseCompo>
</template>

<script setup>
import Alert from "../../components/Alert.vue";
import BaseCompo from "../../components/BaseCompo.vue";
import store from "../../store";
import Spinner from "../../components/Spinner.vue";
import { computed, ref, watch, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { debounce } from "lodash";
import Button from "../../components/Button.vue";
//  <Button :loading="loading" :func="sendTogroup()" :string="'send To group'" />
onMounted(() => {
  store.dispatch("getAllContacts");
});
const router = useRouter();
const route = useRoute();
const Schedule = ref(false);
const errors = ref({});
const contacts = computed(() => store.state.contacts.data);
const filteredContacts = ref({});
const isScheduledDisabled = computed(() =>
  sendOption.value === "" ? true : false
);
let allChecked = true;

const checkedContacts = ref({});
const categories = ref([]);
const sendOption = ref("");
const loading = ref(false);
const spinner = ref(true);

let draftId;

const message = ref({
  service: "",
  from: "",
  body: "",
  to: null,
});

if (route.params.id) {
  store.dispatch("getDraftDetails", route.params.id).then((res) => {
    if (res.data.draft.from) message.value.from = res.data.draft.from;
    message.value.body = res.data.draft.body;
    let category = res.data.draft.category;
    let to = res.data.draft.to;
    if (category) {
      watch(contacts, (newVal, oldVal) => {
        filteredContacts.value = contacts.value.filter(
          (c) => c.category === category
        );
      });

      sendOption.value = "category";
      message.value.category = res.data.draft.category;
    }

    setTimeout(() => {
      if (res.data.draft.server) {
        message.value.service = res.data.draft.server;
        sendOption.value = "contacts";
        if (res.data.draft.server === "Vonage") {
          draft("Vonage", to);
        } else {
          draft("Twilio", to);
        }
      } else {
        message.value.service = "";
      }
      if (res.data.draft.scheduled_at) {
        isScheduledDisabled.value = false;
        Schedule.value = true;
        message.value.scheduled_date =
          res.data.draft.scheduled_at.slice(0, 10) || "";
        message.value.scheduled_time =
          res.data.draft.scheduled_at.slice(11, 16) || "";
      }
      spinner.value = false;
    }, 200);
  });

  function draft(ev, to) {
    message.value.to = "";
    watch(contacts.value, (newVal, oldVal) => {
      filteredContacts.value = newVal.filter(
        (c) => c.service_name === ev && to.includes(c.phone_number)
      );
    });
    if (ev === "Twilio") {
      isScheduledDisabled.value = true;
    } else if (ev === "Vonage") {
      isScheduledDisabled.value = true;
    }
  }
} else {
  message.value.category = "";
  spinner.value = false;
  const debouncedMessageHandler = debounce(() => {
    if (!draftId) {
      message.value.to = getCheckedInputs();
      store.dispatch("storeDrafts", message.value).then((res) => {
        if (res.status === 200) draftId = res.data.id;
      }); // Dispatch message directly
    } else {
      store.dispatch("updateDraft", { id: draftId, message: message.value });
    }
  }, 1500);

  // Watch for changes in the message object and trigger the debounced handler
  watch(message.value, () => {
    debouncedMessageHandler();
  });
}

function getCheckedInputs() {
  const inputs = document.querySelectorAll('[type="checkbox"]');
  checkedContacts.value = Array.from(inputs)
    .filter((input) => input.checked && input.id != "scheduled")
    .map((input) => {
      const parent = input.parentElement.parentElement;
      return parent.querySelector("dd").textContent;
    });
  return checkedContacts.value;
}

watch(contacts, (newVal, oldVal) => {
  categories.value = Array.from(
    new Set(
      Object.values(newVal)
        .map((c) => c?.category) // Optional chaining to handle null c
        .filter((category) => category) // Filter out falsy values (null, undefined, "")
    )
  );
});

function changeName(ev) {
  document
    .querySelector("select")
    .classList.remove("border-red-500", "text-red-900");
  document
    .querySelector("select")
    .classList.add("border-gray-600", "text-black");

  message.value.service = "";

  if (ev.target.value === "Twilio") {
    document.getElementById("scheduled").disabled = false;

    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Twilio"
    );
    store.dispatch("getTwilioPhoneNumber").then((res) => {
      message.value.service = "Twilio";
      message.value.from = res.data;
    });
  } else if (ev.target.value === "Vonage") {
    document.getElementById("scheduled").disabled = false;

    filteredContacts.value = contacts.value.filter(
      (c) => c.service_name === "Vonage"
    );
    message.value.from = "";
  } else {
    filteredContacts.value = {};
  }
  errors.value = {};
  error.value = "";
}
function filterCategory(category) {
  if (category !== "") {
    filteredContacts.value = contacts.value.filter(
      (c) => c.category === category
    );
    message.value.from = "";
    if (filteredContacts.value[0].service_name === "Twilio") {
      store.dispatch("getTwilioPhoneNumber").then((res) => {
        message.value.service = "Twilio";
        message.value.from = res.data;
      });
    }
  } else {
    filteredContacts.value = {};

    message.value = {};
  }
}

const error = ref("");
function selectAll() {
  const inputs = document.querySelectorAll('[type="checkbox"]');
  allChecked = !allChecked;
  if (allChecked) {
    for (let input of inputs) {
      if (input.id != "scheduled") {
        input.checked = false;
      }
    }
  } else {
    for (let input of inputs) {
      if (input.id != "scheduled") {
        input.checked = true;
      }
    }
  }
}

function sendTogroup() {
  loading.value = true;
  if (document.querySelector("select").selectedIndex === 0) {
    document
      .querySelector("select")
      .classList.remove("border-gray-600", "text-black");
    document
      .querySelector("select")
      .classList.add("border-red-500", "text-red-900");
    store.commit("setNotification", {
      message: "Please choose a service !!",
      type: "danger",
    });

    loading.value = false;
  } else if (
    message.value.category &&
    !document.querySelector("input[type=checkbox]").checked
  ) {
    message.value.to = getCheckedInputs();

    store.dispatch("sendByCat", message.value).then((res) => {
      if (res.status === 200) {
        store.dispatch("deleteDraft", route.params.id).then((res) => {
          router.push({
            path: "sms/notifications",
          });
        });
      } else if (res.response.data.errors) {
        errors.value = res.response.data.errors;
      } else {
        error.value = res.response.data.message;
      }
      loading.value = false;
    });
  } else if (document.querySelector("input[type=checkbox]").checked) {
    message.value.to = getCheckedInputs();

    console.log("to", getCheckedInputs());
    store
      .dispatch("scheduleNotifGroup", message.value)
      .then((res) => {
        console.dir(res);
        if (res.status === 200) {
          store.dispatch("deleteDraft", route.params.id).then((res) => {
            router.push({
              name: "calendar",
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
  } else {
    message.value.to = getCheckedInputs();

    const type = message.value.service === "Twilio" ? "Twilio" : "Vonage";

    store
      .dispatch("sendTogroup", { contacts: message.value, type: type })
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
      });
  }
  errors.value = {};
  error.value = "";
}
</script>
