
function isFrontEnd() {
	siteObj.geoSite      	= new geoSite() || null;
	siteObj.mapTargets   	= new mapTargets() || null;
	siteObj.topMenu      	= new topMenu() || null;
	siteObj.formObj			= new application.Form();
}



/**********************************/
/*********************************/
/* page setup #hasing           */
/* controls page loading of scripts and ping for hash */
/******************************/
function geoSite (url, options) {
	
	var settings = {
		timer : 200
	}

	this.options    = jQuery.extend(settings, options);
	
	this.init = function(){
		this.getElements();
		this.setElements();
	},
	
	this.getElements = function(){
		this.lastHash       = null;
		this.isHomepage     = $('body.home').length;
	},

	this.setElements = function(){
		// Good way to split out functonality
		if(this.isHomepage) {
			siteObj.homePageSlider  = new pageSlider() || null;
		} else {
			siteObj.sideBar         = new sidebarMenu() || null;
		}
		// Start the hashing look
		this.isHashed();
	},

	// Initial load check for a hash to load.
	this.isHashed = function() {
		var 
			$this   = this,
			hash    = this.returnHash();
		
		// Does an inital hash exist or not
		if(!hash) {
			this.loadNoHash();
		} else {
			$this.hashPage();
		}   
		
		// Set hash lookup interval
		setInterval(function () { $this.hashPage(); }, $this.options.timer);
	};
	
	// Periodical check for a new hash value to simulate back button ajax action
	this.hashPage = function(tmp) {
		var hash = this.stripHash();
		if(this.lastHash!=hash) {
			this.loadHash(hash);    
		}
	};

	this.loadHash = function(hash) {
		// 
		if(this.isHomepage) {
			
		} else {
			this.triggerSidebar(hash);
		}
	};
	
	this.loadNoHash = function(hash) {
		if(this.isHomepage) {
			
		} else {
			this.triggerSidebar(false);
		}
	};
	
	/* Global methods */
	this.triggerSidebar = function(hash) {
		siteObj.sideBar.triggered(hash);
		this.lastHash = hash;
	};

	/* Helpers */
	this.splitURL = function(o) {
		return o.split('?');
	};
	this.stripHash = function() {
		return this.returnHash().replace('#','');
	};
	this.returnHash = function() {
		return window.location.hash;
	};
	
	// Init
	this.init();
}  
	


function sidebarMenu (options) {

	var settings = {}

	this.options = jQuery.extend(settings, options);
	
	this.init = function(){
		this.menu   = $('.boxy-icon-menu');
		if(this.menu.length) {
			this.getElements();
			this.setElements();
		}
		return this;
	},
	
	// Get dom elements
	this.getElements = function() {
		this.menuItems          = this.menu.find('li a');
		this.container          = $('#middle');
		this.containerItems     = this.container.find('.data-area');
		this.cache              = null;
		this.cacheContent       = null;
	},
	
	// Set function wide objects
	this.setElements = function() {
		this.animating = false;
		this.setContentPane();
		this.setContentClose();
	},  
	
	// Set function wide objects
	this.setContentPane = function() {
		var root = this;
		
		// todo: this was hastily written, clean up event model
		this.menu.on("click", 'a', function(e) {
			//e.preventDefault();
			var 
				$this           = $(this),
				selectedVal     = $this.data('area');
			
			if (root.cache && root.cache.hasClass('is-closed')) {
				root.cache.addClass('is-active');
				root.cacheContent.fadeIn();
			}
			
			if (root.cache && root.cache.data('area') == selectedVal) {
				return false;
			} 
			
			if(selectedVal===null || selectedVal === '') {
				// probably an external link
			} else {
			
				// Remove all the active items
				root.containerItems.removeClass('is-active');
				root.menuItems.removeClass('is-active');
				
				var selectedContentDiv = $('.'+selectedVal);
				var direction = root.getDirection($this);
				selectedContentDiv.addClass('is-active');
				$this.addClass('is-active');
				
				root.setContentHeight(selectedContentDiv)
				
				selectedContentDiv.css({
					top: (direction == 'up' ? '-1000px' : '1000px')
				})
				root.animateIn(selectedContentDiv)
				
				if(root.cacheContent) {
					root.animateOut(root.cacheContent, direction)
				}
				
				root.cache = $this;
				root.cacheContent = selectedContentDiv;
			}    
		});   
	},
	
	this.loadContent = function(link) {
	   link.trigger('click');
	},
	
	this.triggered = function(hash) {
	   var tmpObj = (hash ? this.menu.find('a[data-area='+hash+']') : this.menu.find('a.is-active'));
	   this.loadContent(tmpObj);
	},
			
	this.setContentClose = function(current) {
		var root = this;
		$('.close-button').on("click", function(e) {
			e.preventDefault();
			var 
				$this           = $(this);
				
			root.close();
		});   
	},
	this.close = function(current) {
		this.cache.addClass('is-closed').removeClass('is-active')
		this.cacheContent.fadeOut();
	},
	this.getDirection = function(current) {
		var 
			parents = this.menu.find('li'),
			cIndex = parents.index(current.parent()),
			oIndex = parents.index(this.menu);
		return (cIndex > oIndex ? 'down' : 'up');   
	},
	this.setContentHeight = function(el) {
		this.container.css({
			height: this.getContentHeight(el)
		})
	},
	this.getContentHeight = function(el) {
		var 
			h = el.height(),
			offset = 50;
	   return Math.max(h+offset, this.container.height());
	},
	
	this.animateIn = function(el) {
		var root = this;
		this.animateHelper(el, {
			top: '30px'
		})
	},
	
	this.animateOut = function(el, direction) {
		var root = this;
		this.animateHelper(el, {
			top: (direction == 'up' ? '1000px' : '-1000px') 
		})
	},
	
	this.animateHelper = function(el, opts) {
		var root = this;
		el.animate(
			opts, 
			{
				duration: 500,
				queue: false,
				complete: function() { 
					root.animating = false;
				}
			}
		);
	   
	}
	
	/********/
	/* Run */
	return this.init(); 
}      


