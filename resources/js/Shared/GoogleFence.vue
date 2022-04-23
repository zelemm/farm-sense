<template>
  <div>
    <gmap-map ref="map" :center="center" :zoom="12" map-type-id="terrain">
      <gmap-polygon v-if="markers.length > 0" ref="polygon" :paths="markers" :editable="true"
                    @paths_changed="updateEdited($event)"
                    @rightclick="handleClickForDelete"
      />
    </gmap-map>
    <div>
      <button @click="addPath()">Add Path</button>
      <button @click="removePath()">Remove Path</button>
    </div>
    <div>
      <textarea :value="polygonGeojson" style="width: 100%; height: 200px"
                @input="readGeojson"
      />
      <div v-if="errorMessage">{{ errorMessage }}</div>
    </div>
  </div>
</template>

<script>
// import {gmapApi} from 'vue2-google-maps'

function closeLoop (path) {
  return path.concat(path.slice(0, 1))
}
export default {
  name: 'GoogleFence',
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
  computed: {
    polygonPaths: function () {
      if (!this.mvcPaths) return null

      let paths = []
      for (let i=0; i < this.mvcPaths.getLength(); i++) {
        let path = []
        for (let j=0; j<this.mvcPaths.getAt(i).getLength(); j++) {
          let point = this.mvcPaths.getAt(i).getAt(j)
          path.push({lat: point.lat(), lng: point.lng()})
        }
        paths.push(path)
      }
      return paths
    },
  },
  watch: {
    // polygonPaths: _.throttle(function (markers) {
    //   if (markers) {
    //     this.markers = markers
    //     this.polygonGeojson = JSON.stringify({
    //       type: 'Polygon',
    //       coordinates: this.markers.map(path => closeLoop(path.map(({lat, lng}) => [lng, lat]))),
    //     }, null, 2)
    //   }
    // }, 1000),
  },

  mounted() {
    this.geolocate()
  },

  methods: {
    geolocate: function() {
      for (let i = 0; i < this.location.length; i++) {
        if(i == 0){
          this.center = {lat: parseFloat(this.location[i].latitude), lng: parseFloat(this.location[i].longitude)}
          continue
        }
        this.markers.push({lat: parseFloat(this.location[i].latitude), lng: parseFloat(this.location[i].longitude)})
      }

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
      var f = Math.pow(0.66, degree)

      // Draw a triangle. Use f to control the size of the triangle.
      // i.e., every time we add a path, we reduce the size of the triangle
      var path = [
        { lng: center.lng(), lat: (1-f) * center.lat() + (f) * northEast.lat() },
        { lng: (1-f) * center.lng() + (f) * southWest.lng(), lat: (1-f) * center.lat() + (f) * southWest.lat() },
        { lng: (1-f) * center.lng() + (f) * northEast.lng(), lat: (1-f) * center.lat() + (f) * southWest.lat() },
      ]

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
