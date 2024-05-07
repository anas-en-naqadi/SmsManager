<template>
  <div class="overflow">
    <nav>
      <NavBar />
    </nav>
    <div
      class="w-full h-[50rem] bg-blue-800 border center flex flex-row justify-around items-center"
      id="home"
    >
      <div class="w-[30rem] animate-fade-in-down">
        <h1 class="text-gray-50 mb-5 text-6xl font-bold">
          Get Your Message Across
        </h1>
        <p class="text-gray-300 text-2xl mb-5">
          A leading provider of business messaging solutions
        </p>
        <router-link
          :to="{ name: 'register' }"
          class="text-blue-800 mt-3 bg-gray-50 hover:bg-blue-50 ml-44 p-4 text-md focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-center inline-flex items-center white:bg-blue-600 white:hover:bg-blue-100 white:focus:ring-blue-800"
        >
          Register your account today
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
      <img
        src="../../../public/images/Chat-rafiki.png"
        alt=""
        class="w-[35rem] h-[37rem] animate-fade-in-down"
      />
    </div>

    <div class="mt-44 animate-fade-in-down">
      <div
        class="w-full bg-white border hover:translate-y-4 hover:translate-x-4 transition-transform duration-150 cursor-pointer border-gray-200 rounded-lg shadow white:bg-gray-800 white:border-gray-700"
      >
        <ul
          class="hidden text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg sm:flex white:divide-gray-600 white:text-gray-400 rtl:divide-x-reverse"
          id="fullWidthTab"
          data-tabs-toggle="#fullWidthTabContent"
          role="tablist"
        >
          <li class="w-full p-4">
            <span class="font-bold text-4xl text-black">Statistics</span>
          </li>
        </ul>
        <div
          id="fullWidthTabContent"
          class="border-t border-gray-200 white:border-gray-600"
        >
          <div
            class="p-4 bg-white rounded-lg md:p-8 white:bg-gray-800"
            id="stats"
            role="tabpanel"
            aria-labelledby="stats-tab"
          >
            <dl
              class="grid max-w-screen-xl grid-cols-2 2xl:grid-cols-3 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 white:text-white sm:p-8"
            >
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">73M+</dt>
                <dd class="text-gray-500 white:text-gray-400">Developers</dd>
              </div>
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">100M+</dt>
                <dd class="text-gray-500 white:text-gray-400">
                  Public repositories
                </dd>
              </div>
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">1000s</dt>
                <dd class="text-gray-500 white:text-gray-400">
                  Open source projects
                </dd>
              </div>
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">1B+</dt>
                <dd class="text-gray-500 white:text-gray-400">Contributors</dd>
              </div>
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">90+</dt>
                <dd class="text-gray-500 white:text-gray-400">
                  Top Forbes companies
                </dd>
              </div>
              <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">4M+</dt>
                <dd class="text-gray-500 white:text-gray-400">Organizations</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-12 items-center mt-64 animate-fade-in-down" id="services" >
      <div>
        <span class="font-bold text-4xl">Services</span>
      </div>
      <div class="flex flex-row flex-wrap gap-20">
        <div
          class="max-w-sm bg-white border border-gray-200 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 rounded-lg shadow white:bg-gray-800 white:border-gray-700"
        >
          <a href="#">
            <img
              class="rounded-t-lg"
              src="../../../public/images/messages-concept-illustration_114360-524.jpg"
              alt=""
            />
          </a>
          <div class="p-5">
            <a href="#" class="">
              <h5
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 white:text-white"
              >
                Controll Sending SMS
              </h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 white:text-gray-400">
              It becoms easier To track the Sending SMS and Full controll to
              your Contacts
            </p>
            <a
              href="#"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
            >
              Read more
              <svg
                class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
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
            </a>
          </div>
        </div>

        <div
          class="max-w-sm bg-white transition ease-in-out delay-150 border border-gray-200 rounded-lg shadow hover:-translate-y-1 hover:scale-110 white:bg-gray-800 white:border-gray-700"
        >
          <a href="#">
            <img
              class="rounded-t-lg"
              src="../../../public/images/Accept_request.gif"
              alt=""
            />
          </a>
          <div class="p-5">
            <a href="#">
              <h5
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 white:text-white"
              >
                Send SMS To One Contact
              </h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 white:text-gray-400">
              you can send what ever you want by just one click. check Now !!
            </p>
            <a
              href="#"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
            >
              Read more
              <svg
                class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
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
            </a>
          </div>
        </div>

        <div
          class="max-w-sm bg-white border border-gray-200 transition ease-in-out delay-150 rounded-lg shadow hover:-translate-y-1 hover:scale-110 white:bg-gray-800 white:border-gray-700"
        >
          <a href="#">
            <img
              class="rounded-t-lg"
              src="../../../public/images/16717261_2002.i039.018_remote_management_distant_work_isometric_icons-13.jpg"
              alt=""
            />
          </a>
          <div class="p-5">
            <a href="#">
              <h5
                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 white:text-white"
              >
                Send SMS To Group of Contacts
              </h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 white:text-gray-400">
              You can Add new Contacts and Select All contacts Then send SMS
              message. Check Now !!
            </p>
            <a
              href="#"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 white:bg-blue-600 white:hover:bg-blue-700 white:focus:ring-blue-800"
            >
              Read more
              <svg
                class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
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
            </a>
          </div>
        </div>
      </div>
    </div>

    <Footer class="footer relative top-[11rem]">
      <Footer />
    </Footer>
  </div>
</template>


<script setup>
import Footer from "../../components/Footer.vue";
import NavBar from "../../components/NavBar.vue";
</script>
