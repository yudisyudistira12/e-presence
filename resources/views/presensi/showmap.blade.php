<style>
    #map { height: 260px; }
</style>
<div id="map"></div>
<script>
    var presensiLocation = "{{ $presensi->location_in }}";
    var lok = presensiLocation.split(",");
    var latitude = lok[0];
    var longitude = lok[1];
    var map = L.map('map').setView([latitude, longitude], 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([-6.858770212326696, 107.63178442118637], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 100
    }).addTo(map);
    var popup = L.popup()
    .setLatLng([latitude, longitude])
    .setContent("{{ $presensi->name }}")
    .openOn(map);
</script>