// Home page slider
function pageSlider (options) {

	var settings = {}

	this.options = jQuery.extend(settings, options);
	
	this.init = function(){
		this.getElements();
		this.setElements();
	},
	
	// Get dom elements
	this.getElements = function() {
		this.container          = $('.flexslider');
		this.slideContainer     = this.container.find('slides');
		this.pagination         = $('');
	},
	
	// Get dom elements
	this.setElements = function() {
		var root = this;
		this.container.flexslider({
			  animation: "slide",
			  controlsContainer: ".gms-link-menu",
			  directionNav: false
		});
	} 
	
	/********/
	/* Run */
	return this.init(); 
}      


// Top menu active states with no backend.
function topMenu (options) {

	var settings = {
		className: 'is-active' 
	}

	this.options = jQuery.extend(settings, options);
	
	this.init = function(){
		this.getElements();
		this.setElements();
	},
	
	// Get dom elements
	this.getElements = function() {
		this.selectNode     = $('#menu-selector');
		this.menu           = $('#header ul');
		this.menuItems      = this.menu.find('li a');
	},
	
	// Get dom elements
	this.setElements = function() {
		var indexOf = this.selectNode.data('li');
		$(this.menuItems[indexOf]).addClass(this.options.className)
	} 
	
	/********/
	/* Run */
	return this.init(); 
}


// Allows links to have lat longs and move background map to those co-ords
function mapTargets (options) {

	var settings = {}

	this.options = jQuery.extend(settings, options);
	
	this.init = function(){
		this.getElements();
		this.setElements();
	},
	
	// Get dom elements
	this.getElements = function() {
		this.mapTargetItems = $('a[data-map-target]');
	},
	
	// Get dom elements
	this.setElements = function() {
		var root = this;
		
		this.mapTargetItems.on("click", function(e) {
			e.preventDefault();
			var 
				target = $(this);
			root.setCoords(root.getCoords(target))
		});
	},
	
	// Get coords
	this.getCoords = function(el) {
		var 
			pairs   = el.data('map-target').split(','),
			length  = pairs.length,
			tmpArr  = [];
		for (var i = 0; i < length; i++) {
			tmpArr.push(pairs[i].split('|'))
		}
		return tmpArr;
	},
	
	// set markers
	this.setCoords = function(arr) {
		var length = arr.length;
		siteObj.map.setCenter(new google.maps.LatLng(12.039321, 107.050781));
		siteObj.map.setZoom(5);
		for (var i = 0; i < length; i++) {
			this.toMap(
				new google.maps.LatLng(arr[i][0],arr[i][1])
			)
		}
	},
	
	// change map object
	this.toMap = function(location) {
		return this.toMarker(location);
	},
	
	// change map object
	this.toMarker = function(location) {
		return new google.maps.Marker({
			position: location,
			map: siteObj.map
		});
	}
	
	/********/
	/* Run */
	return this.init(); 
}        












