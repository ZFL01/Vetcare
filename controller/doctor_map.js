// Small helper to load Google Maps API and initialize a map in a container
(function(global){
  const state = { loaded: false, loading: false, promise: null };

  function loadGoogleMapsApi() {
    if (state.loaded) return Promise.resolve(window.google.maps);
    if (state.loading) return state.promise;

    const apiKey = window.GOOGLE_MAPS_API_KEY || '';
    if (!apiKey) return Promise.reject(new Error('GOOGLE_MAPS_API_KEY not provided'));

    state.loading = true;
    state.promise = new Promise((resolve, reject) => {
      // callback name must be unique
      const cbName = '__vetcare_gmaps_cb_' + Date.now();
      window[cbName] = function() {
        state.loaded = true;
        state.loading = false;
        resolve(window.google.maps);
        try { delete window[cbName]; } catch(e){}
      };

      const script = document.createElement('script');
      script.async = true;
      script.defer = true;
      script.onerror = function(err) {
        state.loading = false;
        reject(new Error('Failed to load Google Maps API'));
      };
      script.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(apiKey) + '&callback=' + cbName;
      document.head.appendChild(script);
    });
    return state.promise;
  }

  // initDoctorMap(containerId, lat, lng, options)
  function initDoctorMap(containerId, lat, lng, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return Promise.reject(new Error('Map container not found'));
    if (typeof lat === 'undefined' || typeof lng === 'undefined') return Promise.reject(new Error('Invalid coordinates'));

    return loadGoogleMapsApi().then((maps) => {
      // Clean previous map children
      container.innerHTML = '';
      const mapOptions = Object.assign({
        center: { lat: Number(lat), lng: Number(lng) },
        zoom: options.zoom || 15,
        disableDefaultUI: options.disableUI || false,
      }, options.mapOptions || {});

      const map = new maps.Map(container, mapOptions);
      new maps.Marker({ position: { lat: Number(lat), lng: Number(lng) }, map });
      return map;
    });
  }

  // expose
  global.VetcareMap = {
    load: loadGoogleMapsApi,
    initDoctorMap: initDoctorMap
  };
})(window);
