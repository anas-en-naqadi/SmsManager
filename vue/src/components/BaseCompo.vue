<template>
  <div>
    <header class="bg-white shadow">
      <div class="mx-auto text-lg px-4 py-4 sm:px-6 lg:px-8">
        <slot name="header"></slot>
      </div>
    </header>
    <main>
      <div class=" max-auto w-full py-24 px-4 sm:px-6 lg:px-8 ">
        <div class="px-4 py-6 sm:px-0 ">
          <slot></slot>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from "vue";
const isOpen = ref(false);
</script>
