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

		var eventSource = new Timeline.DefaultEventSource(0);
        
        var theme = Timeline.ClassicTheme.create();
        theme.event.bubble.width = 350;
        theme.event.bubble.height = 300;
        var d = Timeline.DateTime.parseGregorianDateTime("1090");
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
        // stop browser caching of data during testing...
        tl.loadJSON("/timeline/view?"+ (new Date().getTime()), function(json, url) {
            eventSource.loadJSON(json, url);
        });

        Timeline.OriginalEventPainter.prototype._showBubble = function(x, y, evt) 
        {
        	res = evt.getClassName().split(" ");
        	updateInfoPane(res[0], res[1]);
        }

		/*
		 * Wire the Add Location and Event Items.
		 */
		 $("#addLoc").click(function() {
		 	
		 	var name = $( "#loc_name" ),
				lat = $( "#lat" ),
				lng = $( "#lng" ),
				loc_descript = $("#loc_descript"),
				csrf = $("input[name=csrf_test_name]")
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

// Core JSON Data Retrieval function
function getData() 
{
	return $('body').data('data');
}

function refreshMapData()
{
	$.getJSON("/map/view?"+ (new Date().getTime()), function(the_data) 
	{
		$('#mapViewport').gmap3({
			action: 'clear'
		});
	
		$('body').data('data', the_data);
		prepMarkers();
	});
}

// 
function prepMarkers(places) 
{
	var details = {};

	// Iterate each data element, preparing map info.	
	$.each(getData().locations, function(key, val) 
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
	$("#content_title").html(getData()[type][key].name);
	$("#content_picture").attr("src", getData()[type][key].image_url);

	$("#content_details A").off("click");
	
	var record = getData()[type][key];
	
	if (type == 'personalities')
	{
		// Deal with Birth and Death Locations
		birth_loc = "";
		death_loc = "";
		
		if (record.birth_loc != "")
		{
			var loc = getData()['locations'][record.birth_loc];

			if (loc != undefined)
			{			
				birth_loc = ' at <a href="#" class="locations ' + record.birth_loc + '">'+loc.name+'</a>';
			}
		}
		
		if (record.death_loc != "")
		{
			var loc = getData()['locations'][record.death_loc];
			
			if (loc != undefined)
			{
				death_loc = ' at <a href="#" location="' + record.death_loc + '" class="locations">'+loc.name+'</a>';				
			}
		}

		// Participation details
		var participation_details = "";

		if (record.participant.length > 0)
		{
			participation_details += "<p><strong>Participant of:</strong></p>";
			
			for(i = 0; i < record.participant.length; i++)
			{
				current_event = getData()['events'][record.participant[i]];
				if (current_event != undefined)
				{
					participation_details += 
						'<a href="#" class="events" event="' + 
						record.participant[i] + 
						'">' + current_event.name + '</a>, ';
				}
			}		
		}
		
		// Finally output results
		var display_text = 
			"<p><strong>Born: " + record.birth_ts + birth_loc + 
			"<br /> Died: " + record.death_ts + death_loc + "</strong></p>" +  
			"<p>" + record.description + "<p>" + 
			participation_details;		
		
		$("#content_details").html(display_text);

		$("#content_details A.events").click(
				function() {
					updateInfoPane('events', $(this).attr("event"));
			});
		
		$("#content_details A.locations").click(
			function() {
				updateInfoPane('locations', $(this).attr("location"));
		});
	}
	else if (type == 'events')
	{
		var display_text = 
			"<p><strong>" + record.ts + "</strong></p>" +  
			"<p>" + record.description + "<p>";
				
		if (record.participants.length > 0)
		{
			display_text += "<p><strong>Participants:</strong></p>";
			
			for(i = 0; i < record.participants.length; i++)
			{
				current_person = getData()['personalities'][record.participants[i]];
				if (current_person != undefined)
				{
					display_text += '<a href="#" class="' + record.participants[i] + '">' + current_person.name + '</a>, ';
				}
			}
		}	

		$("#content_details").html(display_text);

		$("#content_details A").click(
				function() {
					updateInfoPane('personalities', $(this).attr("class"));
		});	
	}
	else if (type == 'locations')
	{
		$("#content_details").html(record.description);		
	}
}