<template>
  <BaseCompo>
    <template v-slot:header> SMS Services </template>

    <div
      class="relative overflow-x-auto flex flex-col gap-20 -mt-24 sm:rounded-lg"
    >
      <SearchInput class="w-full" />
      <table
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 animate-fade-in-down"
      >
        <thead
          class="text-xs text-gray-700 font-bold uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300 text-center"
        >
          <tr>
            <th scope="col" class="px-6 py-3">ID</th>
            <th scope="col" class="px-6 py-3">Service Name</th>
            <th scope="col" class="px-6 py-3">Key</th>
            <th scope="col" class="px-6 py-3">Token</th>
            <th scope="col" class="px-6 py-3">Created_at</th>
            <th scope="col" class="px-6 py-3">Phone Number</th>
            <th scope="col" class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tr v-if="loading">
          <td colspan="7" class="text-center p-4">
            <Spinner />
          </td>
        </tr>

        <tr v-if="services.length === 0">
          <td colspan="7" class="text-center p-4">
            <span class="text-gray-500">No Saved SMS Services</span>
          </td>
        </tr>
        <tbody v-else>
          <tr
            v-for="(service, index) in services.data"
            :key="index"
            class="odd:bg-white text-center text-gray-800 even:bg-gray-50 border-b border-gray-200"
          >
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap"
            >
              {{ service.id }}
            </th>
            <td class="px-6 py-4">
              {{ service.service_name }}
            </td>
            <td class="px-6 py-4">
              {{ service.service_key }}
            </td>
            <td class="px-6 py-4">
              {{ service.service_token }}
            </td>
            <td class="px-6 py-4">
              {{ formatDate(service.created_at) }}
            </td>
            <td class="px-6 py-4">
              {{ service.phone_number || "-" }}
            </td>
            <td
              class="px-6 py-4 items-center justify-center flex flex-row gap-5"
            >
              <a
                @click="deleteService(service.id)"
                href="#"
                class="font-medium text-red-600 hover:underline"
                >Delete</a
              >
              <router-link
                :to="{ name: 'smsupdate', params: { id: service.id } }"
                class="font-medium text-blue-600 hover:underline"
              >
                Edit
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
         <Paginator
         v-if="services.length >10"
        :data="services"
        :url="'getAllServices'"
        @update:data="services= $event"
        @loading="loading = false"
      />
    </div>
  </BaseCompo>
</template>


<script setup>
import SearchInput from "../../components/SearchInput.vue";
import BaseCompo from "../../components/BaseCompo.vue";
import Spinner from "../../components/Spinner.vue";
import Paginator from "../../components/Paginator.vue";
import { ref } from "vue";
import store from "../../store";
const loading = ref(true);
const services = ref({});
store.dispatch("getAllServices").then((res) => {
  services.value = res.data;
  loading.value = false;
});
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
function deleteService(id) {
  if (confirm("Are you sure to delete this Service !")) {
    store.dispatch("deleteService", id).then((res) => {
      store.dispatch("getAllServices").then((res) => {
        services.value = res.data;
        loading.value = false;
      });
    });
  }
}
</script>
