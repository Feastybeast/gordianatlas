/* Author: Jay Ripley
*/
$(document).ready(
	function() 
	{
		$.ajaxSetup({ cache: false });
	
		// Perform the main UI setup.
		ui_wire();
	
		// Load the Default compliment of data.
		defaultWikiPane();		
		refreshMapData();
		timelineUpdate();
	}
);

/* ############################################################################
 * Timeline Related Functions
 * ######################################################################### */
function timelineInitialize()
{
	/*
	 * Wire the timeline behaviors.
	 */
    Timeline.OriginalEventPainter.prototype._showBubble = function(x, y, evt) 
    {
    	res = evt.getClassName().split(" ");
    	getWikiPane(res[0], res[1]);
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

/* ############################################################################
 * Map Related Functions
 * ######################################################################### */
function refreshMapData()
{
	$.getJSON("/map/view?"+ (new Date().getTime()), 
	function(markers) 
	{
		// Remove all markers on the map.
		$('#mapViewport').gmap3({
			action: 'clear'
		});

		// Iterate each data element, adding new markers to the map.	
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
							getWikiPane('locations', data.hr_idx);
						}
					}
				}
			});
		});
	});
}
/* ############################################################################
 * UI Related Functions
 * ######################################################################### */

// Called for Event addition or editing behaviors.
function ui_event(evt)
{
	// Prevent the baseline behavior.
	evt.preventDefault();
	var tgt_url = evt.target.href;
	
	var is_editing = (tgt_url.indexOf('add') == -1) ? true : false;

	// Prepare references to be used later.
	var evt_name = $("#evt_name");
	var	evt_occurance = $("#evt_occurance");
	var	evt_range = $("#evt_range");
	var	evt_duration = $("#evt_duration");
	var	evt_descript = $("#evt_descript");
	var	evt_units = $("#evt_units");
	var	csrf = $("input[name=csrf_test_name]");
	var evt_fields = $([]).add(evt_name).add(evt_occurance).add(evt_range).add(evt_duration).add(evt_descript);

	if (is_editing)
	{
		evt_name.val($('#evt_name_val').text());
		evt_occurance.val($('#evt_occurance_val').text());
		evt_range.val($('#evt_range_val').text());
		evt_duration.val($('#evt_duration_val').text());
		evt_descript.val($('#evt_descript_val').html());

		// Wipe the select boxes.
		var comparator = $('#evt_units_val').text().trim();
		$("#evt_units option").removeAttr('selected');
		$("#evt_units option[value='"+comparator+"']").attr('selected', 'selected');
	}

	// Prep the necessary buttons.
	var btns = {
		"Confirm": function() {
			// Bundle the data to be posted. 
			var datum = {  
				"evt_name": evt_name.val(),
				"evt_occurance": evt_occurance.val(),
				"evt_range": (evt_range.val() == '') ? 0 : evt_range.val(),
				"evt_duration": (evt_duration.val() == '') ? 0 : evt_duration.val(),
				"evt_descript": evt_descript.val(),
				"evt_units": $("#evt_units option:selected").val(),
				"csrf_test_name": csrf.val()
			};

			// Then send it to the system.
			$.post(
				tgt_url, 
				datum, 
				function(data) 
				{
					timelineUpdate();
					
					// It's an edit screen. Reload the UI.
					if(is_editing) 
					{
						edit_id = tgt_url.substring(tgt_url.lastIndexOf('/'));
						getWikiPane('events', edit_id);
					}
				}
			);
			
			
			// Then close the window.
			evt_fields.val("").removeClass("ui-state-error");
			$(this).dialog("close");
	 	},
		"Cancel": function() {
			evt_fields.val("").removeClass("ui-state-error");
			$(this).dialog("close");
		}
	}

	// Add the buttons and display the UI.
	$("#event-form").dialog({ "buttons": btns });
	$("#event-form").dialog("open");	
}

