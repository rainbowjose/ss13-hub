var stations = {}
var tileURL = "https://renderbus.s3.amazonaws.com/tiles/{id}/{z}/tile_{x}-{y}.png";
fetch('https://renderbus.s3.amazonaws.com/maps.json')
  .then(function(response) {
    return response.json();
  })
  .then(function(mapjson) {
    for(var m in mapjson){
      var id = mapjson[m].map_name.replace(" ",'');
      var station = L.tileLayer(tileURL, {id: id,  maxNativeZoom: 5, maxZoom: 6});
      stations[id] = station
      if (typeof basemap == 'undefined') {
        map.addLayer(station)
        var basemap = true
      }
    }
    L.control.layers(stations).addTo(map);
  })


var map = L.map("map", {
  zoomControl: false,
  attributionControl: false,
  minZoom: 2,
  maxZoom: 6,
  maxBounds: [[0,0],[-256,256]],
  crs: L.CRS.Simple,
}).setView([-128,128], 2);
L.control.zoom({position: "topleft"}).addTo(map);

map.on('click',function(e){
  lat = Math.floor(e.latlng.lat)
  lng = Math.floor(e.latlng.lng)
  var polygon = L.polygon([
      [lat, lng],
      [lat+1, lng],
      [lat+1, lng+1],
      [lat, lng+1],
      [lat, lng]
    ]).addTo(map);
});

// var hash = new L.Hash(map, stations);
// var hash = window.location.hash
// if(hash.indexOf('#') === 0) {
//   hash = hash.substr(1);
// }
// var args = hash.split("/");
// console.log(args[1])
// console.log(args[2])
// hilite(args[1], -args[2])

// function hilite(x, y, txt) {
//   lat = y-255
//   lng = x*1
//   var polygon = L.polygon([
//       [lat, lng],
//       [lat-1, lng],
//       [lat-1, lng-1],
//       [lat, lng-1],
//       [lat, lng]
//     ]).addTo(map);
// }

