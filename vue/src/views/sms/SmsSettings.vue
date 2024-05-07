<template>
  <BaseCompo>
    <template v-slot:header> SMS Settings </template>

    <div class="2xl:flex flex-row animate-fade-in-down sm:-mt-14" id="smssettings">
      <div class="w-1/2 hidden 2xl:block animated-fade-in-down">
        <img
          src="../../../public/images/Chat-rafiki.png"
          alt="smsvector.jpg"
          class="w-full h-4/4 -mt-24 ml-24 animated-fade-in-down"
        />
      </div>

      <Spinner v-if="spinner" class="ml-[30rem] -mt-[23rem]" />
      <form
        v-else
        class="max-w-sm w-full mx-auto 2xl:mt-24 ml-22 -mb-36 animated-fade-in-down"
      >
        <h2 class="text-gray-900 text-md font-bold mb-6">
          Fill you Account Service Details :
        </h2>

        <div v-if="Object.keys(errors).length">
          <Alert>
            <ul class="mt-1.5 list-disc list-inside">
              <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <li v-for="(error, i) in errors[field]" :key="i">
                  {{ error }}
                </li>
              </div>
            </ul>
          </Alert>
        </div>

        <Alert v-else>
          <ul class="mt-2 ml-1">
            <li>* Pls enter a valid Service Credentials</li>
          </ul>
        </Alert>
        <div class="mb-4 px-2 w-full hidden" id="phone">
          <label class="block mb-1 text-sm" for="input1">{{
            fields.number
          }}</label>

          <input
            id="input1"
            class="w-full border border-gray-300 px-4 py-2 rounded focus:border-blue-500 focus:shadow-outline outline-none"
            type="text"
            autofocus
            v-model="client.phone_number"
            placeholder="+212..."
          />
        </div>
        <div class="mb-4 px-2 w-full">
          <label class="block mb-1 text-sm" for="input1">{{
            fields.first
          }}</label>

          <input
            id="input1"
            class="w-full border px-4 border-gray-300 py-2 rounded focus:border-blue-500 focus:shadow-outline outline-none"
            type="text"
            autofocus
            v-model="client.service_key"
            placeholder="first Params..."
          />
        </div>
        <div class="mb-4 px-2 w-full">
          <label class="block mb-1 text-sm" for="input1">{{
            fields.second
          }}</label>

          <input
            id="input1"
            class="w-full border border-gray-300 px-4 py-2 rounded focus:border-blue-500 focus:shadow-outline outline-none"
            type="text"
            v-model="client.service_token"
            placeholder="second Params.."
          />
        </div>
        <div class="mb-4 px-2 w-full">
          <label
            for="countries"
            class="block mb-2 text-sm border-gray-300 font-medium text-gray-900 dark:text-black"
            >Select your SMS service :</label
          >
          <select
            id="select"
            @change="changeName($event.target.value)"
            v-model="fields.type"
            class="bg-white border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 border-gray-300 dark:placeholder-gray-400 text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
          >
            <option value="">Choose your server</option>
            <option value="Twilio">Twilio</option>
            <option value="Vonage">Vonage</option>
          </select>
        </div>
        <div class="mb-4 px-2 w-full">
              <Button
            :loading="loading"
            @trigger-event="checkClient()"
            :string="'Save Settings'"
          />

        </div>
      </form>
    </div>
  </BaseCompo>
</template>

<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import Alert from "../../components/Alert.vue";
import Spinner from "../../components/Spinner.vue";
import Button from "../../components/Button.vue";
import store from "../../store";
import { useRouter, useRoute } from "vue-router";
import { ref } from "vue";
const route = useRoute();
const router = useRouter();
const errors = ref({});
const fields = ref({
  number: "",
  type: "",
  first: "",
  second: "",
});
const client = ref({
  service: null,
  phone_number: null,
  service_key: null,
  service_token: null,
});
const loading = ref(false);
const spinner = ref(false);
if (route.params.id) {
  store.dispatch("getServiceDetails", route.params.id).then((res) => {
    client.value.phone_number = res.data.phone_number || null;
    client.value.service_key = res.data.service_key;
    client.value.service_token = res.data.service_token;
    spinner.value = false;
    if (res.data.service_name === "Vonage") {
      fields.value.type = "Vonage";
      changeName("Vonage");
    } else {
      fields.value.type = "Twilio";
      changeName("Twilio");
    }
  });
} else {
  client.value = {};
  spinner.value = false;
}
function changeName(option) {
  document
    .querySelector("#select")
    .classList.add("border-gray-600", "text-black");

  document
    .querySelector("#select")
    .classList.remove("border-red-500", "text-red-900");
  errors.value = {};
  if (option === "Twilio") {
    document.getElementById("phone").classList.remove("hidden");
    fields.value.number = "Phone Number";
    fields.value.first = "SID";
    fields.value.second = "Token";
  } else if (option === "Vonage") {
    document.getElementById("phone").classList.add("hidden");
    fields.value.first = "Api Key";
    fields.value.second = "Api Secret";
  } else {
    document.getElementById("phone").classList.add("hidden");

    (fields.value.first = ""), (fields.value.second = "");
  }
}

store.state.notification.show = false;

function checkClient() {
    loading.value = true;
  if (document.querySelector("select").selectedIndex === 0) {
    document
      .querySelector("select")
      .classList.remove("border-gray-600", "text-black");

    document
      .querySelector("select")
      .classList.add("border-red-500", "text-red-900");
  } else {
    if (route.params.id) {
      store
        .dispatch("updateService", {
          client: client.value,
          id: route.params.id,
          type: fields.value.type,
        })
        .then((res) => {
          if (res.data.status === "success") {
            loading.value=false;
            router.push({ name: "service" });
          }
        });
    } else {
      client.value.service = fields.value.type;
      store
        .dispatch("checkClient", {
          credentials: client.value,
          type: `${fields.value.type}`,
        })
        .then((res) => {
          if (res.response.data === "success" || res.status === 200) {
            loading.value=false;

            router.push({
              name: "sendSms",
            });
          } else {
            errors.value = JSON.parse(JSON.stringify(res.response.data.errors));

loading.value=false;

          }
        })
        .catch((error) => {
            loading.value=false;

          console.error("error in checking service :", error);
        });
    }
  }
}
</script>
