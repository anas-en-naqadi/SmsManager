<template>
  <div class="min-h-full">
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center justify-center gap-4">
            <div class="flex-shrink-0 flex-row gap-3 flex items-center">
              <img
                class="h-11 w-12 -ml-2 rounded-full"
                src="../../public/images/logo1.png"
                alt="Your Company"
              />
              <h2 class="text-white font-medium">SMS Manager</h2>
            </div>
            <div class="hidden xl:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <router-link
                  v-for="item in navigation"
                  :key="item.name"
                  :to="item.to"
                  :active-class="[
                    this.$route.name === item.to.name
                      ? 'bg-blue-600 text-white'
                      : '',
                  ]"
                  :class="[
                    this.$route.name === item.to.name
                      ? ''
                      : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                    'rounded-md px-3 py-2 text-sm font-medium',
                  ]"
                  >{{ item.name }}</router-link
                >
              </div>
            </div>
          </div>

          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <button
                type="button"
                class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
              >
                <span class="absolute -inset-1.5" />
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
              </button>

              <!-- Profile dropdown -->
              <Menu as="div" class="relative ml-3 xl:block md:hidden">
                <div class="flex items-center">
                  <div class="flex flex-col items-center gap-44">
                    <div class="mb-5">
                      <button
                        @click="
                          notifiable = !notifiable;
                          setRead_at();
                        "
                        type="button"
                        class="relative inline-flex items-center p-3 mr-5 mt-6 text-sm font-medium text-center text-white rounded-lg hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300 white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
                      >
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
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                          />
                        </svg>

                        <span class="sr-only">Notifications</span>
                        <div
                          class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-0 -end-1 dark:border-gray-900"
                        >
                          {{ counter }}
                        </div>
                      </button>
                    </div>
                    <div
                      v-if="notifiable"
                      id="toast-notification"
                      class="absolute top-24 z-50 md:w-88 p-4 text-gray-900 bg-white rounded-lg shadow-md white:bg-gray-800 white:text-gray-300"
                      role="alert"
                    >
                      <div class="flex items-center mb-3 z-50">
                        <span
                          class="mb-1 text-md font-semibold text-gray-900 white:text-white"
                          >New notifications</span
                        >
                        <button
                          type="button"
                          @click="notifiable = !notifiable"
                          class="ms-auto -mx-1.5 -my-1.5 bg-white justify-center items-center flex-shrink-0 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 white:text-gray-500 white:hover:text-white white:bg-gray-800 white:hover:bg-gray-700"
                          data-dismiss-target="#toast-notification"
                          aria-label="Close"
                        >
                          <span class="sr-only">Close</span>
                          <svg
                            class="w-3 h-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14"
                          >
                            <path
                              stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                            />
                          </svg>
                        </button>
                      </div>
                      <hr
                        class="mb-2 text-gray-900 font-bold w-[20rem] -ml-4"
                      />
                      <div
                        class="flex items-center flex-col md:h-52 overflow-scroll"
                        v-if="StatusNotif.length"
                      >
                        <div
                          class="ms-3 text-sm font-normal m-1.5"
                          v-for="(status, index) in StatusNotif"
                          :key="index"
                        >
                          <div
                            class="text-sm text-gray-700 font-semibold white:text-white"
                          >
                            the message sent To {{ status.data.to }} in
                            {{ status.data.scheduled_at }} has been
                            <span
                              :class="[
                                status.data.status === 'sent'
                                  ? 'text-green-500'
                                  : 'text-red-500',
                              ]"
                              >{{
                                status.data.status === "sent"
                                  ? "successfully sent !!"
                                  : "not sent pls check your Credentials or contact number !! "
                              }}</span
                            >
                          </div>

                          <span
                            class="text-xs font-medium text-blue-600 white:text-blue-500"
                          >
                            {{ formatNotificationTime(status.created_at) }}
                          </span>

                          <hr class="mt-2" />
                        </div>
                      </div>

                      <div
                        v-else
                        class="text-gray-600 text-center text-sm font-semibold"
                      >
                        No Notifications !!
                      </div>
                      <div
                        v-if="StatusNotif.length"
                        @click="deleteAllNotifiable()"
                        class="text-gray-600 text-center font-bold text-sm cursor-pointer hover:underline p-1"
                      >
                        Clear All
                      </div>
                    </div>
                  </div>
                  <MenuButton
                    class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                  >
                    <div v-if="data" class="flex flex-col gap-2 mr-2 p-2">
                      <div
                        class="text-base font-medium leading-none text-white"
                      >
                        {{ data.name }}
                      </div>
                      <div
                        class="text-sm font-medium leading-none text-gray-400"
                      >
                        {{ data.email }}
                      </div>
                    </div>

                    <span class="absolute -inset-1.5" />
                    <span class="sr-only">Open user menu</span>
                    <img
                      v-if="user.image_url"
                      :src="user.image_url"
                      alt=""
                      class="w-10 h-10 rounded-full"
                    />
                    <svg
                      v-else
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-10 h-10 text-white"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                      />
                    </svg>
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="absolute right-0 z-50 mt-1 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <MenuItem as="div" v-slot="{ active }">
                      <router-link
                        :to="{ name: 'Profile' }"
                        :class="[
                          'block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200',
                        ]"
                        >Profile</router-link
                      >
                      <router-link
                        :to="{ name: 'drafts' }"
                        :class="[
                          'block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200',
                        ]"
                        >Drafts</router-link
                      >
                      <a
                        @click="logout"
                        :class="[
                          'block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200',
                        ]"
                        >Sign out</a
                      >
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="-mr-2 flex xl:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton
              class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-1 text-white hover:bg-gray-700 hover:text-white md:-mr-3 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
            >
              <span class="absolute -inset-0.5" />
              <span class="sr-only">Open main menu</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-10 h-10 -mr-6  md:w-11 md:h-11"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                />
              </svg>

              <Bars3Icon v-if="!open" class="bloc h-6 w-6" aria-hidden="true" />
              <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="xl:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">

          <router-link
            v-for="item in navigation"
            :key="item.name"
             :active-class="[
                    this.$route.name === item.to.name
                      ? 'bg-blue-600 text-white'
                      : '',
                  ]"
            :to="item.to"
            :class="[
              this.$route.name === item.to.name
                ? ''
                : 'text-gray-300 hover:bg-gray-700 hover:text-white',
              'block rounded-md px-3 py-2 text-base font-medium',
            ]"
            :aria-current="item.current ? 'page' : undefined"
            >{{ item.name }}</router-link
          >
        </div>

        <div class="border-t border-gray-700 pb-3 pt-4">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <img
                v-if="user.image_url"
                :src="user.image_url"
                alt=""
                class="w-10 h-10 rounded-full"
              />
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-10 h-10 text-white"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
              </svg>
            </div>
            <div class="ml-3">
              <div class="text-base mb-1 font-medium leading-none text-white">
                {{ user.name }}
              </div>
              <div class="text-sm font-medium leading-none text-gray-400">
                {{ user.email }}
              </div>
            </div>

            <button
              type="button"
              class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
            >
              <span class="absolute -inset-1.5" />
              <span class="sr-only">View notifications</span>
              <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-1.5 space-y-1 px-2 py-1">
            <router-link
              :to="{ name: 'Profile' }"
              class="black px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 cursor-pointer"
              >Profile</router-link
            >
            <router-link
              :to="{ name: 'drafts' }"
              class="black px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 cursor-pointer"
              >Drafts</router-link
            >
            <a
              @click="logout"
              class="black px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 cursor-pointer"
              >Sign out</a
            >
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <router-view></router-view>
    <Notification />
  </div>
