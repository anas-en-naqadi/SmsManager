<template>

    <div class="flex justify-center mt-5">

          <nav
            aria-label="Pagination"
            v-if="data.data.length"

            class="relative z-0 inline-flex justify-center rounded-md shadow-sm"
          >

            <a
              v-html="link.label"
              v-for="(link, index) in data.links"
              :key="index"
              :disabled="!link.url"
              href="#"
              aria-current="page"
              class="relative inline-flex items-center px-4 py-2 border text-sm"
              :class="[
                link.active
                  ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                index === 0 ? 'rounded-1-md' : '',
                index === data.links.length - 1 ? 'rounded-r-md' : '',
              ]"
              @click="getForPage($event, link)"

            >
            </a>
          </nav>
        </div>
</template>

<script setup>
import store from "../store";
import { defineProps, defineEmits,watch,ref } from "vue";

const emit = defineEmits(['loadingTrue',,'loadingFalse','update:data']);
const props = defineProps({
  data: Object,
  url: String
});


function getForPage(ev, link) {
 emit('loadingTrue');
  ev.preventDefault();
  if (!link.url || link.active) {
    emit('loadingFalse');
    return;
  } else {

    store.dispatch(props.url, { url: link.url }).then((res) => {


      if (res.status == 200)    emit('update:data', res.data);

    });
  }
  emit('loadingFalse');
}
</script>

