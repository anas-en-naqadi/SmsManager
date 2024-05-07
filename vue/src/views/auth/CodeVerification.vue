<template>
  <div>
    <div
      class="m-auto mt-[10%] flex 2xl:flex-row items-center justify-center gap-12 w-[60rem] border shadow-lg rounded-lg h-[23rem] p-12"
    >
      <form class="w-full ml-12">
        <div class="mt-8">
          <label
            for="email"
            class="block text-md font-bold mb-3 leading-6 text-gray-900"
            >Verification Code</label
          >
          <div v-if="errorMsg">
            <Alert id="alert">
              <ul class="mt-1.5 list-disc list-inside">
                <li>
                  {{ errorMsg }}
                </li>
              </ul>
            </Alert>
          </div>
          <div class="flex flex-row gap-4 justify-center items-center">
            <div class="mt-2">
              <input
                ref="inp1Ref"
                autofocus
                @input="moveToNext($event, $refs.inp2Ref)"
                v-model="inp1"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
            <div class="mt-2">
              <input
                ref="inp2Ref"
                maxlength="1"
                @input="moveToNext($event, $refs.inp3Ref)"
                v-model="inp2"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
            <div class="mt-2">
              <input
                ref="inp3Ref"
                maxlength="1"
                @input="moveToNext($event, $refs.inp4Ref)"
                v-model="inp3"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
            <div class="mt-2">
              <input
                ref="inp4Ref"
                maxlength="1"
                @input="moveToNext($event, $refs.inp5Ref)"
                v-model="inp4"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
            <div class="mt-2">
              <input
                ref="inp5Ref"
                maxlength="1"
                @input="moveToNext($event, $refs.inp6Ref)"
                v-model="inp5"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
            <div class="mt-2">
              <input
                ref="inp6Ref"
                maxlength="1"
                @input="moveToNext($event, null)"
                v-model="inp6"
                type="text"
                required=""
                class="block w-[40px] text-center font-bold rounded-lg border-sm py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
              />
            </div>
          </div>
          <div class="text-center mt-6">
            <span>{{ timer }}</span>
            <a
              @click="resendCode()"
              class="text-blue-500 ml-3 hover:underline cursor-pointer"
              >Resend code</a
            >
          </div>
        </div>

        <div class="mb-8">
          <Button
            :loading="loading"
            @trigger-event="checkVerificationCode()"
            :string="'Check Code'"
          />
        </div>
      </form>
      <div class="sm:mx-auto sm:w-full sm:max-w-sm sm:max-h-sm mb-6">
        <img
          class="mx-auto h-34 w-34 -mb-10"
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
import { ref, watch } from "vue";
import Alert from "../../components/Alert.vue";
import Button from "../../components/Button.vue";
import { useRouter, useRoute } from "vue-router";
import CryptoJS from "crypto-js";
const inp1 = ref("");
const inp2 = ref("");
const router = useRouter();
const inp3 = ref("");
const inp4 = ref("");
const inp5 = ref("");
const inp6 = ref("");
const loading = ref(false);
const routes = useRoute();
const token = ref(atob(routes.query.t));
const errorMsg = ref("");
let leftTime = 3 * 60; // Initial time in seconds (3 minutes)
const timer = ref("");
const decryptedToken = ref(
  CryptoJS.AES.decrypt(token.value, "code").toString(CryptoJS.enc.Utf8)
);

// Watch for changes in the token value
watch(token, (newToken) => {
  decryptedToken.value = newToken;
});

const timerInterval = setInterval(() => {
  // Decrement time by 1 second
  leftTime--;

  // Check if time has reached 0
  if (leftTime < 0) {
    clearInterval(timerInterval); // Stop the timer
  } else {
    // Calculate remaining minutes and seconds
    const minutes = Math.floor(leftTime / 60);
    const seconds = leftTime % 60;

    timer.value = `${minutes}:${seconds < 10 ? "0" + seconds : seconds}`;
  }
}, 1000);
function moveToNext(event, nextInputRef) {
  const input = event.target;
  const currentLength = input.value.length;
  colorInputs("black");
  errorMsg.value = "";

  if (currentLength === 1 && nextInputRef) {
    nextInputRef.focus();
  }
}
function resendCode() {
  store
    .dispatch("resendEmailVerification", { code: decryptedToken.value })
    .then((res) => {
      console.log(res);
      if (res.status === 200) {
        token.value = res.data.token;
        console.log("token res", res.data.token);

        leftTime = 3 * 60;
        timer.value = "3:00"; // Update timer.value, as it is a ref
        store.commit("setNotification", {
          message: "The code was sent, check your inbox!",
          type: "success",
        });
      } else {
        store.commit("setNotification", {
          message: "Something went wrong!",
          type: "danger",
        });
      }
    });
}

function colorInputs(color) {
  Array.from(document.querySelectorAll("input")).map(
    (i) => (i.style.borderColor = color)
  );
}
const checkVerificationCode = () => {
  loading.value = true;
  // Combine input values to form the verification code
  const verificationCode = `${inp1.value}${inp2.value}${inp3.value}${inp4.value}${inp5.value}${inp6.value}`;
  if (!verificationCode) {
    colorInputs("red");
    errorMsg.value = "the verification code required";
    return;
  }

  // Send the verification code to the server
  store
    .dispatch("checkVerificationCode", {
      code: verificationCode,
    })
    .then((res) => {
      if (res.status === 200 && res.data.status === "success") {
        const encryptedToken = CryptoJS.AES.encrypt(
          decryptedToken.value,
          "code"
        ).toString();

        setTimeout(() => {
          loading.value = false;

          router.push({
            name: "pass_reset",
            query: { t: btoa(encryptedToken) },
          });
        }, 2000);
      } else {
        colorInputs("red");
        loading.value = false;

        errorMsg.value = res.response.data.message;
      }
    });
};
</script>
