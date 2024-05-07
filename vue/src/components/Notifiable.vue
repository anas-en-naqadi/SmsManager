<template>
  <div
    v-if="notifiable"
    id="toast-notification"
    class="w-full max-w-md p-4 text-gray-900 bg-white rounded-lg shadow white:bg-gray-800 white:text-gray-300"
    role="alert"
  >

    <div class="flex items-center mb-3 z-50">
      <span class="mb-1 text-sm font-semibold text-gray-900 white:text-white"
        >New notification</span
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
    <div class="flex items-center flex-col" v-if="StatusNotif.length">
      <div
        class="ms-3 text-sm font-normal m-1.5"
        v-for="(status, index) in StatusNotif"
        :key="index"
      >
      {{ StatusNotif }}
        <div class="text-sm font-semibold text-gray-900 white:text-white">
          the message sent To {{ status.data.to }} in
          {{ status.data.scheduled_at }} has been
          <span
            :class="[
              status.data.status === 'sent' ? 'text-green-500' : 'text-red-500',
            ]"
            >{{
              status.data.status === "sent"
                ? "successfully sent"
                : "not sent pls check your Credentials or contact number "
            }}</span
          >
        </div>

        <span class="text-xs font-medium text-blue-600 white:text-blue-500"
          >a few seconds ago</span
        >
      </div>
      <hr class="m-2">
    </div>
    <div v-else class="text-gray-600 text-center font-bold">
      No Notifications !!
    </div>
  </div>
</template>


<script setup>
import { defineProps } from "vue";

const props = defineProps(() => {
  return {
    notifiable: Boolean,
    StatusNotif: Object,
  };
});
</script>
