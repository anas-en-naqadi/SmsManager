<template>
  <BaseCompo>
    <template v-slot:header> Contacts </template>

    <div
      class="relative overflow-x-auto flex flex-col gap-20 -mt-24 sm:rounded-lg w-full"
    >
      <div class="flex flex-row items-center gap-[57rem] w-full -mb-6">
        <div class="2xl:w-full max-w-sm mx-auto -mb-16 animate-fade-in-down">
          <label for="underline_select" class="sr-only">Underline select</label>
          <select
            @change="filterContacts($event.target.value)"
            id="underline_select"
            class="block py-2.5 px-0 w-full text-md text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none white:text-gray-400 white:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
          >
            <option selected value="Filter by category">
              Filter by category
            </option>
            <option
              v-for="(category, index) in categories"
              :key="index"
              :value="category"
            >
              {{ category }}
            </option>
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
            <th scope="col" class="px-6 py-3">Full Name</th>
            <th scope="col" class="px-6 py-3">Phone_number</th>
            <th scope="col" class="px-6 py-3">Service_name</th>
            <th scope="col" class="px-6 py-3">Address</th>

            <th scope="col" class="px-6 py-3">Category</th>
            <th scope="col" class="px-6 py-3">Created_at</th>
            <th scope="col" class="px-6 py-3">Action</th>
          </tr>
        </thead>
        <tr v-if="loading">
          <td colspan="9" class="text-center p-4">
            <Spinner />
          </td>
        </tr>

        <tbody v-else>
          <tr
            v-for="(contact, index) in newContacts"
            :key="index"
            class="odd:bg-white text-center text-gray-800 even:bg-gray-50 border-b border-gray-200"
          >
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap"
            >
              {{ contact.id }}
            </th>
            <td class="px-6 py-4">
              {{ contact.name }}
            </td>
            <td class="px-6 py-4">
              {{ contact.phone_number }}
            </td>
            <td class="px-6 py-4">
              {{ contact.service_name }}
            </td>
            <td class="px-6 py-4">
              {{ contact.address }}
            </td>
            <td class="px-6 py-4">
              {{ contact.category || "_" }}
            </td>
            <td class="px-6 py-4">
              {{ formatDate(contact.created_at) }}
            </td>

            <td
              class="px-6 py-4 flex flex-row gap-2 items-center justify-center"
            >
              <button
                @click="deleteContact(contact.id)"
                class="font-medium text-red-600 hover:underline"
              >
                Delete
              </button>
              <router-link
                :to="{ name: 'updateContact', params: { id: contact.id } }"
                class="font-medium text-blue-600 hover:underline"
              >
                Edit
              </router-link>
            </td>
          </tr>
          <tr v-if="!newContacts.length">
            <td colspan="8" class="text-center p-4">
              <span class="text-gray-500">No Saved Contacts</span>
            </td>
          </tr>
        </tbody>
      </table>
      <Paginator
        :data="store.state.contacts.data"
        :url="'getAllContacts'"
        v-if="newContacts.length > 10"
        @update:data="contacts = $event"
      />
    </div>
  </BaseCompo>
</template>


<script setup>
import SearchInput from "../../components/SearchInput.vue";
import BaseCompo from "../../components/BaseCompo.vue";
import Spinner from "../../components/Spinner.vue";
import Paginator from "../../components/Paginator.vue";

import { computed, ref, watch, onMounted } from "vue";

import store from "../../store";

const loading = computed(() => store.state.contacts.loading);
const contacts = computed(() => store.state.contacts.data.data);
const newContacts = ref([]);
const categories = ref([]);

onMounted(() => {
  store.dispatch("getAllContacts");
});
watch(contacts, (newValue, oldValue) => {
  newContacts.value = [...newValue];
  categories.value = [...new Set(newValue.filter((c) => c.category != ""))];
});

function deleteContact(id) {
  if (confirm("Are you Sure to delete this Contact")) {
    store.dispatch("deleteContact", id).then((res) => {
      store.dispatch("getAllContacts");
      loading.value = false;
    });
  }
}
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

function filterContacts(text) {
  if (text === "Filter by category") {
    newContacts.value = [...Object.values(contacts.value)];
  } else {
    newContacts.value = [
      ...new Set(
        Object.values(contacts.value).filter((c) => c.category == text)
      ),
    ];
  }
}
</script>
