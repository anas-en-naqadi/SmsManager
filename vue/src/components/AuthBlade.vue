
<template>
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-nd w-full space-y-8">
    <div>
            <h2 class="mt-6 text-center text-3xl font-extralight text-black">{{ title }}</h2>
    </div>

<router-view></router-view>

</div>
  </div>
</template>


<script setup>
const {title} = defineProps({
  title:String
})
const emit =defineEmits(['submit']);
</script>
<style scoped>

</style>
