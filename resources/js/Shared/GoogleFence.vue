<template>
  <div>
    <gmap-map ref="map" :center="center" :zoom="12" map-type-id="terrain" style="width:100%;  height: 400px;">
      <gmap-polygon ref="polygon" :paths="markers" :editable="true"
                    @paths_changed="updateEdited($event)"
                    @rightclick="handleClickForDelete"
      />
    </gmap-map>
    <div>
      <v-btn class="success" @click="addPath()">Add Path</v-btn>
      <v-btn class="error" @click="removePath()">Remove Path</v-btn>
    </div>
    <div>
      <TextAreaInput :value="polygonGeojson" :errors="errorMessage" label="Fence Cordinates" :readonly="true" :hidden="true" @input="readGeojson" />
      <!--      <textarea :value="polygonGeojson" style="width: 100%; height: 200px"-->
      <!--                @input="readGeojson"-->
      <!--      />-->
      <!--      <div v-if="errorMessage">{{ errorMessage }}</div>-->
    </div>
  </div>
</template>

<script>
// import {gmapApi} from 'vue2-google-maps'
import throttle from 'lodash/throttle'
import TextAreaInput from '../Common/Form/TextAreaInput'

function closeLoop (path) {
  return path.concat(path.slice(0, 1))
}
export default {
  name: 'GoogleFence',
  components: {TextAreaInput},
  props: {
    location: {
      Type: Object,
      default: null,
    },
  },
  data() {
    return {
      edited: null,
      mvcPaths: null,
      errorMessage: null,
      polygonGeojson: '',
      // default to montreal to keep it simple
      // change this to whatever makes sense -33.91408223110558,-33.871028, 151.020579
      center: { lat: -33.871028, lng: 151.020579 },
      markers: [],
      infoWindow: {
        position: {lat: 0, lng: 0},
        open: false,
        template: '',
      },
      places: [],
    }
  },
  computed: {
    polygonPaths: function () {
      if (!this.markers) return null

      let paths = []
      for (let i=0; i < this.markers.getLength(); i++) {
        let path = []
        for (let j=0; j<this.markers.getAt(i).getLength(); j++) {
          let point = this.markers.getAt(i).getAt(j)
          path.push({lat: point.lat(), lng: point.lng()})
        }
        paths.push(path)
      }
      return paths
    },
    iValue: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  watch: {
    markers: throttle(function (markers) {
      if (markers) {
        this.markers = markers
        this.iValue = this.markers
        this.polygonGeojson = JSON.stringify({
          type: 'Polygon',
          coordinates: this.markers.map(path => closeLoop(path.map(({lat, lng}) => [lng, lat]))),
        }, null, 2)
      }
    }, 1000),
  },

  mounted() {
    this.geolocate()
  },

  methods: {
    geolocate: function() {
      let path = []
      for (let i = 0; i < this.location.length; i++) {
        if(i == 0){
          this.center = {lat: parseFloat(this.location[i].latitude), lng: parseFloat(this.location[i].longitude)}
          // continue
        }
        path.push({lat: parseFloat(this.location[i].latitude), lng: parseFloat(this.location[i].longitude)})
      }
      this.markers = []
      this.markers.push(path)
    },
    updateCenter: function (place) {
      this.center = {
        lat: place.geometry.location.lat(),
        lng: place.geometry.location.lng(),
      }
    },
    updateEdited: function (mvcPaths) {
      this.mvcPaths = mvcPaths
    },
    addPath: function () {
      // obtain the bounds, so we can guess how big the polygon should be
      var bounds = this.$refs.map.$mapObject.getBounds()
      var northEast = bounds.getNorthEast()
      var southWest = bounds.getSouthWest()
      var center = bounds.getCenter()
      var degree = this.markers.length + 1
      var f = Math.pow(0.20, degree)

      // Draw a triangle. Use f to control the size of the triangle.
      // i.e., every time we add a path, we reduce the size of the triangle
      var path = [
        { lng: center.lng(), lat: (1-f) * center.lat() + (f) * northEast.lat() },
        { lng: (1-f) * center.lng() + (f) * southWest.lng(), lat: (1-f) * center.lat() + (f) * southWest.lat() },
        { lng: (1-f) * center.lng() + (f) * northEast.lng(), lat: (1-f) * center.lat() + (f) * southWest.lat() },
      ]
      this.markers = []
      this.markers.push(path)
    },
    removePath: function () {
      this.markers.splice(this.markers.length - 1, 1)
    },
    handleClickForDelete($event) {
      if ($event.vertex) {
        this.$refs.polygon.$polygonObject.getPaths()
          .getAt($event.path)
          .removeAt($event.vertex)
      }
    },
    readGeojson: function ($event) {
      try {
        this.polygonGeojson = $event.target.value

        var v = JSON.parse($event.target.value)

        this.paths = v.coordinates.map(linearRing =>
          linearRing.slice(0, linearRing.length - 1)
            .map(([lng, lat]) => ({lat, lng}))
        )

        this.errorMessage = null
      } catch (err) {
        this.errorMessage = err.message
      }
    },
  },
}
</script>
