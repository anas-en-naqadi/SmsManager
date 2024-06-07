<template>
  <div class="md:overflow-hidden">
    <div class="sm:mx-auto sm:max-w-sm sm:max-h-sm">
      <img
        class="mx-auto h-32 w-32 -mb-10"
        src="../../../public/images/logo.png"
        alt="logo"
      />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        Sign in to your account
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6">
        <div
          id="success"
          class="hidden p-4 mb-4 text-sm text-green-600 rounded-lg bg-green-50"
          role="alert"
        >
          Your account was created successfully, redirecting ...
        </div>
        <div v-if="Object.keys(errorMsg).length">
          <Alert>
            <ul class="mt-1.5 list-disc list-inside">
              <div v-for="(field, i) of Object.keys(errorMsg)" :key="i">
                <li v-for="(error, i) in errorMsg[field]" :key="i">
                  {{ error }}
                </li>
              </div>
            </ul>
          </Alert>
        </div>
        <div>
          <label
            for="name"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Full Name</label
          >
          <div class="mt-2">
            <input
              autofocus
              v-model="user.name"
              id="name"
              name="fullName"
              type="text"
              class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>
        <div>
          <label
            for="email"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Email address</label
          >
          <div class="mt-2">
            <input
              v-model="user.email"
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Password</label
            >
          </div>
          <div class="mt-2">
            <input
              v-model="user.password"
              id="password"
              name="password"
              type="password"
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
              v-model="user.password_confirmation"
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="current-password"
              class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>
        <div>
          <Button
            :loading="loading"
            @trigger-event="register()"
            :string="'Sign up'"
          />
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import store from "../../store";
import { useRouter } from "vue-router";
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";
let errorMsg = ref("");
const user = {
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
};
const router = useRouter();
const loading = ref(false);

function register() {
  loading.value = true;
  store
    .dispatch("register", user)
    .then((res) => {
      loading.value = false;
      if (res.status == 200) {
        document.querySelector("#success").classList.remove("hidden");
        setTimeout(() => {
          router.push({
            name: "login",
          });
        }, 1300);
      }
      errorMsg.value = res.response.data.errors;
    })
    .catch((err) => {
      loading.value = true;

      if (err.response.status === 422) {
        errorMsg.value = err.response.data.errors;
      }
    });
}
</script>
