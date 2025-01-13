function isMarkerInsidePolygon(marker, poly) {
    var inside = false;
    var x = marker.lat, y = marker.lng;
    for (var ii=0;ii<poly.getLatLngs().length;ii++){
        var polyPoints = poly.getLatLngs()[ii];
        for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            var xi = polyPoints[i].lat, yi = polyPoints[i].lng;
            var xj = polyPoints[j].lat, yj = polyPoints[j].lng;

            var intersect = ((yi > y) != (yj > y))
                && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
    }

    return inside;
};


// Creating map options
var mapOptions = {
    center: [19.50066, -99.13977], //19.50393, -99.13848
    zoom: 16
}

// Creating a map object
var map = new L.map('map', mapOptions);

// Creating a Layer object
var layer = new L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    minZoom: 15,
    subdomains:['mt0','mt1','mt2','mt3']
});

// Adding layer to the map
map.addLayer(layer);

let marker = L.marker([19.50066, -99.13977]).addTo(map);
document.getElementById('latitud').value = 19.50066;
document.getElementById('longitud').value = -99.13977;


// Definir un polígono para la zona permitida
var permittedArea = L.polygon([
    [19.50628008702418,-99.14700329303741], 
    [19.50613850421442,-99.14648830890656], 
    [19.505951412454284,-99.14615571498871], 
    [19.505319344096524,-99.14529740810394],
    [19.50490090693011,-99.14368808269502],
    [19.508556922907136,-99.14148330688478],
    [19.509245618173917,-99.13962721824647],
    [19.508308789334993,-99.13856506347658],
    [19.50714913963418,-99.13740634918214], 
    [19.503225916059073,-99.13317382335661], 
    [19.50273036632624,-99.13145184516907], 
    [19.501926358938498,-99.13156986236572], 
    [19.499711525470772,-99.13199365139008], 
    [19.49663700318986,-99.1326320171356], 
    [19.494518178342986,-99.13294851779938], 
    [19.495443587356398,-99.1370952129364], 
    [19.496085807987576,-99.13997054100037], 
    [19.496399332094327,-99.14056062698364], 
    [19.498280463978986,-99.14466440677643], 
    [19.499200794730072,-99.14690136909485], 
    [19.5010111003823,-99.14593040943146], 
    [19.502366288004175,-99.14895057678223]
], { color: "#33691e", weight: 1 }).addTo(map);

map.on('click', (event) => {
    // Verificar si el punto clicado está dentro del polígono
    if (isMarkerInsidePolygon(event.latlng, permittedArea)) {
        if (marker !== null) {
            map.removeLayer(marker);
        }
        marker = L.marker([event.latlng.lat, event.latlng.lng]).addTo(map);

        // Mostrar las coordenadas en los campos
        document.getElementById('latitud').value = event.latlng.lat;
        document.getElementById('longitud').value = event.latlng.lng;
    } else {
        alert("Coordenadas fuera de la zona permitida (Zacatenco).");
    }
});
