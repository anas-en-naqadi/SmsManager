<template>
  <div>
    <!-- The modal -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 flex  items-center justify-center bg-gray-500 bg-opacity-75"
    >

      <div class="bg-white p-8 rounded shadow-lg w-[30rem]" @click.stop>

        <!-- Input field -->
        <div>
          <div class="flex flex-row items-center justify-between">
            <label
              class="block mb-2 text-sm font-medium text-gray-900 white:text-white"
              for="file_input"
              >Upload file</label
            >
            <button @click="closeModal" class="relative top-0 right-0 m-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
    <Alert v-if="error" >


      <div class="mt-2"> * {{ error }}</div>

    </Alert>

  
          <input
            class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 white:text-gray-400 focus:outline-none white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400"
            id="file_input"
            type="file"
            @change="handleFileUpload"
          />
        </div>
        <!-- Submit button -->
        <button
          type="button"
          @click="sendCsv()"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4"
        >
          Submit
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import Alert from './Alert.vue';
import { ref, reactive, defineProps } from 'vue'
import store from '../store';
import { useRouter } from 'vue-router';
import {defineEmits} from 'vue';
const router = useRouter();
const emit  = defineEmits(['close-modal']);
const file = ref(null)
const error = ref(" Pls load a csv file wish contains 5 columns")
const props = defineProps({
    isModalOpen: Boolean
});
// Define methods
const closeModal = (event) => {
  emit('close-modal');
  if (event.target.closest('.close-button')) {
    emit('close-modal');
  } else {
    if (!event.target.closest('.bg-white')) {
      emit('close-modal');
    }
  }
}

const handleFileUpload = (event) => {
  file.value = event.target.files[0]
}


const sendCsv = () => {
    const formData = new FormData();
  formData.append('file', file.value);

  store.dispatch('sendCsvFile', formData).then(res => {

    if(res.status === 200){
        isModalOpen = false;
        router.push({
            name:'contacts'
        });
    }else{
        error.value = res.response.data.error
    }
  }).catch(error => console.error(error));
}
</script>