// Called for Location addition or editing behaviors.
function ui_location(evt)
{
	evt.preventDefault();
	var tgt_url = evt.target.href;
	
	var is_editing = (tgt_url.indexOf('add') == -1) ? true : false;
	
	var edit_id = tgt_url.substring(tgt_url.lastIndexOf('/'));

	// Setup references.
 	var name = $( "#loc_name" ),
		lat = $( "#lat" ),
		lng = $( "#lng" ),
		loc_descript = $("#loc_descript"),
		csrf = $("input[name=csrf_test_name]"),
		loc_fields = $([]).add(name).add(lat).add(lng).add(loc_descript);

	if (is_editing)
	{
		name.val($('#loc_name_val').text());
		lat.val($('#lat_val').text());
		lng.val($('#lng_val').text());
		loc_descript.val($('#loc_descript_val').html());
	}

	// Prep the necessary buttons.
	var btns = {
		"Confirm": function() {
			var datum = {  
	            "name": name.val(),
	            "lat": lat.val(),
	            "lng": lng.val(),
	            "description": loc_descript.val(),
	            "csrf_test_name": csrf.val()
	          }

			// Then send it to the system.
			$.post(
				tgt_url, 
				datum, 
				function(data) 
				{
					refreshMapData();
					
					// It's an edit screen. Reload the UI.
					if(is_editing) 
					{
						edit_id = tgt_url.substring(tgt_url.lastIndexOf('/')+1);
						//TODO: We have a bug re: Updating not loading wikipage automatically.
						getWikiPane('locations', edit_id);
					}
				}
			);
				
			loc_fields.val("").removeClass("ui-state-error");
			$(this).dialog("close");
	 	},
		"Cancel": function() {
			loc_fields.val("").removeClass("ui-state-error");
			$(this).dialog("close");
		}
	}
	
	// Add the buttons and display the UI.
	$("#location-form").dialog({ "buttons": btns });
	$("#location-form").dialog("open");	
}

// Called for Deletion events.
function ui_delete(evt)
{
	evt.preventDefault();
	var tgt_url = evt.target.href;
	
	// Prep the necessary buttons.
	var btns = {
		"Confirm": function() {
			$.get(tgt_url, 
				function(data) {
					defaultWikiPane();

					if (tgt_url.indexOf('map') != -1)
					{
	 					refreshMapData();
					}
					else
					{
						timelineUpdate();
					}
				});
				
			$(this).dialog("close");
	 	},
		"Cancel": function() {
			$(this).dialog("close");
		}
	}
	
	// Add the buttons and display the UI.
	$("#deletion_notice").dialog({ "buttons": btns });
	$("#deletion_notice").dialog("open");
}

// Called at application startup, preventing rewired behaviors.
function ui_wire()
{
	/*
	 * Setup Map
	 */
	$('#mapViewport').gmap3({ 
		action:'init',
		options:{
			'mapTypeControl': false, 
			'panControl': false, 
			'zoomControl': false, 
			'streetViewControl': false, 
			'mapTypeId': google.maps.MapTypeId.SATELLITE,
			'minZoom': 1
    	}
	});	

	/*
	 * Deal with the Timeline
	 */
	timelineInitialize();

	// The application will need a date picker.
	$("#evt_occurance").datepicker(); 

	/*
	 * Wire the add event location
	 */
	$("#addEvent").click(
		function(evt)
		{
			ui_event(evt);
		}
	);

	/*
	 * Wire the Add Location and Event Items.
	 */
	$("#addLoc").click(
		function(evt) 
		{
			ui_location(evt);
		}
	);

	/*
	 *
	 */
 	$("#event-form").dialog({
	 	autoOpen: false,
	 	modal: true,
		height: 550,
		width: 500,
		buttons: { }
 	});
	 	
	/*
	 *  Location Form
	 */
  $("#location-form").dialog({
    autoOpen: false,
    modal: true,
    height: 400,
    width: 400,
    buttons: 
    { }
  });
  	
	/*
	 * And a generalized deletion notice.
	 */
	$("#deletion_notice").dialog({
		autoOpen: false,
		modal: true,
      	buttons: 
      	{ }
	});	
}

/* ############################################################################
 * Wiki Related Functions
 * ######################################################################### */
function defaultWikiPane()
{
	updateWikiPane("/atlas/wiki");
}

function getWikiPane(type, key) 
{
	var controller = {
		'events': "timeline",
		'locations': "map"
	}
	
	var url = "/" + controller[type] + "/wiki/" + key.substr(2);
	updateWikiPane(url);
} 

function updateWikiPane(url)
{
	$.get(url,
		function(data) {
			$("#content A").off("click");
			$("#content").html(data);
		
			// Wire up the edit button.
			$("#content A.edit_btn").click(
				function(evt) {
					var url = evt.target.href;
				
					if (url.indexOf('map') == -1)
					{
						ui_event(evt);
					}
					else
					{
						ui_location(evt);
					}
				}
			);
		
			// Wire up the removal button.
			$("#content A.remove_btn").click(
				function(evt) {
					ui_delete(evt);
				});
	});
}