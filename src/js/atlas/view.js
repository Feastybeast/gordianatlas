/* Author: Jay Ripley
*/
$(document).ready(
	function() 
	{
		/*
		 * Setup Map and References
		 */
		$('#mapViewport').gmap3({ 
			action:'init',
    		options:{
				'mapTypeControl': false, 
				'panControl': false, 
				'zoomControl': false, 
				'streetViewControl': false, 
				'mapTypeId': google.maps.MapTypeId.SATELLITE
	    	},
    		events: {
        		zoom_changed: function(map)
        		{
          			// alert(map.getZoom());
        		}
  			}
		});
		
		/*
		 * Load Map Data onto the page.
		 */
		refreshMapData();

		/*
		 * Timeline Details
		 */
		$("#evt_occurance").datepicker(); 
		 
		timelineInitialize();
		timelineUpdate();

		/*
		 * Wire the add event location
		 */
		  $("#addEvent").click(function() {
		 	
		 	var evt_name = $( "#evt_name" );
			var	evt_occurance = $( "#evt_occurance" );
			var	evt_range = $( "#evt_range" );
			var	evt_duration = $("#evt_duration");
			var	evt_descript = $("#evt_descript");
			var	evt_units = $("#evt_units option:selected");
			var	csrf = $("input[name=csrf_test_name]");
			var allFields = $( [] ).add( evt_name ).add( evt_occurance ).add( evt_range ).add(evt_duration).add(evt_descript);
		 
		 	$("#event-form").dialog({
			 	autoOpen: true,
				height: 550,
				width: 500,
				modal: true,
				buttons: {
					"Accept": function() {
						
						var datum = {  
								"evt_name": evt_name.val(),
								"evt_occurance": evt_occurance.val(),
								"evt_range": (evt_range.val() == '') ? 0 : evt_range.val(),
								"evt_duration": (evt_duration.val() == '') ? 0 : evt_duration.val(),
								"evt_descript": evt_descript.val(),
								"evt_units": evt_units.val(),
								"csrf_test_name": csrf.val()
						};
						
										
						$.post(
							"/timeline/add", 
							datum, 
							function(data) 
							{
								timelineUpdate();
								// allFields.val("").removeClass("ui-state-error");
								// $("#event-form").dialog("close");							
							}
						);

						// Clean house.
						allFields.val("").removeClass( "ui-state-error");
						$( this ).dialog( "close" );						
					},
					"Cancel": function() {
						allFields.val("").removeClass("ui-state-error");
						$( this ).dialog( "close" );
					}
				}
		 	});
		  });
	

		/*
		 * Wire the Add Location and Event Items.
		 */
		 $("#addLoc").click(function() {
		 	
		 	var name = $( "#loc_name" ),
				lat = $( "#lat" ),
				lng = $( "#lng" ),
				loc_descript = $("#loc_descript"),
				csrf = $("input[name=csrf_test_name]"),
				allFields = $( [] ).add( name ).add( lat ).add( lng ).add(loc_descript);
		 
		 	$("#location-form").dialog({
			 	autoOpen: true,
				height: 400,
				width: 400,
				modal: true,
				buttons: {
					"Accept": function() {						
						var a = $.post(
							"/map/add", 
							{  
								"name": name.val(),
								"lat": lat.val(),
								"lng": lng.val(),
								"description": loc_descript.val(),
								"csrf_test_name": csrf.val()
							}, 
							function(data) 
							{
								refreshMapData();
								allFields.val("").removeClass("ui-state-error");
								$("#location-form").dialog("close");							
							}
						);

						// Clean house.
						allFields.val("").removeClass( "ui-state-error");						
					},
					"Cancel": function() {
						allFields.val("").removeClass("ui-state-error");
						$( this ).dialog( "close" );
					}
				}
		 	});		 	
		 });
        
		/*
		 * End Document.Ready Function here.
		 */
	}
);

function timelineInitialize()
{
	/*
	 * Wire the timeline behaviors.
	 */
    Timeline.OriginalEventPainter.prototype._showBubble = function(x, y, evt) 
    {
    	res = evt.getClassName().split(" ");
    	updateInfoPane(res[0], res[1]);
    	window.scrollTo(0, 0);
    }
        
	// Timeline boilerplate code.
    var theme = Timeline.ClassicTheme.create();
	eventSource = new Timeline.DefaultEventSource(0);

	var d = Timeline.DateTime.parseGregorianDateTime("1900");		
		                
    var bandInfos = [
        Timeline.createBandInfo({
            width:          "100%", 
            intervalUnit:   Timeline.DateTime.DECADE, 
            intervalPixels: 100,
            eventSource:    eventSource,
            date:           d,
            theme:          theme,
            layout:         'original'  // original, overview, detailed
        })
    ];
        
    tl = Timeline.create(document.getElementById("timelineViewport"), bandInfos, Timeline.HORIZONTAL);
}

function timelineUpdate()
{
    // stop browser caching of data during testing...
    tl.loadJSON("/timeline/view?"+ (new Date().getTime()), function(json, url) {
    	eventSource.clear();
        eventSource.loadJSON(json, url);
    });
}

function refreshMapData()
{
	$.getJSON("/map/view?"+ (new Date().getTime()), function(the_data) 
	{
		$('#mapViewport').gmap3({
			action: 'clear'
		});

		prepMarkers(the_data);
	});
}

// 
function prepMarkers(markers) 
{
	var details = {};

	// Iterate each data element, preparing map info.	
	$.each(markers.locations, function(key, val) 
	{
		$('#mapViewport').gmap3({
			action: 'addMarker',
			lat: val.Lat,
			lng: val.Lng,
			marker: {
				options: {
					title: val.Title
				},
				data: {
					"hr_idx": key
				},
				events: {
					click: function(marker, event, data) 
					{
						updateInfoPane('locations', data.hr_idx);
					}
				}
			}
		});
	});
}

function updateInfoPane(type, key) 
{
	var controller = {
		'events': "timeline",
		'locations': "map"
	}
	
	var url = "/" + controller[type] + "/wiki/" + key.substr(2);
	
	$.get(url, 
		function(data) {
			$("#content A").off("click");
			$("#content").html(data);
	});	
} 