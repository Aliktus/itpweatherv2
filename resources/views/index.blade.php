@extends('layouts.base')

@section('stylesheets')

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

@endsection

@section('javascripts')

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://unpkg.com/vue@next"></script>

  <script>

    const WeatherWidget = {

      data() {
        return {
          showCities: false,
          weather: {},
          cities: [],
          currentCity: "_default",
          refreshAnimation: false
        }
      },

      delimiters: ['${', '}'],

      mounted() {
        this.currentCity = window.localStorage.getItem('city');
        if (!this.currentCity) {
          this.currentCity = "_default";
        }
        this.getWeather();
        this.getCities();
      },

      methods: {
        selectCity(city) {
          this.currentCity = city;
          this.showCities = false;
          window.localStorage.setItem('city', city);
          this.getWeather();
        },
        getWeather() {
          axios
            .get('http://itpweatherv2.test/api/weather/' + this.currentCity)
            .then(response => {
              this.weather = response.data;
              this.refreshAnimation = false;
            });
        },
        getCities() {
          axios
            .get('http://itpweatherv2.test/api/cities')
            .then(response => {
              this.cities = response.data;
              if (!this.cities.includes(this.currentCity)) {
                if(this.cities.length > 0) {
                  this.selectCity(this.cities[0]);
                }
              }
          });
        },
        refresh() {
          this.refreshAnimation = true;
          this.getWeather();
        },
        toggleCities() {
          this.showCities = !this.showCities;
          if(this.showCities) {
            this.getCities();
          }
        }
      }

    };

    Vue.createApp(WeatherWidget).mount('#widget');

  </script>

@endsection

@section('body')

  <div id="widget">
    <div class="border">
      <div class="header" @mousedown="toggleCities()">
        <h1 class="city" v-if="weather">${weather.city}</h1>
      </div>
      <div class="body body-cities" :class="{hidden: !showCities}">
        <ul class="cities-list">
          <li class="city-element" v-for="city in cities">
            <span class="city-name" @click="selectCity(city)">${city}</span>
          </li>
        </ul>
      </div>
      <div class="body body-main" :class="{hidden: showCities, refreshAnimation}">
        <div class="first">
          <h2 class="description">${weather.description}</h2>
        </div>
        <div class="second" v-if="weather.cond">
          <p class="temp">
            <span class="value" :class="{minus: weather.cond.temp.value < 0}">${Math.round(weather.cond.temp.value)}</span>
            <span class="unit" v-html="weather.cond.temp.unit"></span>
          </p>
        </div>
        <div class="third" v-if="weather">
          <img :src="weather.icon" />
        </div>
        <div class="fourth" v-if="weather.cond">
          <div class="block pressure">
            <h3 class="name">${weather.cond.pressure.name}</h2>
            <p class="variable"><span class="value">${weather.cond.pressure.value}</span><span class="unit" v-html="weather.cond.pressure.unit"></span></p>
          </div>
          <div class="block humidity">
            <h3 class="name">${weather.cond.humidity.name}</h2>
            <p class="variable"><span class="value">${weather.cond.humidity.value}</span><span class="unit" v-html="weather.cond.humidity.unit"></span></p>
          </div>
        </div>
        <div class="fifth" v-if="weather.atmo">
          <h2 class="atmo-title">Warunki atmosferyczne</h2>
          <div class="atmo-content">
            <ul class="atmo-list" :class="{columns: weather.atmo.length > 2}">
              <li class="atmo-element" v-for="atmo in weather.atmo">
                <span class="name">${atmo.name}</span>
                <span class="value">${atmo.value}</span>
                <span class="unit">${atmo.unit}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <a href="javascript:void(0)" @click="refresh" class="left">odśwież</a>
      <a href="http://itpweatherv2.test/login" class="right">zaloguj</a>
    </div>
  </div>

@endsection
