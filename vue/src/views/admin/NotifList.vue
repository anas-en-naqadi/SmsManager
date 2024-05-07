<template>
  <BaseCompo>
    <template v-slot:header>
      <div
        class="flex flex-row max-w-full 2xl:gap-96 justify-between items-center"
      >
        <div class="w-full">Notifications</div>
        <div class="w-full">
          <button
            @click="exportCsv()"
            type="button"
            class="2xl:ml-[37rem] text-green-700 hover:text-white border border-green-700 hover:bg-emerald-600 focus:ring-1 focus:outline-none focus:ring-green-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 white:border-green-500 white:text-green-500 white:hover:text-white white:hover:bg-green-600 dark:focus:ring-green-800"
          >
            Export As Csv
          </button>
        </div>
      </div>
    </template>

    <div
      class="relative overflow-x-auto h-[45rem] flex flex-col gap-20 -mt-24 sm:rounded-lg"
    >
      <div class="flex flex-row items-center gap-[57rem] w-full -mb-6 animate-fade-in-down">
        <div class="2xl:w-full max-w-sm mx-auto -mb-16">
          <label for="underline_select" class="sr-only">Underline select</label>
          <select
            @change="filterMessages($event.target.value)"
            id="underline_select"
            class="block py-2.5 px-0 w-full text-md text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
          >
            <option selected value="Filter by category">Filter</option>
            <option value="one">messages to one contact</option>
            <option value="many">messages to many contact</option>
            <option value="one_scheduled">messages to one contact && scheduled</option>
            <option value="many_scheduled">messages to many contact && scheduled</option>
          </select>
        </div>

        <SearchInput
          class="w-full"
          :var="notFound"
          @update:var="notFound = $event"
        />
      </div>
      <table
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 animate-fade-in-down"
      >
        <thead
          class="text-xs text-gray-700 font-bold uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300 text-center"
        >
          <tr>
            <th scope="col" class="px-6 py-3">ID</th>
            <th scope="col" class="px-6 py-3">From</th>
            <th scope="col" class="px-6 py-3">To</th>
            <th scope="col" class="px-6 py-3">Message</th>
            <th scope="col" class="px-6 py-3">service</th>
            <th scope="col" class="px-6 py-3">scheduled_at</th>
            <th scope="col" class="px-6 py-3">status</th>
            <th scope="col" class="px-6 py-3">created_at</th>
            <th scope="col" class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tr v-if="loading">
          <td colspan="9" class="text-center p-4">
            <Spinner />
          </td>
        </tr>
        <tr v-if="notifications.length === 0">
          <td colspan="9" class="text-center p-4">
            <span class="text-gray-500">No Sent Notifications</span>
          </td>
        </tr>
        <tbody v-else>
          <tr
            v-for="(notification, index) in notifications"
            :key="index"
            class="odd:bg-white overflow-y-scroll text-center text-gray-800 even:bg-gray-50 border-b border-gray-200"
          >
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap"
            >
              {{ index + 1 }}
            </th>
            <td class="px-6 py-4">
              {{ notification.from }}
            </td>
            <td class="px-6 py-4" v-if="Array.isArray(notification.to)">
              {{
                notification.to
                  .map((number) => number.replace(/\[|\]|"/g, ""))
                  .join(", ")
              }}
            </td>

            <td class="px-6 py-4" v-else>
              {{ notification.to }}
            </td>
            <td class="px-6 py-4">
              {{ notification.message }}
            </td>

            <td class="px-6 py-4">
              {{ notification.service }}
            </td>
            <td class="px-6 py-4">
              {{ notification.scheduled_at ? notification.scheduled_at : "-" }}
            </td>
            <td class="px-6 py-4">
              {{ notification.status }}
            </td>
            <td class="px-6 py-4">
              {{ notification.created_at }}
            </td>
            <td class="px-6 py-4">
              <button
                @click="deleteNotif(notification.id)"
                class="font-medium text-red-600 hover:underline"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <Paginator
        :data="links"
        :url="'getAllNotificationss'"
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
let links;
const notifications = ref({});
const loading = ref(true);
const filteredNotifications = ref({});
store.dispatch("getAllNotifications").then((response) => {
  store.dispatch("getAllNotificationGroup").then((res) => {
    notifications.value = [...response.data, ...res.data];
    filteredNotifications.value=[...notifications.value];
    loading.value = false;
  });
});
// store.dispatch("getAllNotificationss").then((res) => {
//     console.dir(res)
//     links = [...res.data.notifications.links,...res.data.notificationGroups.links];
//     links = Array.from(new Set(links));
//     notifications.value = [...res.data.notifications.data, ...res.data.notificationGroups.data];
//     loading.value = false;
//   });
function deleteNotif(id) {
  if (confirm("Are you sure to delete this Notification")) {
    store.dispatch("deleteNotif", id).then((res) => {
      if (res.status === 200) {
        store.dispatch("getAllNotifications").then((response) => {
          store.dispatch("getAllNotificationGroup").then((res) => {
            notifications.value = [...response.data, ...res.data];

            loading.value = false;
          });
        });
      }
    });
  }
}
function filterMessages(select) {

  if (select === "many")
    notifications.value = filteredNotifications.value.filter((c) =>
      Array.isArray(c.to)
    );
  else if (select === "one")
    notifications.value = filteredNotifications.value.filter(
      (c) => !Array.isArray(c.to)
    );
      else if (select === "many_scheduled")
    notifications.value = filteredNotifications.value.filter(
      (c) => Array.isArray(c.to) && c.scheduled_at
    );
        else if (select === "one_scheduled")
    notifications.value = filteredNotifications.value.filter(
      (c) => !Array.isArray(c.to) && c.scheduled_at
    );



  else notifications.value = filteredNotifications.value;

}
function exportCsv() {
  store
    .dispatch("exportCsvNotification")
    .then()
    .catch((error) => error);
}
</script>
