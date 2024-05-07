<template>
  <BaseCompo>
    <template v-slot:header> Welcome To SMS Sender </template>
    <div class="container w-full flex items-center  flex-row gap-24 justify-between animate-fade-in-down" id="container">
      <div class="2xl:ml-36 2xl:-mt-36 animated-fade-in-down md:ml-16" >
        <p class="text-xl mb-8">
        <p class="text-5xl font-bold mb-5">
          Welcome to our platform!</p > We've created a user-friendly space where you
          can easily manage your SMS messages. Whether you're a business owner
          or a personal user. Explore our site to discover its features, and don't
          hesitate to reach out if you need assistance.
        </p>
        <span class="text-lg font-bold mb-6">Pls Choose Your :</span>
        <div class="flex flex-row mt-6">
          <router-link
            :to="{name:'sendSms'}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Send To one Contact
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-6 ml-2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
              />
            </svg>
          </router-link>
          <router-link
            :to="{name:'sendTogroup'}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Send To Many contacts
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-6 ml-2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
              />
            </svg>
          </router-link>
        </div>
      </div>
      <div class="w-full ml-[300px] hidden 2xl:block lg:block ">
        <img
          src="../../../public/images/Sent_Message.gif"
          alt=""
          class="w-[100rem] h-[40rem] -mt-20 2xl:ml-24 lg:-ml-12 lg:-mt-12 animate-fade-in-down"
        />
      </div>
    </div>
  </BaseCompo>
</template>


<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
</script>

