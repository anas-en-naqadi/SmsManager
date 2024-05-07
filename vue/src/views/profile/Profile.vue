<template>
  <BaseCompo>
    <template v-slot:header>
      <h1 class="font-bold text-md">Profile</h1>
    </template>
    <div
      class="bg-white w-full flex flex-row justify-center items-center gap-24 px-3 md:px-16 lg:px-28 md:flex-row text-[#161931]"
    >
      <aside class="hidden py-4 md:w-1/3 lg:w-1/4 md:block">
        <div
          class="sticky flex flex-col gap-2 p-4 text-sm mb-52 border-r border-indigo-100 top-12"
        >
          <h2 class="pl-3 mb-4 text-2xl font-semibold">Settings</h2>

          <a
            @click="toggle('profile')"
            href="#"
            class="flex items-center px-3 py-2.5 font-bold bg-white text-indigo-900 focus:border focus:rounded-full"
          >
            Pubic Profile
          </a>
          <a
            @click="toggle('settings')"
            href="#"
            class="flex items-center px-3 py-2.5 font-semibold focus:text-indigo-900 focus:border focus:rounded-full"
          >
            Account Settings
          </a>
          <a
            href="#"
            @click="toggle('pass')"
            class="flex items-center px-3 py-2.5 font-semibold focus:text-indigo-900 focus:border focus:rounded-full"
          >
            Password Reset
          </a>
        </div>
      </aside>
      <div>
        <div role="status" v-if="!newUser" class="flex justify-center">
          <svg
            aria-hidden="true"
            class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
            viewBox="0 0 100 101"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="currentColor"
            />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentFill"
            />
          </svg>
          <span class="sr-only">Loading...</span>
        </div>
        <div
          v-else
          class="border-b border-gray-900/10 pb-12 -mt-12"
          id="profile"
        >
          <h2 class="text-base font-semibold leading-7 text-gray-900">
            Profile
          </h2>
          <p class="mt-1 text-sm leading-6 text-gray-600">
            Your private information.
          </p>

          <div
            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 items-center"
          >
            <div class="col-span-full ml-32">
              <div class="mt-2">
                <img
                  :src="newUser.image_url"
                  class="h-40 w-40 text-gray-300 rounded-full"
                  aria-hidden="true"
                />
              </div>
            </div>
            <div class="sm:col-span-8">
              <label
                for="username"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Full name</label
              >
              <div class="mt-2">
                <div
                  class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
                >
                  <span
                    type="text"
                    name="username"
                    id="username"
                    autocomplete="username"
                    class="block flex-1 border-0 bg-transparent py-2 pl-2 text-gray-900"
                    >{{ newUser.name }}</span
                  >
                </div>
              </div>
            </div>

            <div class="sm:col-span-8 2xl:col-span-24">
              <label
                for="about"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Email</label
              >
              <div class="mt-2">
                <div
                  class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md"
                >
                  <span
                    type="text"
                    name="username"
                    id="username"
                    autocomplete="username"
                    class="block flex-1 border-0 bg-transparent py-2 pl-2 text-gray-900"
                    >{{ newUser.email }}</span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="div flex flex-row gap-44 mt-12 items-center ml-6">
            <p class="text-red-600 flex flex-row gap-1">
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
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                />
              </svg>
              Delete Account
            </p>
            <button
              @click="deleteProfile(newUser.id)"
              class="bg-red-600 border-red mr-4 flex flex-row items-center gap-4 p-1.5 rounded-md text-white w-20 font-medium"
            >
              <Spinner v-if="loading" />
              Delete
            </button>
          </div>
        </div>

        <div
          class="border-b border-gray-900/10 pb-12 hidden w-full -mt-16"
          id="settings"
        >
          <h2 class="text-base font-semibold leading-7 text-gray-900">
            Personal Information
          </h2>
          <p class="mt-1 text-sm leading-6 text-gray-600">
            Use a permanent address where you can receive mail.
          </p>

          <div
            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 w-full"
          >
            <div class="col-span-full mx-auto">
              <label
                for="photo"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Photo</label
              >
              <div class="mt-2 flex items-center gap-x-3">
                <div class="mt-1 flex items-center">
                  <img
                    v-if="newUser.image_url"
                    :src="newUser.image_url"
                    alt="Profile image"
                    class="w-64 h-40 rounded-lg"
                  />
                  <span
                    v-else
                    class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="text-gray-300 h-[80%] w-[80%]"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                      />
                    </svg>
                  </span>
                  <button
                    class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <input
                      type="file"
                      @change="onImageChoose"
                      id=""
                      class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                    />

                    Change
                  </button>
                </div>
              </div>
            </div>
            <div class="sm:col-span-6">
              <Alert v-if="Object.keys(profileError).length">
                <ul class="mt-1.5 list-disc list-inside">
                  <div v-for="(field, i) of Object.keys(profileError)" :key="i">
                    <li v-for="(error, i) in profileError[field]" :key="i">
                      {{ error }}
                    </li>
                  </div>
                </ul>
              </Alert>
              <label
                for="first-name"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Full name</label
              >
              <div class="mt-2">
                <input
                  type="text"
                  name="first-name"
                  id="first-name"
                  v-model="newUser.name"
                  autocomplete="given-name"
                  class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            <div class="sm:col-span-6">
              <label
                for="email"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Email address</label
              >
              <div class="mt-2">
                <input
                  id="email"
                  name="email"
                  type="email"
                  v-model="newUser.email"
                  autocomplete="email"
                  class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            
          </div>
          <div class="mt-6 flex items-center justify-end gap-x-6">
            <button
              @click="updateUser()"
              type="button"
              class="rounded-md bg-blue-600 flex flex-row gap-4 items-center px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              <Spinner v-if="loading" />
              Save
            </button>
          </div>
        </div>

        <div class="border-b border-gray-900/10 pb-12 hidden w-full" id="pass">
          <h2 class="text-base font-semibold leading-7 text-gray-900">
            Reset Password
          </h2>
          <p class="mt-1 text-sm leading-6 text-gray-600">
            Use a permanent address where you can receive mail.
          </p>

          <div
            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 w-full"
          >
            <div class="sm:col-span-6">
              <label
                for="first-name"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Current Password</label
              >
              <Alert v-if="errors.length">
                <ul>
                  <li>* {{ errors }}</li>
                </ul>
              </Alert>
              <div class="mt-2">
                <input
                  type="password"
                  name="first-name"
                  id="first-name"
                  v-model="password.currentPass"
                  autocomplete="given-name"
                  class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            <div class="sm:col-span-6">
              <label
                for="newPass"
                class="block text-sm font-medium leading-6 text-gray-900"
                >New Password</label
              >
              <div class="mt-2">
                <input
                  id="newPass"
                  name="newPass"
                  type="password"
                  v-model="password.newPass"
                  class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            <div class="sm:col-span-6">
              <label
                for="password"
                class="block text-sm font-medium leading-6 text-gray-900"
                >Confirm Password</label
              >
              <div class="mt-2">
                <input
                  id="password"
                  name="password"
                  type="password"
                  v-model="password.confirmed_password"
                  class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-x-6">
            <button
              type="button"
              @click="updatePass()"
              class="rounded-md bg-blue-600 flex flex-row gap-4 items-center px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              <Spinner v-if="loading" />
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </BaseCompo>
</template>


