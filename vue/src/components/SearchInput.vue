<template>
  <form class="2xl:max-w-md md:max-w-sm sm:max-w-xs mx-auto relative -bottom-8 z-10 animate-fade-in-down">
    <label
      for="default-search"
      class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
      >Search</label
    >
    <div class="relative">
      <div
        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
      >
        <svg
          class="w-4 h-4 text-gray-500 dark:text-gray-400"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 20 20"
        >
          <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
          />
        </svg>
      </div>
      <input
        v-model="searchQuery"
        type="search"
        id="default-search"
        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Search Names, services..."
        required
      />
      <button
        @click.prevent="searchSomeThing"
        type="button"
        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Search
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch ,defineProps,defineEmits} from "vue";
const { emit } = defineEmits(['update:var']);
const props = defineProps(()=>{
    return {
        var:Boolean
    }
})
const searchQuery = ref("");

watch(searchQuery, () => {
  if (!searchQuery.value) {
    let trs = document.querySelectorAll("tr");
    trs.forEach((tr, index) => {
      if (index > 0) {
        tr.classList.remove("hidden");
      }
    });
  }
});

function searchSomeThing() {
  const word = searchQuery.value.toLowerCase();
  let trs = document.querySelectorAll("tr");
  trs.forEach((tr, index) => {
    if (index === 0) {
      return;
    }
    const tds = tr.querySelectorAll("td");
    let rowVisible = false;
    for (let td of tds) {
      if (td.textContent.toLowerCase().includes(word)) {
        rowVisible = true;
        break;
      }
    }
    if (rowVisible) {
      tr.classList.remove("hidden");
    } else {
      tr.classList.add("hidden");
    }
  });
if(!rowVisible) emit('udpate:var',true);
}
</script>
