/*
 * CJSLocationPicker
 *
 * A click-to-place location map for the advert forms and the admin "add advert" screen.
 * Built on Leaflet and OpenStreetMap, the same as the map on the public job page, so no
 * Google account or API key is involved.
 *
 *   var picker = CJSLocationPicker({
 *     map:         'map',            // the map element (or its id); it needs a height set in CSS
 *     lat:         'lat',            // the latitude input: an element, an id, or a field name
 *     lng:         'lng',            // the longitude input
 *     multiple:    true,             // true: any number of markers, stored as comma separated
 *                                    //       lists "a,b,c" (what the forms save)
 *                                    // false: one marker that is moved by clicking or dragging
 *     clearButton: 'delete-markers', // optional: a button that removes every marker
 *     draggable:   false,            // let markers be dragged
 *     restore:     true,             // draw whatever is already in the lat / lng inputs
 *     syncFields:  false,            // single marker only: typing in the inputs moves the pin
 *     center:      [53.419354, -1.953678],
 *     zoom:        6,
 *     focusZoom:   10,               // zoom used when there is a single saved marker
 *     decimals:    6                 // precision written to the inputs
 *   });
 *
 *   picker.refresh()   re-measure the map (use after showing a panel that was hidden)
 *   picker.clear()     remove every marker and empty the inputs
 *
 * Leaflet is loaded from unpkg if the page has not already loaded it.
 */