<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import Alert from "../../components/Alert.vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import Spinner from "../../components/Spinner.vue";
import { computed, ref, watch } from "vue";
import { PhotoIcon, UserCircleIcon } from "@heroicons/vue/24/solid";
const store = useStore();
const route = useRoute();
const router = useRouter();
// Assuming `store.state.user.data` is a reactive state
const user = computed(() => store.state.user.data);
// Initialize `newUser` with default values
const newUser = ref({
  id: user.value.id,
  name: user.value.name,
  email: user.value.email,
  image: user.value.image,
  image_url: user.value.image_url,
});

const password = ref({
  currentPass: null,
  newPass: null,
  confirmed_password: null,
});
const loading = ref(false);
// Watch for changes in `user` and update `newUser`
watch(user, (newValue) => {
  // Update `newUser` with values from `user`
  newUser.value.id = newValue.id;
  newUser.value.name = newValue.name;
  newUser.value.email = newValue.email;
});
const errors = ref({});
const profileError = ref({});
function onImageChoose(ev) {
  const file = ev.target.files[0];
  const reader = new FileReader();
  reader.onload = () => {
    newUser.value.image = reader.result;
    newUser.value.image_url = reader.result;
  };
  reader.readAsDataURL(file);
}
function toggle(id) {
  if (id === "settings") {
    document.getElementById("profile").classList.add("hidden");
    document.getElementById("pass").classList.add("hidden");
    document.getElementById(id).classList.remove("hidden");
  } else if (id === "pass") {
    document.getElementById("settings").classList.add("hidden");
    document.getElementById("profile").classList.add("hidden");
    document.getElementById(id).classList.remove("hidden");
  } else {
    document.getElementById("pass").classList.add("hidden");
    document.getElementById("settings").classList.add("hidden");
    document.getElementById(id).classList.remove("hidden");
  }
}
function updateUser() {
  loading.value = true;
  profileError.value = {};
  store
    .dispatch("updateUser", newUser.value)
    .then((res) => {
      if (res.status === 200) {
        router.push({
          name: "Dashboard",
        });
      }else{
      profileError.value = res.response.data.errors;

      }

      loading.value = false;
    })
    .catch((error) => {
      loading.value = false;

      profileError.value = error.response.data.errors;
    });
}
function updatePass() {
  loading.value = true;
  errors.value = {};
  store
    .dispatch("updatePass", password.value)
    .then((res) => {

       if (res.data.status === "success" || res.status === 200) {

        store.dispatch("logout").then((res) => {

          if (res.status === 200) {
            router.push({
              name: "login",
            });
          }
        });
      }else if (res.response.data.error) {
        errors.value = res.response.data.error;
      }
      loading.value = false;
    })
    .catch((error) => {
      loading.value = false;

      errors.value = error.error;
    });
}
function deleteProfile(id) {
  loading.value = true;

  store
    .dispatch("deleteUser", id)
    .then((res) => {
      if (res.data === "successfful") {
        // Assuming 'res.data' contains the success indicator
        setTimeout(() => {
          router.push({ name: "login" });
        }, 1500);
      }
      loading.value = false;
    })
    .catch((error) => {
      loading.value = false;

      console.error("Error deleting profile:", error);
    });
}
</script>
