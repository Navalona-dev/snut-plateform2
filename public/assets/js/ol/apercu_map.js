var map = new ol.Map({
  layers: [
    new ol.layer.Tile({
      source: new ol.source.MapQuest({layer: 'sat'})
    })
  ],
  target: 'map',
  view: new ol.View({
    center: [518034, 795304],
    zoom: 19,
    maxZoom: 24,
    minZoom: 2
  })
});


var plof_bypass = new ol.layer.Image({
    source: new ol.source.ImageVector({
      source: new ol.source.GeoJSON({
        projection: 'EPSG:29702',
        url: '{{ path('traitement_dossier_communecouche_plof_ajax') }}'}
      }),

      style: new ol.style.Style({
        fill: new ol.style.Fill({
          color: '#ECF0F1'
        }),
        stroke: new ol.style.Stroke({
          color: '#317589',
          width: 1
        })
      })
    }),
    visible: true,
    name: 'plof_bypass'
});

map.addLayer(plof_bypass);