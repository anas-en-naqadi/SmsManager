<template>


<nav class="border-gray-200 bg-gray-50 white:bg-gray-800 white:border-gray-700 ">
  <div class="max-w-screen-2xl flex flex-wrap items-center justify-between gap-24 mx-auto p-2 ">
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="../../../public/images/logo.png" class="h-14 w-18" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap white:text-white">SMS Manager</span>
    </a>
    <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 white:text-gray-400 white:hover:bg-gray-700 white:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
      <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent white:bg-gray-800 md:white:bg-transparent white:border-gray-700">
        <li>
          <a active-class="text-blue-700" href="#home" class="block py-2 px-3 md:p-0 text-lg  text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:white:text-blue-500 white:bg-blue-600 md:white:bg-transparent" aria-current="page">Home</a>
        </li>
        <li>
          <a active-class="text-blue-700" href="#services" class="block py-2 text-lg px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 white:text-white md:white:hover:text-blue-500 white:hover:bg-gray-700 white:hover:text-white md:white:hover:bg-transparent">Services</a>
        </li>
        <li>
          <router-link  to="/login" class="block text-lg  py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 white:text-white md:white:hover:text-blue-500 white:hover:bg-gray-700 white:hover:text-white md:white:hover:bg-transparent">Log in</router-link>
        </li>
        <li>
          <router-link to="/register" class="block text-lg  py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 white:text-white md:white:hover:text-blue-500 white:hover:bg-gray-700 white:hover:text-white md:white:hover:bg-transparent">Sign up</router-link>
        </li>
      </ul>
    </div>
  </div>
</nav>

</template>