// Constructor for forms
$JSKK.Class.create
(
	{
		$namespace:	'application',
		$name:		'Form'
	}
)
(
	{},
	{
		options: {
			formClass: '.form',
			className: 'is-active' 
		},
		init: function(opts)
		{
			this.options = jQuery.extend(this.options, opts);
			this.getElements();
			this.setElements();
		},
		getElements: function()
		{
			this.forms = $(this.options.formClass);
			this.selects = $(this.options.formClass + " select");
			//get all checks radios.
		},
		setElements: function()
		{
			// apply select code
			this.selects.selectbox();
			
			var length = this.forms.length;
			for (var i = 0; i < length; i++) {
				new application.FormObj({
					form: $(this.forms[i]),
					options: {}
				});
			}
			
		}
	}
);

// Common functionality for forms
$JSKK.Class.create
(
	{
		$namespace:	'application',
		$name:		'FormBase'
	}
)
(
	{},
	{
		// Submit button
		createSubmitBtn: function()
		{
			this.submit = this.appendSubmitBtn(this.eventsSubmitBtn(this.buildSubmitBtn()));
		},
		buildSubmitBtn: function()
		{
			return $('<a></a>').addClass('submit').html('Submit');
		},
		appendSubmitBtn: function(submit)
		{
			return this.form.append(submit);
		},
		eventsSubmitBtn: function(submit)
		{
			var root = this;
			return submit.one("click", function(e) {
				e.preventDefault();
				root.submitForm(root.dataCollection());
			}); 
		},
		
		// Data collection
		dataCollection: function(submit)
		{
			var 
				editables 	= this.form.find('.editable'),
				editLength 	= editables.length,
				selects 	= this.form.find('select'),
				selLength 	= selects.length,
				dataObj 	= {
					formName: this.form.data('action')
				};
			for (var i = 0; i < selLength; i++) {
				var tmpSelect = $(selects[i]);
				dataObj[tmpSelect.data('name')] = tmpSelect.val();
			}
			for (var i = 0; i < editLength; i++) {
				var tmpEdit = $(editables[i]);
				dataObj[tmpEdit.data('name')] = tmpEdit.text();
			}
			return dataObj;
		},
		// Form submission
		submitForm: function(dataObj)
		{
			var root = this;
			this.disableForm();
			//setTimeout(function () { root.enableForm() }, 10000);
			this.sendRequest(dataObj);
		},
		resetForm: function()
		{
			this.form.reset();
		},
		disableForm: function()
		{
			this.form.addClass('disabled');
			this.submit.addClass('disabled');
		},
		enableForm: function()
		{
			this.form.removeClass('disabled');
			this.submit.removeClass('disabled');
			this.eventsSubmitBtn(this.submit);
		},
		// request helper
        requestHelper: function(req, callback) {
        	console.log(1)
            $.ajax({
                url: req.url,
                type: (req.type || "POST"),
                data: JSON.stringify(req.data),
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: callback
            }).error(function(res) { alert("Server error :: " + res.statusText); }); 
        }
		
	}
);


// form objs
$JSKK.Class.create
(
	{
		$namespace:	'application',
		$name:		'FormObj',
		$extends:   application.FormBase
	}
)
(
	{},
	{
		options: {
			
		},
		init: function(data)
		{
			this.options 	= jQuery.extend(this.options, data.options);
			this.form 		= data.form;
			this.submitting	= false;
			this.getElements();
			this.setElements();
		},
		getElements: function()
		{
			
		},
		setElements: function()
		{
			// Currently we are just going to procedurally setup the form.
			this.createSubmitBtn();
		},
		sendRequest: function(dataObj) {
            var root = this,
                req = {
                    url: "http://local.geomash.com/formtest-geomash.php",
                    data: dataObj
                },
                callback = function(response) {
                    if(response.code != -1) {
                        root.dealWithError(response);
                        root.enableForm();
                    } else {
                    	// do somthign here to indicate success
                        root.resetForm();
                    }
                };
            this.requestHelper(req, callback)
        },
        dealWithError: function(res)
		{
			var 
				currents 		= this.form.find('.error'),
				errors 			= res.error,
				errorsLength 	= errors.length;
			
			currents.remove();
				
			for (var i = 0; i < errorsLength; i++) {
				var tmp = errors[i];
				
				var obj = $('<div class="error">Please enter something</div>');
				var input = $('div[data-name="'+tmp.objname+'"]');
				obj.insertAfter(input);
			}
		}
		
	}
);
