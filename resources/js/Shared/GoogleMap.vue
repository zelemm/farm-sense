<template>
  <div>
    <gmap-map
      :center="center"
      :zoom="12"
      style="width:100%;  height: 400px;"
    >
      <gmap-marker
        v-for="(m, index) in markers"
        ref="farmLocation"
        :key="index"
        :position="m.position"
        :clickable="true"
        :draggable="false"
        @click="center=m.position; openInfoWindowTemplate(index)"
      />
      <gmap-info-window
        :options="{
          maxWidth: 300,
          pixelOffset: { width: 0, height: -35 }
        }"
        :position="infoWindow.position"
        :opened="infoWindow.open"
        @closeclick="infoWindow.open=false"
      >
        <div v-html="infoWindow.template" />
      </gmap-info-window>
    </gmap-map>
  </div>
</template>

<script>
export default {
  name: 'GoogleMap',
  props: {
    location: {
      Type: Object,
      default: null,
    },
  },
  data() {
    return {
      // default to montreal to keep it simple
      // change this to whatever makes sense
      center: { lat: 45.508, lng: -73.587 },
      markers: [],
      infoWindow: {
        position: {lat: 0, lng: 0},
        open: false,
        template: '',
      },
      places: [],
    }
  },

  mounted() {
    this.geolocate()
  },

  methods: {
    geolocate: function() {
      this.center = {
        lat: parseFloat(this.location.lat),
        lng: parseFloat(this.location.lng),
      }
      const place = {
        lat: parseFloat(this.location.lat),
        lng: parseFloat(this.location.lng),
        farm_name: this.location.farm_name,
        farm_address: this.location.farm_address,
      }
      this.places.push(place)
      const marker = {
        lat: parseFloat(this.location.lat),
        lng: parseFloat(this.location.lng),
      }
      this.markers.push({ position: marker })
      // navigator.geolocation.getCurrentPosition(position => {
      //     this.center = {
      //         lat: position.coords.latitude,
      //         lng: position.coords.longitude
      //     };
      // });
    },
    openInfoWindowTemplate(index) {
      const place = this.places[index]
      // const { lat, lng, farm_name, farm_address } = this.markers[index]
      this.infoWindow.position = { lat: parseFloat(place.lat), lng: parseFloat(place.lng) }
      this.infoWindow.template = `<div><h4 class="text-sm font-semibold">${place.farm_name}</h4><p class="text-xs mb-0 mt-2">${place.farm_address}</p></div>`
      this.infoWindow.open = true
    },
    getIcon(url) {
      return {
        url,
        scaledSize: { width: 28, height: 50, f: 'px', b: 'px' },
      }
    },

    async showPopup() {
      let content, marker

      if (!this.$refs.farmLocation) return

      content = `<div><h4 class="text-sm font-semibold">${this.googleMap}</h4><p class="text-xs mb-0 mt-2">${this.googleMapAddress}</p></div>`
      this.infoWindow = new this.google.maps.InfoWindow({ content })
      marker = await this.$refs.destinationMarker.$markerPromise

      const map = await this.$refs.googleMap.$mapPromise
      this.infoWindow.open(map, marker)
    },

    hidePopup() {
      this.infoWindow.close()
    },
  },
}
</script>
