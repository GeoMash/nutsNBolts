$JSKK.Class.create
(
    {
        $namespace:	'nutsnbolts.widget.map',
        $name:		'Main'
    }
)
(
    {},
    {
        id: null,
        mapOptions: {},
        map: null,
	    marker: null,
	    location: null,
	    zoomSlider: null,
        field:
        {
			lng:    null,
	        lat:    null,
	        zoom:   null
        },
        init: function(id)
        {
            this.id=id;
	        console.log(id);
	        this.field.lat=$('#map-'+id+' .map-lat');
	        this.field.lng=$('#map-'+id+' .map-lng');
	        this.field.zoom=$('#map-'+id+' .map-zoom');
	        this.zoomSlider=$('#map-'+id+' .zoomSlider');
	        this.bindEvents.bind(this);
            this.loadMap();
	        this.bindEvents();

			//Slider initialize
	        this.zoomSlider.slider
	        (
		        {
			        min: 1,
			        max: 20,
			        animate: true,
			        step:1,
			        value:8,
			        slide: function(event, ui)
			        {
				        var handle=this.zoomSlider.find('.ui-slider-handle');
				        this.field.zoom.val(ui.value);
				        this.onFieldChange();
				        // Add zoom value on top of the handler
				        (function()
				        {
					        this.zoomSlider.find('p').css('left', handle.css('left'))
						                             .text(ui.value);
				        }.bind(this)).defer(1);
			        }.bind(this)
		        }
	        );
	        this.zoomSlider.append('<p></p>');
        },
        loadMap: function()
        {
            if (Object.isUndefined(window.$mapCallbacks))
            {
                window.$mapCallbacks=[];
            }
            var index=window.$mapCallbacks.length;
            window.$mapCallbacks[index]=this.onMapLoaded.bind(this);
            window['$mapCallback_'+index]=window.$mapCallbacks[index];
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = 'https://maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=window.$mapCallback_'+index;
            document.body.appendChild(script);
        },
	    bindEvents: function()
	    {
			//Bind field change events.
		    this.field.lat.bind("change",this.onFieldChange.bind(this));
		    this.field.lng.bind("change",this.onFieldChange.bind(this));
		    this.field.zoom.bind("change",this.onFieldChange.bind(this));
	    },
        onMapLoaded: function()
        {
	        if(!Object.isEmpty(this.field.lat.val()) && Object.isNumeric(this.field.lat.val()))
	        {
		        var defaultLatLng= new google.maps.LatLng(this.field.lat.val(), this.field.lng.val());
	        }
	        else
	        {
		        var defaultLatLng= new google.maps.LatLng(-34.397, 150.644);
	        }
            var mapOptions =
            {
                center: defaultLatLng,
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
	            disableDefaultUI:true
            },
            mapEl=$('#'+this.id);
            this.map = new google.maps.Map(mapEl[0],mapOptions);
            mapEl.width('100%').height(400);
	        this.onFieldChange();
        },
	    onFieldChange: function(event)
	    {
		    //Update Google Map, reflecting field change.
		    var lng = this.field.lng.val();
		    var lat = this.field.lat.val();
		    var zoom = this.field.zoom.val();
		    if(!zoom)
		    {
				zoom=8;
		    }
		    else
		    {
			    zoom=parseInt(zoom);
		    }
		    if(lng.length && lat.length)
		    {
			    var location = new google.maps.LatLng(lat,lng);
			    this.map.setCenter(location);
			    this.map.setZoom(zoom);

			    if (!Object.isNull(this.marker))
			    {
			        this.marker.setMap(null);
				    delete this.marker;
			    }
				this.marker = new google.maps.Marker
				(
					{
						position: location,
						map:this.map,
						draggable: true,
				    }
				)
		    };
		    //change Lat and Lng by drag down the marker & update the center
		    google.maps.event.addListener
		    (
			    this.marker,
			    'dragend',
			    function(event)
			    {
				    var position=this.marker.getPosition();
				    this.field.lng.val(position.lng());
				    this.field.lat.val(position.lat());
				    var newLatLng= new google.maps.LatLng(this.field.lat.val(), this.field.lng.val());
				    this.map.panTo(newLatLng);
			    }.bind(this)
		    )
	    }
    }
);