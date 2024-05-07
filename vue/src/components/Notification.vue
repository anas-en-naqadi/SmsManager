<template >
  <div
    v-if="notification.show"
    :class="[notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
    class="fixed w-[300px] left-4 bottom-4 py-2 px-4 z-50 text-white animated-fade-in-down"
  >
    {{ notification.message }}
  </div>
</template>

<script setup>
import store from "../store";
import { computed } from "vue";
const notification = computed(() => {
  return store.state.notification;
});
</script>
