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
	        this.bindEvents.bind(this);
            this.loadMap();
	        this.bindEvents();
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
                mapTypeId: google.maps.MapTypeId.ROADMAP
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
		    google.maps.event.addListener
		    (
			    this.marker, 'dragend', function (event)
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