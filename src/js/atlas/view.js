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
							getWikiPane('location', data.hr_idx);
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

// Called for editing concepts
function ui_concept(evt)
{
	var	con_name = $("#concept_name");
	var	con_descript = $("#concept_descript");
	var	csrf = $("input[name=csrf_test_name]");
	var con_fields = $([]).add(con_name).add(con_descript);

	var tgt_url = evt.target.href;
	var url_frags = tgt_url.split('/');
	var record_id = url_frags.pop();
	var action = url_frags.pop();
	var type = url_frags.pop();	
	
	if (action == 'edit')
	{
		var name_val = $("#concept_name_val").text();
		var dat_val = $("#concept_descript_val").html();
	
		con_name.val(name_val);
		con_descript.val(dat_val);
	}

	var btns = {
		"Confirm": function() {
			evt.preventDefault();	

			var datum = {  
				"title": con_name.val(),
				"content": con_descript.val(),
				"csrf_test_name": csrf.val()
			};

			// Then send it to the system.
			$.post(
				tgt_url, 
				datum, 
				function(data) 
				{
					getWikiPane(type, 'id'+record_id);
				}
			);

			con_fields.val("").removeClass("ui-state-error");
			$(this).dialog("close");			
	 	},
		"Cancel": function() {
			$(this).dialog("close");
		}
	};

	$("#concept-form").dialog({ "buttons": btns });
	$("#concept-form").dialog("open");	
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

					if (tgt_url.indexOf('location') != -1)
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

// Called for Event addition or editing behaviors.
function ui_event(evt)
{
	// Prevent the baseline behavior.
	evt.preventDefault();
	
	var tgt_url = evt.target.href;
	var url_frags = tgt_url.split('/');
	var record_id = url_frags.pop();
	url_frags.pop();
	var type = url_frags.pop();	
	
	
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
		evt_occurance.val($('#evt_occurance_val').val());
		evt_range.val($('#evt_range_val').val());
		evt_duration.val($('#evt_duration_val').val());
		evt_descript.val($('#evt_descript_val').html());

		// Wipe the select boxes.
		var comparator = $('#evt_units_val').val();
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
						getWikiPane('event', 'id'+record_id);
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
	var url_frags = tgt_url.split('/');
	var record_id = url_frags.pop();
	url_frags.pop();
	var type = url_frags.pop();	

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
					getWikiPane('location', 'id'+record_id);
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

// Called for editing personality
function ui_personality(evt)
{
	var tgt_url = evt.target.href;

	var url_frags = evt.target.href.split('/');
	
	var record_id = url_frags.pop();
	url_frags.pop();
	var type = url_frags.pop();
	
	// Load the possible locations for the dropdowns.
	$.getJSON(
		"/person/related_locations",
		function(data)
		{
			$('#person_birth_loc').html('');
			$('#person_death_loc').html('');
		
			// Wire up the tabs.
			$.each(data, function(id, fields) {
				$('#person_birth_loc')
			   		.append($('<option>', { value : fields.IdLocation })
					.text(fields.Title));

			     $('#person_death_loc')
			   		.append($('<option>', { value : fields.IdLocation })
					.text(fields.Title));
			});

			// Wire up other UI components as necessary.
			if (tgt_url.indexOf('add') == -1)
			{
				var blv = $('#person_birth_loc_val').val();
				var dlv = $('#person_death_loc_val').val();
			
				$('#person_name').val($('#person_name_val').text());
				$('#person_descript').val($('#person_descript_val').html());
		
				$('#person_birth').val($('#person_birth_val').val());
				$('#person_death').val($('#person_death_val').val());
				
				$('#person_birth_loc option:eq(' + blv + ')').attr('selected', 'selected');
				$('#person_death_loc option:eq(' + dlv + ')').attr('selected', 'selected');
			}			
		}
	);	
	
	// Wire up the buttons.
	var btns = {
		"Update": function() {
			// Setup the request.
			var csrf = $("input[name=csrf_test_name]");
		
			var datum = { 
				'lob' : $("#person_birth_loc option:selected").val(),
				'dob' : $("#person_birth").val(),
				'lod' : $("#person_death_loc option:selected").val(),
				'dod' : $("#person_death").val(),
				'name': $("#person_name").val(),
				'biography': $("#person_descript").val(),
				'csrf_test_name': csrf.val()
			 };
			
			// Then make it.
			$.post(
				tgt_url,
				datum,
				function(data) {
					$("#person_birth_loc").val('');
					$("#person_birth").val('');
					$("#person_death_loc").val('');
					$("#person_death").val('');
					$("#person_name").val('');
					$("#person_descript").val('');
				
					getWikiPane(type, 'id'+record_id);
				}
			)
		
			$(this).dialog("close");			
	 	},
		"Cancel": function() {
			$(this).dialog("close");
		}
	};

	$("#person-form").dialog({ "buttons": btns });	
	$("#person-form").dialog("open");	
}

// Called for relation behaviors.
function ui_relate(evt)
{
	var tgt_url = evt.target.href;

	var url_frags = evt.target.href.split('/');
	
	var record_id = url_frags.pop();
	url_frags.pop();
	var type = url_frags.pop();
	
	var btns = {
		"Update": function() {
			// Setup the request.
			var csrf = $("input[name=csrf_test_name]");
		
			var datum = { 
				'related[]' : [],
				'related_updated': $("#related_updated").val(),
				'csrf_test_name': csrf.val()
			 };

			$("#relate-form :checked").each(
				function() {
			  		datum['related[]'].push($(this).val());
			});
			
			// Then make it.
			$.post(
				tgt_url,
				datum,
				function(data) {
					getWikiPane(type, 'id'+record_id);
				}
			)

			$(this).dialog("close");			
	 	},
		"Cancel": function() {
			$(this).dialog("close");
		}
	};

	$.get(tgt_url, 
		function(data) {
			$("#relation-div").html(data);
			$("#relate-form").dialog({ "buttons": btns });
			$("#relate-form").dialog("open");
		});				
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
	 * Concept Form
	 */
 	$("#concept-form").dialog({
	 	autoOpen: false,
	 	modal: true,
		height: 450,
		width: 400,
		buttons: { }
 	});

	/*
	 * Event Form
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
		buttons: { }    
	});

	/*
	 * Personality Form
	 */
 	$("#person-form").dialog({
	 	autoOpen: false,
	 	modal: true,
		height: 500,
		width: 750,
		buttons: { }
 	});

	$("#person-form #person_birth").datepicker();
	$("#person-form #person_death").datepicker();

	/*
	 * And a generalized deletion notice.
	 */
	$("#deletion_notice").dialog({
		autoOpen: false,
		modal: true,
      	buttons: { }
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
	var url = "/" + type + "/wiki/" + key.substr(2);
	updateWikiPane(url);
} 

function updateWikiPane(url)
{
	$.get(url,
		function(data) {
			$("#contentViewport A").off("click");
			$("#contentViewport").html(data);

			updateWikiTab('entry');
			
			// Wire up the relation buttons.
			$("#contentViewport A.relate_btn").click(
				function(evt) {
					evt.preventDefault();
					ui_relate(evt);
				}
			);
			
			// Wire up the edit buttons.
			$("#contentViewport A.edit_btn").click(
				function(evt) {
					evt.preventDefault();
				
					var url = evt.target.href;
					var url_frags = url.split('/');
					var action = url_frags[url_frags.length-2];
					var controller = url_frags[url_frags.length-3];
	
					/*
					 * What window needs to appear?
					 */	
					if (controller.indexOf('person') > -1)
					{
						ui_personality(evt);
					} else if (action.indexOf('add_personality') > -1)
					{
						ui_personality(evt);
					}
					else if (controller.indexOf('concept') > -1 && action.indexOf('edit') > -1) 
					{
						ui_concept(evt);
					}
					else if (action.indexOf('add_concept') > -1)
					{
						ui_concept(evt);
					}					
					else if (controller.indexOf('location') > -1)
					{
						ui_location(evt);
					}
					else if (controller.indexOf('event') > -1)
					{
						ui_event(evt);
					}
				}
			);
			
			// Wire up the redirection buttons.
			$("#contentViewport A.wiki_btn").click(
				function(evt) {
					evt.preventDefault();
					updateWikiPane(evt.target.href);
				}
			);
		
			// Wire up the removal button.
			$("#contentViewport A.remove_btn").click(
				function(evt) {
					ui_delete(evt);
				});
				
			// Wire up the tabbing buttons.
			$("#wiki_tab_tray A").click(
				function(evt) {
					evt.preventDefault();
					var url_frags = evt.target.href.split('/');
					updateWikiTab(url_frags[url_frags.length-2]);
				}
			);
	});
}

function updateWikiTab(load_pane)
{
	$("#contentViewport .wiki_pane").hide();
	$("#contentViewport #wiki_" + load_pane).show();
}