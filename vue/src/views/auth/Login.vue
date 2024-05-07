<template>
  <div >
    <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm ">
      <img
        class="mx-auto h-32 w-32 -mb-10"
        src="../../../public/images/logo.png"
        alt="Your Company"
      />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        Sign in to your account
      </h2>
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6">
        <div>
          <div v-if="Object.keys(errors).length">
            <Alert id="alert">
              <ul class="mt-1.5 list-disc list-inside">
                <div v-for="(field, i) of Object.keys(errors)" :key="i">
                  <li v-for="(error, i) in errors[field]" :key="i">
                    {{ error }}
                  </li>
                </div>
              </ul>
            </Alert>
          </div>
          <Alert v-if="errorMsg">
            <ul>
              <li>{{ errorMsg }}</li>
            </ul>
          </Alert>

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
              autofocus
              autocomplete="email"
              required=""
              class="block w-full rounded-md border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label
              for="password"
              class="block text-sm font-medium leading-6 text-gray-900"
              >Password
            </label>
          </div>
          <div class="mt-2">
            <input
              v-model="user.password"
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required=""
              class="block w-full rounded-md border-1 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            />
            <div class="text-sm mt-3">
              <a
                href="/email/verification"
                class="font-semibold text-blue-600 hover:text-blue-500 hover:underline"
                >Forgot Password?</a
              >
            </div>
          </div>
        </div>
        <div class="flex flex-row justify-between items-center">
          <label
            for="email"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Remember me
          </label>
          <input
            type="checkbox"
            v-model="user.remember"
            name="remember-me"
            id="remember me"
            class="font-semibold text-blue-600 hover:text-blue-500"
          />
        </div>

        <div>
          <Button
            :loading="loading"
            @trigger-event="login()"
            :string="'Sign in'"
          />
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Not a member?
        {{ " " }}
        <a
          href="/register"
          class="font-semibold leading-6 text-blue-600 hover:text-blue-500"
          >Register</a
        >
      </p>
    </div>
  </div>
</template>

<script setup>
import store from "../../store";
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";
import { ref } from "vue";
const errors = ref({});
const user = {
  email: "",
  password: "",
  remember: false,
};
function makeitRed() {
  document.querySelector("#email").classList.add("border-red-600");
  document.querySelector("#password").classList.add("border-red-600");
}
let errorMsg = ref("");
const loading = ref(false);


function login() {
  document.querySelector("#email").classList.remove("border-red-600");
  document.querySelector("#password").classList.remove("border-red-600");
  loading.value = true;
  store
    .dispatch("login", user)
    .then((res) => {
            console.dir(res)
      if (res.status === 200 && res.data.status === "success") {
        setTimeout(() => {
          window.location.href = "http://127.0.0.1:5173/dashboard";

        }, 1000);
      }
       else if(res.data && res.data.message) {
        errors.value = {};

        makeitRed();
        errorMsg.value = res.data.message;

      } else  {
        errorMsg.value = "";
        makeitRed();
        errors.value = res.response.data.errors;

      }
       loading.value = false;
    })
    .catch((err) => {
      loading.value = false;
    });
}
</script>
