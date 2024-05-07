<template>
  <BaseCompo>
    <template v-slot:header> Dashboard </template>
    <Spinner v-if="!loading" />
    <div
      v-else
      class="grid grid-cols-1 animate-fade-in-down -mt-4 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5 text-gray-700 "
    >
      <div
        style="animation-delay: 1s"
        class="bg-gray-100 rounded h-56 shadow-md p-3 text-center flex flex-col order-1 lg:order-2 2xl:order-1"
      >
        <div class="flex flex-row justify-center gap-6 items-center">
          <h3 class="text-2xl font-semibold">Total sending Notifications</h3>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-12 h-12 mt-3"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"
            />
          </svg>
        </div>

        <div
          class="text-8xl text-blue-700 font-semibold flex-1 flex items-center justify-center"
        >
          {{ data.totalNotif || 0 }}
        </div>
      </div>
      <div
        style="animation-delay: 1s"
        class="bg-gray-100 rounded h-56  shadow-md p-3 text-center flex flex-col order-1 lg:order-2 2xl:order-3"
      >
        <div class="flex flex-row justify-center gap-6 items-center">
          <h3 class="text-2xl font-semibold">Total Contacts</h3>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-12 h-12 mt-3"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
            />
          </svg>
        </div>

        <div
          class="text-8xl text-blue-700 font-semibold flex-1 flex items-center justify-center"
        >
          {{ data.totalContact }}
        </div>
      </div>
      <div
        style="animation-delay: 1s"
        class="bg-gray-100 rounded h-56  shadow-md p-3 text-center flex flex-col order-1 lg:order-4 2xl:order-4"
      >
        <div class="flex flex-row justify-center gap-6 items-center mt-1">
          <h3 class="text-2xl font-semibold">Total Scheduled Notifications</h3>
        <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-12 h-12"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"
                />
        </svg>
        </div>

        <div
          class="text-8xl text-blue-700 font-semibold flex-1 flex items-center justify-center"
        >
          {{ data.totalScheduled_at }}
        </div>
      </div>
      <div
        style="animation: delay 0.2s"
        class="bg-gray-100  shadow-md p-3 text-center flex flex-col order-2 lg:order-4 2xl:order-2"
      >
        <div class="flex flex-row justify-center gap-6 items-center mt-2">
          <h3 class="text-2xl font-semibold">Total used SMS services</h3>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-10 h-10 mt-1.5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"
            />
          </svg>
        </div>

        <div
          class="text-8xl font-semibold text-blue-700 flex-1 flex items-center justify-center"
        >
          {{ data.usedService.length || 0 }}
        </div>
      </div>

      <div
        class="row-span-5 order-3 bg-gray-100 rounded  shadow-md p-8 lg:order-1 2xl:order-5"
      >
        <div class="flex justify-between items-center mb-3">
          <h3 class="text-2xl font-semibold">Latest Notification</h3>
          <router-link
            to="/sms/notifications"
            class="text-sm text-blue-500 hover:decoration-blue-500"
            >View all
          </router-link>
        </div>
        <div v-if="!data.latestNotif" class="mx-auto text-center mt-12">
          <span class="text-blue-600 text-lg font-bold"
            >No Notification was created !!</span
          >
        </div>
        <div v-else>
          <h3 class="font-bold text-xl mb-3">{{ data.latestNotif.title }}</h3>
          <div class="flex justify-between text-lg mb-1">
            <div>Create Date:</div>
            <div>{{ data.latestNotif.created_at }}</div>
          </div>
          <div class="flex justify-between text-lg mb-1">
            <div>From:</div>
            <div>{{ data.latestNotif.from }}</div>
          </div>
          <div class="flex justify-between text-lg mb-1">
            <div>To:</div>
            <div>{{ "+" + data.latestNotif.to }}</div>
          </div>
          <div class="flex justify-between text-lg mb-1">
            <div>Message:</div>
            <div>{{ data.latestNotif.message }}</div>
          </div>
          <div class="flex justify-between text-lg mb-3">
            <div>used Service:</div>
            <div>{{ data.latestNotif.service }}</div>
          </div>
          <div class="flex justify-between"></div>
        </div>
      </div>
      <div
        style="animation-delay: 0.3s"
        class="row-span-5 rounded order-4 bg-gray-100  shadow-md p-6 lg:order-3 2xl:order-6"
      >
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-2xl font-semibold">Latest SMS Services</h3>

          <router-link
            to="/sms/services"
            class="text-sm text-blue-500 hover:decoration-blue-500"
            >View all
          </router-link>
        </div>
        <div
          v-if="data.usedService.length === 0"
          class="mx-auto mt-12 text-center"
        >
          <span class="text-blue-600 font-bold text-lg"
            >No SMS Service was created !!</span
          >
        </div>

        <a
          v-else
          href="#"
          v-for="service in data.usedService"
          :key="service.id"
          class="block p-2 w-full hover:bg-gray-50"
        >
          <div class="font-semibold">{{ service.service_name }}</div>
          <small
            >Service Stored at :
            <i class="font-semibold">{{
              formatDate(service.created_at)
            }}</i></small
          >
        </a>
      </div>
      <div
        style="animation-delay: 0.3s"
        class="row-span-5 rounded order-4 bg-gray-100  shadow-md p-6 lg:order-3 2xl:order-8"
      >
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-xl font-semibold">Latest Scheduled Notifications</h3>

          <router-link
            to="/sms/calendar"
            class="text-sm text-blue-500 hover:decoration-blue-500"
            >View all
          </router-link>
        </div>
        <div
          v-if="data.usedService.length === 0"
          class="mx-auto mt-12 text-center"
        >
          <span class="text-blue-600 font-bold text-lg"
            >No scheduled notifications was created yet!!</span
          >
        </div>

        <a
          v-else
          href="#"
          v-for="notif in data.latestScheduledNotif"
          :key="notif.id"
          class="block p-2 w-full hover:bg-gray-50"
        >
          <div class="font-semibold">{{ notif.from }}</div>
          <small
            >Service Stored at :
            <i class="font-semibold">{{ formatDate(notif.created_at) }}</i>

            <span
              class="ml-2"
              :class="[
                notif.status === 'sent'
                  ? 'text-emerald-500 font-bold'
                  : notif.status === 'pending'
                  ? 'text-orange-500 font-bold'
                  : 'text-red-500 font-bold',
              ]"
              >{{ notif.status }}</span
            >
          </small>
        </a>
      </div>
      <div
        style="animation-delay: 0.3s"
        class="row-span-5 rounded order-4 bg-gray-100  shadow-md p-6 lg:order-3 2xl:order-7"
      >
        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-2xl font-semibold">Latest Saved Contacts</h3>

          <router-link
            to="/contacts"
            class="text-sm text-blue-500 hover:decoration-blue-500"
            >View all
          </router-link>
        </div>
        <div v-if="!data.usedService.length" class="mx-auto mt-12 text-center">
          <span class="text-blue-600 font-bold text-lg"
            >No SMS Service was created !!</span
          >
        </div>
        <a
          v-else
          href="#"
          v-for="contact in data.latestContacts"
          :key="contact.id"
          class="p-2 w-full mt-2 flex flex-col hover:bg-gray-50"
        >
          <div class="font-semibold">{{ contact.name }}</div>
          <small
            >Phone number :
            <i class="font-semibold">{{ contact.phone_number }}</i></small
          >
          <small
            >contact Stored at :
            <i class="font-semibold">{{
              formatDate(contact.created_at)
            }}</i></small
          >
        </a>
      </div>
    </div>
  </BaseCompo>
</template>


<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import Spinner from "../../components/Spinner.vue";

import { useStore } from "vuex";
import { computed } from "vue";
const store = useStore();
store.dispatch("getDashboard");
  store.dispatch("getsmStatusNotifications");

const data = computed(() => store.state.dashboard.data);
const loading = computed(() => store.state.dashboard.loading);

function formatDate(timestamp) {
  // Convert the timestamp to a Date object
  const date = new Date(timestamp);

  // Format the date to only a date string
  const formattedDate = date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  });

  return formattedDate;
}
</script>


