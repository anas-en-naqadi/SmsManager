<template>
  <BaseCompo>
    <template v-slot:header>
      <div
        class="flex items-center max-w-full flex-row 2xl:gap-[40rem] md:gap-40 sm:gap-6 justify-between"
      >
        <div class="w-full">Calendar</div>
        <div class="flex flex-row w-full gap-6">
          <div class="flex items-center gap-2">
            <div class="rounded-full h-4 w-4 border bg-emerald-600"></div>
            <span class="2xl:text-lg sm:text-sm md:text-md font-tahoma"
              >sent</span
            >
          </div>
          <div class="flex items-center gap-2">
            <div class="rounded-full h-4 w-4 border bg-orange-600"></div>
            <span class="sm:text-sm 2xl:text-lg md:text-md font-tahoma"
              >pending</span
            >
          </div>
          <div class="flex items-center gap-2 w-24">
            <div class="rounded-full h-4 w-4 border bg-red-600"></div>
            <span class="2xl:text-lg sm:text-sm md:text-md font-tahoma"
              >not sent</span
            >
          </div>
        </div>
        <div class="w-full">
          <button
            @click="addEvent()"
            type="button"
            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-600 focus:ring-1 focus:outline-none focus:ring-blue-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 white:border-blue-500 white:text-blue-500 white:hover:text-white white:hover:bg-blue-600 dark:focus:ring-blue-800"
          >
            + Add Event
          </button>
        </div>
      </div>
    </template>
    <Spinner v-if="loading" />
    <div v-else>
      <ScheduleMssg
        :isModalOpen="show"
        :start="start"
        @close-event="show = !show"
      />
      <div class="relative bottom-24">
        <FullCalendar :options="calendarOptions" />
      </div>
    </div>
  </BaseCompo>
</template>

<script setup>
import BaseCompo from "../../components/BaseCompo.vue";
import ScheduleMssg from "../../components/ScheduleMssg.vue";
import Spinner from "../../components/Spinner.vue";
import { ref, onMounted, watch, computed } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import TimeGridPlugin from "@fullcalendar/timegrid";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import store from "../../store";
const show = ref(false);
const events = ref([]);

onMounted(() => {
  store.dispatch("getAllEvents");
});

events.value = store.state.events.data;

const loading = ref(true);
let start = "";
let calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin, listPlugin, TimeGridPlugin],
  initialView: "dayGridMonth",
  headerToolbar: {
    right: "prev,today,next",
    left: "title",

    center: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
  },
  selectable: true,
  selectMirror: true,
  weekends: true,
  editable: true,
  displayEventTime: true,
  durationEditable: false,
 selectAllow: function (selectInfo) {
  // Allow selection only for dates after today
  if (selectInfo.start <= new Date()) {
    store.commit("setNotification", {
      message: "You can't add an event on a past day!!!",
      type: "danger",
    });
    return false; // Disallow selection
  }
  return true; // Allow selection
},
  select: function (info) {
    show.value = true;
    var time = new Date();
    var hours = time.getHours();
    var minutes = time.getMinutes();
    var seconds = time.getSeconds();
    info.end = "";
    info.endStr = "";

    // Format the time as a string
    var currentTime =
      (hours < 10 ? `0${hours}` : hours) +
      ":" +
      (minutes < 10 ? `0${minutes}` : minutes) +
      ":" +
      seconds;
    start = info.startStr + "T" + currentTime;
  },
  eventClassNames: function ({ event }) {
    return event.extendedProps.status === "sent"
      ? "bg-emerald-600 font-bold text-black text-md h-10 border border-emerald-600 hover:bg-emerald-400"
      : event.extendedProps.status === "pending"
      ? "bg-orange-500 font-bold text-black text-md h-10 border border-yellow-600 hover:bg-orange-400"
      : " bg-red-600 text-black h-10 border border-red-600 font-bold text-md hover:bg-red-400";
  },
  // Event Display
  eventDisplay: "auto", // Determines how events should be displayed: 'auto', 'block', 'list'
  eventTimeFormat: { hour: "numeric", minute: "2-digit", meridiem: "short" }, // Time format for displaying events
  events: events.value,
  eventClick: function (info) {
    const type = Array.isArray(info.event.extendedProps.to) ? "many" : "one";
    store
      .dispatch("getEventById", { type: type, id: info.event.id })
      .then(() => (show.value = true));
  },
  eventDrop: function (info) {
    /* callback when an event is dropped */
    const type = Array.isArray(info.event.extendedProps.to) ? "many" : "one";
    const newEvent = { start: info.event.start, id: info.event.id, type: type };
loading.value = true;
    events.value.forEach((event) => {
      if (event.id === info.event.id) {
        event.start = info.event.start;
      }
    });

    store.dispatch("updateSmsEvents", newEvent).then((res) => {
      if (res.status === 200) store.dispatch("getAllEvents");

    });
  },
  //   // Set the valid range to restrict interactions before the current date
  //   validRange: {
  //     start: new Date().toISOString().substr(0, 10), // Use ISO string format for the start date
  //   },
});
watch(
  () => store.state.events.data,
  (newVal) => {
    events.value = newVal ? Object.values(newVal) : null;
    calendarOptions.value.events = events.value;
    loading.value = false;
  }
);

function addEvent() {
  show.value = true;
}
</script>


