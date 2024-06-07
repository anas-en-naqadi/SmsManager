<template>
  <BaseCompo>
    <template v-slot:header>
      <div
        class="flex flex-row max-w-full sm:gap-24 2xl:gap-96 justify-between items-center"
      >
        <div class="w-full sm:text-md">Add New Contact</div>
        <div class="w-full">
          <button
            @click="isOpen = !isOpen"
            type="button"
            class="2xl:ml-[35rem] text-green-700 hover:text-white border border-green-700 hover:bg-emerald-700 focus:ring-1 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 white:border-green-500 white:text-green-500 white:hover:text-white white:hover:bg-green-600 dark:focus:ring-green-800"
          >
            Add Csv file
          </button>
        </div>
      </div>
    </template>

    <div class="mt-10 2xl:flex flex-row">
      <div class="w-full h-full hidden 2xl:block animate-fade-in-down">
        <img
          src="../../../public/images/Accept_request.gif"
          alt="smsvector.jpg"
          class="w-[65rem] h-[800px] -mt-36"
        />
      </div>
      <Spinner v-if="spinner" class="mr-[30rem]" />
      <form
        v-else
        class="max-w-sm w-full h-full mx-auto -mt-24 2xl:mr-64 animate-fade-in-down"
      >
        <div>
          <Alert v-if="Object.keys(errors).length">
            <ul class="mt-1.5 list-disc list-inside">
              <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <li v-for="(error, i) in errors[field]" :key="i">
                  {{ error }}
                </li>
              </div>
            </ul>
          </Alert>
          <Alert v-else>
            <ul v-if="error" class="mt-2 ml-1">
              <li>* {{ error }}</li>
            </ul>
            <ul v-else class="mt-2 ml-1">
              <li>* Pls enter a verified number from Your SMS service</li>
            </ul>
          </Alert>
          <div>
            <div class="flex items-center justify-between mb-3">
              <label
                for="password"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Choose your SMS service :
              </label>
            </div>
            <select
              id="services"
              @change="changeName($event.target.value)"
              v-model="contact.service_name"
              class="bg-white border text-sm mb-3 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400 text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
              for="email"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Full name</label
            >
            <div class="mt-2">
              <input
                v-model="contact.name"
                id="name"
                name="name"
                type="text"
                required
                placeholder="Jhon doe"
                class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
            </div>
          </div>

          <div class="mb-5">
            <label
              for="email"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Category
            </label>
            <div class="mt-2">
              <input
                v-model="contact.category"
                id="category"
                name="category"
                type="text"
                required
                placeholder="Optional"
                class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
            </div>
          </div>

          <div class="mb-5">
            <div class="flex items-center justify-between">
              <label
                for="password"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Phone Number
              </label>
            </div>
            <div class="mt-2">
              <input
                v-model="contact.phone_number"
                id="text"
                name="text"
                type="text"
                placeholder="212..."
                required=""
                class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
            </div>
          </div>

          <div class="mb-5">
            <label
              for="message"
              class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
              >Address</label
            >
            <textarea
              id="message"
              v-model="contact.address"
              rows="3"
              class="block p-2 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Leave a message..."
            ></textarea>
          </div>

          <div>
            <Button
              :loading="loading"
              @trigger-event="saveContact()"
              :string="'Save Contact'"
            />
          </div>
        </div>
      </form>
    </div>

    <CsvImport :isModalOpen="isOpen" @close-modal="isOpen = false" />
  </BaseCompo>
</template>

<script setup>
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";

import BaseCompo from "../../components/BaseCompo.vue";
import Spinner from "../../components/Spinner.vue";
import CsvImport from "../../components/CsvImport.vue";
import store from "../../store";
import { useRouter, useRoute } from "vue-router";
import { ref, onMounted } from "vue";
const errors = ref({});
const router = useRouter();
const isOpen = ref(false);
const error = ref("");
const route = useRoute();
const contact = ref({
  name: null,
  phone_number: null,
  service_name: "",
  category: null,
  address: null,
});
const loading = ref(false);
const spinner = ref(false);
const services = ref([]);
onMounted(() => {
  contact.value.service_name = "";
  store.dispatch("getAllServices").then((res) => {
    console.log(res);
    if (res.status == 200) {
      services.value = [...res.data.data];
    }
  });
  store.dispatch("getsmStatusNotifications");
});
if (route.params.id) {
  spinner.value = true;
  store.dispatch("getContactDetails", route.params.id).then((res) => {
    contact.value.name = res.data.name || null;
    contact.value.phone_number = res.data.phone_number;
    contact.value.service_name = res.data.service_name;
    contact.value.category = res.data.category || "";
    contact.value.address = res.data.address || "";

    spinner.value = false;
    if (res.data.service_name === "Vonage") {
      changeName("Vonage");
    } else {
      changeName("Twilio");
    }
  });
} else {
  contact.value = {};
  loading.value = false;
}
function changeName(option) {
  document
    .querySelector("select")
    .classList.add("border-gray-600", "text-black");

  document
    .querySelector("select")
    .classList.remove("border-red-500", "text-red-900");
  errors.value = {};
  if (option === "Vonage") {
    document.querySelector("select").selectedIndex = 1;
  } else {
    document.querySelector("select").selectedIndex = 2;
  }
}
function saveContact() {
  loading.value = true;
  if (route.params.id) {
    store
      .dispatch("updateContact", {
        contact: contact.value,
        id: route.params.id,
      })
      .then((res) => {
        loading.value = false;
        if (res.status === 200) {
          router.push({
            name: "contacts",
          });
        } else {
          errors.value = res.response.data.errors;
        }
      });
  } else {
    if (document.querySelector("select").selectedIndex === 0) {
      document
        .querySelector("select")
        .classList.remove("border-gray-600", "text-black");

      document
        .querySelector("select")
        .classList.add("border-red-500", "text-red-900");
      loading.value = false;
    } else {
      store.dispatch("saveContact", contact.value).then((res) => {
        loading.value = false;
        if (res.status === 200 && res.data.status === "success") {
          router.push({
            name: "contacts",
          });
        } else if (res.response.data.errors) {
          errors.value = res.response.data.errors;
        } else {
          error.value = res.response.data.message;
        }
      });
    }
  }
}
</script>
