<template>

 <div>

    <div class=" m-auto  mt-[10%]  flex flex-row items-center justify-center gap-12 w-[60rem] border shadow-lg rounded-lg h-[23rem] p-12">
    <form class=" w-full ml-12">
      <div class="mt-8">
        <div v-if="error">
          <Alert id="alert">
            <ul class="mt-1.5 list-disc list-inside">
             <li>{{ error }}</li>
            </ul>
          </Alert>
        </div>


        <label
          for="email"
          class="block text-sm font-medium leading-6 text-gray-900"
          >Email address</label
        >
            <div
          id="success"
          class="flex hidden p-4 mt-2 -mb-2 text-sm text-green-600 rounded-lg bg-green-50"
          role="alert"
        ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
</svg>

         verification code sent successfully check your inbox, redirecting ...
        </div>
        <div class="mt-2">
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            autofocus
            autocomplete="email"
            required=""
            class="block w-full rounded-md border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>


      <div>
        <Button
          :loading="loading"
          class="mb-4"
          @trigger-event="sendVerificationCode()"
          :string="'Send email verification'"
        />
      </div>
    </form>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm ">
      <img
        class="mx-auto h-34 w-34 -mb-10 "
        src="../../../public/images/logo.png"
        alt="Your Company"
      />
      <h2
        class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
      >
        SMS MANAGER
      </h2>
    </div>
  </div>
 </div>
</template>

<script setup>
import store from "../../store";
import { ref } from "vue";
import {useRouter,useRoute} from "vue-router";
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";
import CryptoJS from 'crypto-js'
const error = ref("");
const email = ref("");
const loading = ref(false);

const router= useRouter();
async function sendVerificationCode()  {
                loading.value = true ;

    if (!email.value) {
      error.value = "Email address is required";
      return;
    }
await store.dispatch("sendVerificationCode", email.value).then(res=>{
        if(res.status === 200 && res.data.status === 'success'){
            error.value = "";
            document.getElementById("success").classList.remove("hidden");
            const encryptedToken = CryptoJS.AES.encrypt(res.data.token, "code").toString();
            setTimeout(()=>{
                loading.value = false ;
                router.push({name:"code",query:{t:btoa(encryptedToken)}});
            },2000);
        }else{
                loading.value = false ;

            error.value = res.response.data.message
        }
    });


};

</script>
