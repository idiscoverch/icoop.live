/*!
Copyright (c) 2014 Dominik Moritz

This file is part of the leaflet locate control. It is licensed under the MIT license.
You can find the project at: https://github.com/domoritz/leaflet-locatecontrol
*/
(function (factory, window) {
     // see https://github.com/Leaflet/Leaflet/blob/master/PLUGIN-GUIDE.md#module-loaders
     // for details on how to structure a leaflet plugin.

    // define an AMD module that relies on 'leaflet'
    if (typeof define === 'function' && define.amd) {
        define(['leaflet'], factory);

    // define a Common JS module that relies on 'leaflet'
    } else if (typeof exports === 'object') {
        module.exports = factory(require('leaflet'));
    }

    // attach your plugin to the global 'L' variable
    if(typeof window !== 'undefined' && window.L){
        window.L.Locate = factory(L);
    }

} (function (L){
    L.Control.Locate = L.Control.extend({
        options: {
            position: 'topleft',
            drawCircle: true,
            follow: false,  // follow with zoom and pan the user's location
            stopFollowingOnDrag: false, // if follow is true, stop following when map is dragged (deprecated)
            // if true locate control remains active on click even if the user's location is in view.
            // clicking control will just pan to location
            remainActive: false,
            markerClass: L.circleMarker, // L.circleMarker or L.marker
            // range circle
            circleStyle: {
                color: '#136AEC',
                fillColor: '#136AEC',
                fillOpacity: 0.15,
                weight: 2,
                opacity: 0.5
            },
            // inner marker
            markerStyle: {
                color: '#136AEC',
                fillColor: '#2A93EE',
                fillOpacity: 0.7,
                weight: 2,
                opacity: 0.9,
                radius: 5
            },
            // changes to range circle and inner marker while following
            // it is only necessary to provide the things that should change
            followCircleStyle: {},
            followMarkerStyle: {
                //color: '#FFA500',
                //fillColor: '#FFB000'
            },
            icon: 'fa fa-map-marker',  // fa-location-arrow or fa-map-marker
            iconLoading: 'fa fa-spinner fa-spin',
            circlePadding: [0, 0],
            metric: true,
            onLocationError: function(err) {
                // this event is called in case of any location error
                // that is not a time out error.
                alert(err.message);
            },
            onLocationOutsideMapBounds: function(control) {
                // this event is repeatedly called when the location changes
                control.stopLocate();
                alert(control.options.strings.outsideMapBoundsMsg);
            },
            setView: true, // automatically sets the map view to the user's location
            // keep the current map zoom level when displaying the user's location. (if 'false', use maxZoom)
            keepCurrentZoomLevel: false,
            showPopup: true, // display a popup when the user click on the inner marker
            strings: {
                title: "Show me where I am",
                popup: "You are within {distance} {unit} from this point",
                outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
            },
            locateOptions: {
                maxZoom: Infinity,
                watch: true  // if you overwrite this, visualization cannot be updated
            }
        },

        initialize: function (options) {
            L.Map.addInitHook(function () {
                if (this.options.locateControl) {
                    this.locateControl = L.control.locate();
                    this.addControl(this.locateControl);
                }
            });

            for (var i in options) {
                if (typeof this.options[i] === 'object') {
                    L.extend(this.options[i], options[i]);
                } else {
                    this.options[i] = options[i];
                }
            }
        },

        onAdd: function (map) {
            var container = L.DomUtil.create('div',
                'leaflet-control-locate leaflet-bar leaflet-control');

            var self = this;
            this._layer = new L.LayerGroup();
            this._layer.addTo(map);
            this._event = undefined;

            this._locateOptions = this.options.locateOptions;
            L.extend(this._locateOptions, this.options.locateOptions);
            L.extend(this._locateOptions, {
                setView: false // have to set this to false because we have to
                               // do setView manually
            });

            // extend the follow marker style and circle from the normal style
            var tmp = {};
            L.extend(tmp, this.options.markerStyle, this.options.followMarkerStyle);
            this.options.followMarkerStyle = tmp;
            tmp = {};
            L.extend(tmp, this.options.circleStyle, this.options.followCircleStyle);
            this.options.followCircleStyle = tmp;

            this._link = L.DomUtil.create('a', 'leaflet-bar-part leaflet-bar-part-single', container);
            this._link.href = '#';
            this._link.title = this.options.strings.title;

            this._icon = L.DomUtil.create('span', this.options.icon, this._link);

            L.DomEvent
                .on(this._link, 'click', L.DomEvent.stopPropagation)
                .on(this._link, 'click', L.DomEvent.preventDefault)
                .on(this._link, 'click', function() {
                    var shouldStop = (self._event === undefined || map.getBounds().contains(self._event.latlng)
                        || !self.options.setView || isOutsideMapBounds());
                    if (!self.options.remainActive && (self._active && shouldStop)) {
                        stopLocate();
                    } else {
					   
                        locate();
                    }
                })
                .on(this._link, 'dblclick', L.DomEvent.stopPropagation);

            var locate = function () {
                if (self.options.setView) {
                    self._locateOnNextLocationFound = true;
                }
                if(!self._active) {
				    bool_lf_moi = 1;
					if (routeline) {
						map.removeLayer(routeline);
					};
					
					for (i = 0; i < routemarker.length; i++) {
							map.removeLayer(routemarker[i]);
						};
					
					
                    map.locate(self._locateOptions);
                }
                self._active = true;
                if (self.options.follow) {
                    startFollowing();
                }
                if (!self._event) {
                    setClasses('requesting');
                } else {
                    visualizeLocation();
                }
            };

            var onLocationFound = function (e) { //alert('jj'); //if (bool_locatefound == 1) {
                // no need to do anything if the location has not changed
				//alert('moi'+bool_lf_moi);
				if (bool_lf_moi == 1) {
                if (self._event &&
                    (self._event.latlng.lat === e.latlng.lat &&
                     self._event.latlng.lng === e.latlng.lng &&
                     self._event.accuracy === e.accuracy)) {
                    return;
                }

                if (!self._active) {
                    return;
                }

                self._event = e;

                if (self.options.follow && self._following) {
                    self._locateOnNextLocationFound = true;
                }

                visualizeLocation();
				  bool_lf_moi = 0;
				}
            };

            var startFollowing = function() {
                map.fire('startfollowing', self);
                self._following = true;
                if (self.options.stopFollowingOnDrag) {
                    map.on('dragstart', stopFollowing);
                }
            };

            var stopFollowing = function() {
                map.fire('stopfollowing', self);
                self._following = false;
                if (self.options.stopFollowingOnDrag) {
                    map.off('dragstart', stopFollowing);
                }
                setContainerStyle();
            };

            var isOutsideMapBounds = function () {
                if (self._event === undefined)
                    return false;
                return map.options.maxBounds &&
                    !map.options.maxBounds.contains(self._event.latlng);
            };

            var visualizeLocation = function() {
                if (self._event.accuracy === undefined)
                    self._event.accuracy = 0;

                var radius = self._event.accuracy;
                if (self._locateOnNextLocationFound) {
                    if (isOutsideMapBounds()) {
                        self.options.onLocationOutsideMapBounds(self);
                    } else {
                        map.fitBounds(self._event.bounds, {
                            padding: self.options.circlePadding,
                            maxZoom: self.options.keepCurrentZoomLevel ? map.getZoom() : self._locateOptions.maxZoom
                        });
                    }
                    self._locateOnNextLocationFound = false;
                }

                // circle with the radius of the location's accuracy
                var style, o;
                if (self.options.drawCircle) {
                    if (self._following) {
                        style = self.options.followCircleStyle;
                    } else {
                        style = self.options.circleStyle;
                    }

                    if (!self._circle) {
                        self._circle = L.circle(self._event.latlng, radius, style)
                            .addTo(self._layer);
                    } else {
                        self._circle.setLatLng(self._event.latlng).setRadius(radius);
                        for (o in style) {
                            self._circle.options[o] = style[o];
                        }
                    }
                }

                var distance, unit;
                if (self.options.metric) {
                    distance = radius.toFixed(0);
                    unit = "meters";
                } else {
                    distance = (radius * 3.2808399).toFixed(0);
                    unit = "feet";
                }

                // small inner marker
                var mStyle;
                if (self._following) {
                    mStyle = self.options.followMarkerStyle;
                } else {
                    mStyle = self.options.markerStyle;
                }

                if (!self._marker) {
                    self._marker = self.options.markerClass(self._event.latlng, mStyle)
                        .addTo(self._layer);
                } else {
                    self._marker.setLatLng(self._event.latlng);
                    for (o in mStyle) {
                        self._marker.options[o] = mStyle[o];
                    }
                }

		        var t = self.options.strings.popup;
                if (self.options.showPopup && t) {
                  self._marker.bindPopup(L.Util.template(t, {distance: distance, unit: unit}))
                      ._popup.setLatLng(self._event.latlng);
                }

                setContainerStyle();
            };

            var setContainerStyle = function() {
                if (!self._container)
                    return;
                if (self._following) {
                    setClasses('following');
                } else {
                    setClasses('active');
                }
            };

            var setClasses = function(state) {
                if (state == 'requesting') {
                    L.DomUtil.removeClasses(self._container, "active following");
                    L.DomUtil.addClasses(self._container, "requesting");

                    L.DomUtil.removeClasses(self._icon, self.options.icon);
                    L.DomUtil.addClasses(self._icon, self.options.iconLoading);
                } else if (state == 'active') {
                    L.DomUtil.removeClasses(self._container, "requesting following");
                    L.DomUtil.addClasses(self._container, "active");

                    L.DomUtil.removeClasses(self._icon, self.options.iconLoading);
                    L.DomUtil.addClasses(self._icon, self.options.icon);
                } else if (state == 'following') {
                    L.DomUtil.removeClasses(self._container, "requesting");
                    L.DomUtil.addClasses(self._container, "active following");

                    L.DomUtil.removeClasses(self._icon, self.options.iconLoading);
                    L.DomUtil.addClasses(self._icon, self.options.icon);
                }
            };

            var resetVariables = function() {
                self._active = false;
                self._locateOnNextLocationFound = self.options.setView;
                self._following = false;
            };

            resetVariables();

            var stopLocate = function() {
                map.stopLocate();
                map.off('dragstart', stopFollowing);
                if (self.options.follow && self._following) {
                    stopFollowing();
                }

                L.DomUtil.removeClass(self._container, "requesting");
                L.DomUtil.removeClass(self._container, "active");
                L.DomUtil.removeClass(self._container, "following");
                resetVariables();

                self._layer.clearLayers();
                self._marker = undefined;
                self._circle = undefined;
            };

            var onLocationError = function (err) {
                // ignore time out error if the location is watched
                if (err.code == 3 && self._locateOptions.watch) {
                    return;
                }

                stopLocate();
                self.options.onLocationError(err);
            };

            // event hooks
            map.on('locationfound', onLocationFound, self);
            map.on('locationerror', onLocationError, self);
            map.on('unload', stopLocate, self);

            // make locate functions available to outside world
            this.locate = locate;
            this.stopLocate = stopLocate;
            this.stopFollowing = stopFollowing;

            return container;
        }
    });

    L.Map.addInitHook(function () {
        if (this.options.locateControl) {
            this.locateControl = L.control.locate();
            this.addControl(this.locateControl);
        }
    });

    L.control.locate = function (options) {
        return new L.Control.Locate(options);
    };

    (function(){
      // leaflet.js raises bug when trying to addClass / removeClass multiple classes at once
      // Let's create a wrapper on it which fixes it.
      var LDomUtilApplyClassesMethod = function(method, element, classNames) {
        classNames = classNames.split(' ');
        classNames.forEach(function(className) {
            L.DomUtil[method].call(this, element, className);
        });
      };

      L.DomUtil.addClasses = function(el, names) { LDomUtilApplyClassesMethod('addClass', el, names); };
      L.DomUtil.removeClasses = function(el, names) { LDomUtilApplyClassesMethod('removeClass', el, names); };
    })();
    return L.Control.Locate;
}, window));