</template>
<script>
// import { Bars3Icon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { useStore } from "vuex";
import { computed, ref, watch, onMounted } from "vue";
import { useRouter } from "vue-router";
import Notification from "../components/Notification.vue";

import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from "@headlessui/vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Notification,
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const data = computed(() => store.state.user.data);
    onMounted(() => {
      store.dispatch("getUser");
      store.dispatch("getsmStatusNotifications");
      //        Echo.channel("notifications").listen(
      //     "TriggerNotifications",
      //     (message) => {
      //       store.dispatch("getsmStatusNotifications");
      //       console.log("notif", message);
      //       console.log('Notification received:', notification);

      //     }
      //   );
    });

    function formatNotificationTime(receivedTimeStr) {
      const now = new Date();
      const receivedTime = new Date(receivedTimeStr);
      const delta = Math.floor((now - receivedTime) / 1000); // Calculate time difference in seconds
      if (delta < 60) {
        return "a few seconds ago";
      } else if (delta < 3600) {
        const minutes = Math.floor(delta / 60);
        return `${minutes} minute${minutes === 1 ? "" : "s"} ago`;
      } else if (delta < 86400) {
        const hours = Math.floor(delta / 3600);
        return `${hours} hour${hours === 1 ? "" : "s"} ago`;
      } else if (delta < 2592000) {
        // Less than 30 days
        const days = Math.floor(delta / 86400);
        return `${days} day${days === 1 ? "" : "s"} ago`;
      } else {
        // For older notifications, just show the date (assuming "YYYY-MM-DD" format)
        const formattedDate = receivedTime.toISOString().slice(0, 10);
        return formattedDate;
      }
    }

    function logout() {
      store.dispatch("logout").then((res) => {
        if (res.data.status === "success" && res.status === 200) {
          router.push({
            name: "login",
          });
        }
      });
    }
    function deleteAllNotifiable() {
      store
        .dispatch("deleteAllNotifiable")
        .then((res) => store.dispatch("getsmStatusNotifications"));
    }
    const notifiable = ref(false);

    const StatusNotif = computed(() => store.state.smStatusNotifications);
    const counter = ref(0);
    watch(StatusNotif, (newValue, oldValue) => {
      // Update the counter with the new length of StatusNotif
      counter.value = newValue.filter((n) => n.read_at == null).length;
    });
    function setRead_at() {
      counter.value = 0;
      const now = new Date();
      store.dispatch("setReadAt", now);
    }
    return {
      user: computed(() => store.state.user.data),
      logout,
      data,
      deleteAllNotifiable,
      notifiable,
      formatNotificationTime,
      StatusNotif,
      setRead_at,
      counter,
      notifLength: StatusNotif.length,
      navigation: [
        { name: "Dashboard", to: { name: "Dashboard" }, current: false },
        { name: "Send sms", to: { name: "home" }, current: false },

        { name: "Add Contact", to: { name: "addContact" }, current: false },
        { name: "Sms Settings", to: { name: "smsSettings" }, current: false },
      ],
      userNavigation: [
        { name: "Your Profile", href: "#" },
        { name: "drafts", href: "#" },
        { name: "logout", href: "#" },
      ],
    };
  },
  methods: {},
};
</script>


