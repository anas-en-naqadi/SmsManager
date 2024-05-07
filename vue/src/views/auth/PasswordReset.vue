<template>
  <div class="w-full">
    <div
      class="m-auto flex mt-[10%] flex-row items-center justify-center gap-12 max-w-[58rem] border shadow-lg rounded-lg p-12 max-h-min"
    >
      <form class="w-full">
        <div class="mt-2">
          <div v-if="error">
            <Alert id="alert">
              <ul class="mt-1.5 list-disc list-inside">
                <li>
                  {{ error }}
                </li>
              </ul>
            </Alert>
          </div>


          <div class="flex flex-col gap-6">
            <div>
              <div class="flex items-center justify-between">
                <label
                  for="password"
                  class="block text-sm font-medium leading-6 text-gray-900"
                  >New password</label
                >
              </div>
              <div
            id="success"
            class="flex gap-1 p-6 hidden mt-2 -mb-2 text-md  text-green-600 rounded-lg bg-green-50"
            role="alert"
          >
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>


            password updated successfully, redirecting ...
          </div>
              <div class="mt-2">
                <input
                  id="password"
                  name="password"
                  type="password"
                  v-model="data.password"
                  autocomplete="current-password"
                  class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
            <div>
              <div class="flex items-center justify-between">
                <label
                  for="password_confirmation"
                  class="block text-sm font-medium leading-6 text-gray-900"
                  >Confirm Password</label
                >
              </div>
              <div class="mt-2">
                <input
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  v-model="data.password_confirmation"
                  autocomplete="current-password"
                  class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
          </div>
        </div>

        <div>
          <Button
            :loading="loading"
            @trigger-event="updatePass()"
            :string="'Update password'"
          />
        </div>
      </form>
      <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm mb-4">
        <img
          class="mx-auto h-34 w-34 -mb-10"
          src="../../../public/images/logo.png"
          alt="Your Company"
        />
        <h2
          class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
        >
          SMS MANAGER
        </h2>
      </div>
    </div>
  </div>
</template>


<script setup>
import store from "../../store";
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";
import CryptoJS from "crypto-js";

const routes = useRoute();
const router = useRouter();
const token = atob(routes.query.t);
console.log(
  "token",
  CryptoJS.AES.decrypt(token, "code").toString(CryptoJS.enc.Utf8)
);
const error = ref("");
const data = ref({
  password: "",
  password_confirmation: "",
  code: CryptoJS.AES.decrypt(token, "code").toString(CryptoJS.enc.Utf8),
});
const loading = ref(false);
async function updatePass() {
  loading.value = true;

  await store.dispatch("updatePassword", data.value).then((res) => {
    if (res.status === 200 && res.data.status === "success") {
      document.getElementById("success").classList.remove("hidden");

      setTimeout(() => {
        loading.value = false;
        router.push({ name: "login" });
      }, 2000);
    } else {
      loading.value = false;
      error.value = res.response.data.message;
    }
  });
}
</script>
