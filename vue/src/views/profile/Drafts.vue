<template>
  <BaseCompo>
    <template v-slot:header> Drafts </template>

    <Spinner v-if="loading" />

    <div v-else class="2xl:flex 2xl:flex-row sm:flex-col">
      <div
        class="2xl:w-1/2 hidden 2xl:block sm:flex sm:justify-center sm:w-full"
      >
        <img
          src="../../../public/images/edit_message.gif"
          alt=""
          class="md:w-[35rem] md:h-[20rem] 2xl:w-[650px] 2xl:h-[700px] -mt-24 2xl:ml-20 md:ml-4 animate-fade-in-down"
        />
      </div>

      <div class="flex flex-col gap-12 justify-content h-1/2 2xl:mr-32 mt-8">
        <div
          v-if="drafts"
          class="grid 2xl:grid-cols-3 md:grid-cols-3 md:container sm:grid-cols-2 justify-center  2xl:gap-16 md:gap-8 sm:ml-6 sm:gap-8"
        >
          <div
            class="max-w-sm grid-spa w-[17rem] md:w-[15rem] p-6 bg-white border h-[11rem] border-gray-200 rounded-lg shadow white:bg-gray-800 white:border-gray-700"
            v-for="draft in drafts.data"
            :key="draft.id"
          >
            <span>
              <h5
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 white:text-white"
              >
                Draft {{ draft.id }}
              </h5>
            </span>
            <p class="mb-3 font-normal text-gray-700 white:text-gray-400">
              {{ new Date(draft.created_at).toLocaleString() }}
            </p>

            <div class="flex flex-row items-center gap-10 justify-between">
              <router-link
                :to="{
                  name: Array.isArray(draft.to) ? 'edit_group' : 'edit_sms',
                  params: { id: draft.id },
                }"
                class="inline-flex items-center  px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
              >
                Check it
                <svg
                  class="rtl:rotate-180 w-3.5 h-3.5 ms-2 md:w-2.5 md:h-2.5"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 14 10"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9"
                  />
                </svg>
              </router-link>
              <button
                @click="deleteDraft(draft.id)"
                class="p-1 text-red-700 hover:cursor-pointer hover:underline text-md"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
        <div v-else class="flex justify-center 2xl:w-[800px] mt-44">
          <span class="text-red-600 text-2xl font-semibold text-center"
            >No saved drafts !!
          </span>
        </div>

        <Paginator
          :data="drafts"
          :url="'getDrafts'"
          @loadingTrue="loading = true"
          @loadingFalse="loading = false"
          @update:data="drafts = $event"
        />
      </div>
    </div>
  </BaseCompo>
</template>

<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import { onMounted, ref, watch } from "vue";
import store from "../../store";
import Spinner from "../../components/Spinner.vue";
import Paginator from "../../components/Paginator.vue";
const drafts = ref({});
let loading = ref(true);

onMounted(() => {
  fetchDrafts();
});
function deleteDraft(id) {
  const check = confirm("are you sure to delete this draft !!");
  if (check)
    store.dispatch("deleteDraft", id).then((res) => {
      if (res.status === 200) fetchDrafts();
    });
}

function fetchDrafts() {
  store.dispatch("getDrafts").then((res) => {
    if (res.status == 200) drafts.value = res.data;

    loading.value = false;
  });
}
</script>
