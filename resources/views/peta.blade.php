<!DOCTYPE html>
<html>
<head>
    <title>Peta Responden</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="height: 600px;"></div>

    <script>
      
        var map = L.map('map').setView([-7.42, 109.29], 10);

       
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        /
        fetch('/geo/banyumas.geojson')
            .then(res => res.json())
            .then(data => {
                L.geoJSON(data, {
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup("Wilayah: " + feature.properties.NAME_2);
                    }
                }).addTo(map);
            });

        
        const titiks = @json($titiks);
        titiks.forEach(r => {
            if (r.latitude && r.longitude) {
                L.marker([r.latitude, r.longitude])
                    .addTo(map)
                    .bindPopup("Nama: " + r.nama + "<br>Desa: " + r.desa);
            }
        });
    </script>
</body>
</html>
