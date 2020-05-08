<template>
    <div class="text-white mb-8">
      <div class="places-input text-gray-800">
        <input type="search" class="form-control w-full" id="address-input" placeholder="Location you are looking for">

        <p>Selected: <strong id="address-value">none</strong></p>
      </div>
      <div class="weather-container font-sans w-128 max-w-lg rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-4">
        <div class="current-weather flex items-center justify-between px-6 py-8">
          <div class="flex items-center">
            <div>
              <div class="text-6xl font-semibold"> {{ currentTemperature.actual }}ºC</div>
              <div>Feel like {{ currentTemperature.feels }}ºC</div>
            </div>
          </div>
          <div class="mx-5">
            <div class="font-semibold">{{ currentTemperature.summary }}</div>
            <div>{{ location.name }}</div>
          </div>
          <div>
            <img v-if="currentTemperature.icon" :src="`https://openweathermap.org/img/wn/${currentTemperature.icon}@2x.png`" alt="">
          </div>
        </div> <!-- end current-weather -->

        <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
          <div
            v-for="(day, index) in daily"
            :key="day.dt"
            class="flex items-center"
            :class="{ 'mt-8' : index > 0 }"
            v-if="index < 6"
          >
            <div class="flex items-center">
              <div class="w-1/6 text-lg text-gray-200">{{ toDayOfWeek(day.dt_txt) }}</div>
              <div class="w-4/6 mx-4 flex items-center">
                <img v-if="day.weather[0].icon" :src="`https://openweathermap.org/img/wn/${day.weather[0].icon}@2x.png`" alt="">
                <div class="ml-3" v-text="day.weather[0].description"></div>
              </div>
              <div class="w-1/6 text-right">
                <div v-text="day.main.temp_max"></div>
                <div v-text="day.main.temp_min"></div>
              </div>
            </div>
          </div>
        </div> <!-- end future-weather -->

      </div> <!-- end weather-container -->
    </div>
</template>

<script>
var moment = require('moment');

export default {
  data() {
    return {
      currentTemperature: {
        actual: '',
        feels: '',
        summary: '',
        icon: '',
      },
      daily: [],
      location: {
        name: "Jaraguá do Sul, Brasil",
        lat: -26.4898,
        lon: -49.0779,
      },
    }
  },
  watch: {
    location: {
      handler(newValue,oldValue) {
        this.fetchCurrentTemperature()
        this.fetchForecastTemperature()
      },
      deep: true
    }
  },
  mounted() {
    moment.locale('pt-br');
    this.fetchCurrentTemperature()
    this.fetchForecastTemperature()



    var placesAutocomplete = places({
      appId: 'plIGVQ2NDWGV',
      apiKey: '206048d3f0d0e8f31c0ee3c1ff9a5b75',
      container: document.querySelector('#address-input')
    }).configure({
      type: 'city',
      aroundLatLingViaIP: false
    });

    var $address = document.querySelector('#address-value')

    placesAutocomplete.on('change', (e) => {
      $address.textContent = e.suggestion.value
      console.log(e.suggestion);
      this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
      this.location.lat = e.suggestion.latlng.lat
      this.location.lon = e.suggestion.latlng.lng
    })

    placesAutocomplete.on('clear',  () => {
      $address.textContent = 'none';
    })
  },
  methods: {
    fetchCurrentTemperature(){
      fetch(`/api/weather?lat=${this.location.lat}&lon=${this.location.lon}`)
        .then(response => response.json())
        .then(data => {
          console.log(data)
          this.currentTemperature.actual = data.main.temp;
          this.currentTemperature.feels = data.main.feels_like;
          this.currentTemperature.summary = data.weather[0].main;
          this.currentTemperature.icon = data.weather[0].icon;
        })
    },
    fetchForecastTemperature(){
      fetch(`/api/forecast?lat=${this.location.lat}&lon=${this.location.lon}`)
        .then(response => response.json())
        .then(data => {
          this.daily = data.list;
        })
    },
    toDayOfWeek(timestamp){
      console.log(timestamp)
      console.log(moment(timestamp).format('LLLL'))
      return moment(timestamp).format('ddd')
    }
  }
}
</script>