(function (window, document) {
  'use strict';

  var LEAFLET_CSS = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
  var LEAFLET_CSS_SRI = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
  var LEAFLET_JS = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  var LEAFLET_JS_SRI = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
  var TILES = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
  var ATTRIBUTION = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

  var leafletWaiting = null;

  function whenLeafletReady(callback) {
    if (window.L && window.L.map) { callback(); return; }
    if (leafletWaiting) { leafletWaiting.push(callback); return; }
    leafletWaiting = [callback];

    if (!document.querySelector('link[href^="https://unpkg.com/leaflet@"]')) {
      var css = document.createElement('link');
      css.rel = 'stylesheet';
      css.href = LEAFLET_CSS;
      css.integrity = LEAFLET_CSS_SRI;
      css.crossOrigin = '';
      document.head.appendChild(css);
    }

    var script = document.createElement('script');
    script.src = LEAFLET_JS;
    script.integrity = LEAFLET_JS_SRI;
    script.crossOrigin = '';
    script.onload = function () {
      var queue = leafletWaiting;
      leafletWaiting = null;
      for (var i = 0; i < queue.length; i++) { queue[i](); }
    };
    script.onerror = function () {
      leafletWaiting = null;
      if (window.console) { console.error('CJSLocationPicker: could not load Leaflet'); }
    };
    document.head.appendChild(script);
  }

  function byId(ref) {
    if (!ref) { return null; }
    return ref.nodeType === 1 ? ref : document.getElementById(ref);
  }

  // A field can be given as an element, an id, or the input's name. A name is looked for inside
  // the map's own form first: the advert forms are built by Perch, which prefixes ids but keeps
  // the name, and the same name can appear in other forms on the page.
  function field(ref, mapEl) {
    if (!ref) { return null; }
    if (ref.nodeType === 1) { return ref; }
    var selector = '[name="' + ref + '"]';
    var form = mapEl.closest ? mapEl.closest('form') : null;
    return (form && form.querySelector(selector)) || document.getElementById(ref) || document.querySelector(selector);
  }

  function numbers(value) {
    return String(value || '').split(',').map(function (part) { return part.trim(); }).filter(Boolean);
  }

  function create(options) {
    var o = {
      multiple: true,
      draggable: false,
      restore: true,
      syncFields: false,
      center: [53.419354, -1.953678],
      zoom: 6,
      focusZoom: 10,
      decimals: 6
    };
    for (var key in options) { if (Object.prototype.hasOwnProperty.call(options, key)) { o[key] = options[key]; } }

    var mapEl = byId(o.map);
    var picker = { map: null, markers: [], refresh: function () {}, clear: function () {} };
    if (!mapEl) { return picker; }

    var latField = field(o.lat, mapEl);
    var lngField = field(o.lng, mapEl);

    function start() {
      var L = window.L;
      var map = L.map(mapEl, { scrollWheelZoom: false });
      picker.map = map;

      L.tileLayer(TILES, { maxZoom: 19, attribution: ATTRIBUTION }).addTo(map);
      map.setView(o.center, o.zoom, { animate: false });

      // Wheel-zooming only after the map has been clicked, so scrolling the form isn't hijacked.
      map.on('focus', function () { map.scrollWheelZoom.enable(); });
      map.on('blur', function () { map.scrollWheelZoom.disable(); });

      function round(n) { return Number(n.toFixed(o.decimals)); }

      function writeFields() {
        if (!latField || !lngField) { return; }
        var lats = [];
        var lngs = [];
        for (var i = 0; i < picker.markers.length; i++) {
          var p = picker.markers[i].getLatLng();
          lats.push(round(p.lat));
          lngs.push(round(p.lng));
        }
        latField.value = lats.join(',');
        lngField.value = lngs.join(',');
      }

      function addMarker(latlng) {
        latlng = L.latLng(latlng.lat, latlng.lng);
        if (latlng.lng > 180 || latlng.lng < -180) { latlng = latlng.wrap(); }  // dragged round the world

        if (!o.multiple && picker.markers.length) {
          picker.markers[0].setLatLng(latlng);
          return picker.markers[0];
        }

        var marker = L.marker(latlng, { draggable: !!o.draggable, alt: 'Location marker' }).addTo(map);
        if (o.draggable) { marker.on('dragend', writeFields); }
        picker.markers.push(marker);
        return marker;
      }

      function clear() {
        for (var i = 0; i < picker.markers.length; i++) { map.removeLayer(picker.markers[i]); }
        picker.markers = [];
        writeFields();
      }

      // Frame whatever is on the map: one marker is centred, several are fitted into view.
      function applyView() {
        var still = { animate: false };   // framing on first display should not animate
        if (picker.markers.length === 1) {
          map.setView(picker.markers[0].getLatLng(), o.focusZoom, still);
        } else if (picker.markers.length > 1) {
          var bounds = L.latLngBounds(picker.markers.map(function (m) { return m.getLatLng(); }));
          map.fitBounds(bounds, { padding: [30, 30], maxZoom: 12, animate: false });
        } else {
          map.setView(o.center, o.zoom, still);
        }
      }

      if (o.restore && latField && lngField) {
        var lats = numbers(latField.value);
        var lngs = numbers(lngField.value);
        if (lats.length && lats.length === lngs.length) {
          for (var i = 0; i < lats.length; i++) {
            var la = parseFloat(lats[i]);
            var ln = parseFloat(lngs[i]);
            if (isNaN(la) || isNaN(ln) || (la === 0 && ln === 0)) { continue; }
            addMarker({ lat: la, lng: ln });
            if (!o.multiple) { break; }
          }
        }
      }
      applyView();

      map.on('click', function (event) {
        addMarker(event.latlng);
        writeFields();
      });

      var button = byId(o.clearButton);
      if (button) { button.addEventListener('click', clear); }

      // Typing coordinates into the inputs moves the single pin.
      if (o.syncFields && !o.multiple && latField && lngField) {
        var sync = function () {
          var la = parseFloat(latField.value);
          var ln = parseFloat(lngField.value);
          if (isNaN(la) || isNaN(ln) || (la === 0 && ln === 0)) { return; }
          addMarker({ lat: la, lng: ln });
          map.panTo([la, ln]);
        };
        latField.addEventListener('change', sync);
        lngField.addEventListener('change', sync);
      }

      // The map often sits in a panel that starts hidden, where it measures 0 x 0. Leaflet has to be
      // told when its size changes, and saved markers are framed once the map first has a size.
      // That happens when the page calls refresh() as it shows the panel, or, failing that, when
      // the browser reports the container's size change.
      var sized = mapEl.clientWidth > 0;
      function frameOnceSized() {
        if (!sized && mapEl.clientWidth > 0) {
          sized = true;
          applyView();
        }
      }
      function resized() {
        map.invalidateSize();
        frameOnceSized();
      }
      if (window.ResizeObserver) {
        new window.ResizeObserver(resized).observe(mapEl);
      } else {
        window.addEventListener('resize', resized);
      }

      picker.refresh = function () {
        map.invalidateSize();
        frameOnceSized();
      };
      picker.clear = clear;
      picker.ready = true;
    }

    function begin() { whenLeafletReady(start); }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', begin);
    } else {
      begin();
    }

    return picker;
  }

  window.CJSLocationPicker = create;
})(window, document);
