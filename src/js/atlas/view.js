/* Author: Jay Ripley
*/
$(document).ready(
	function() 
	{
		/*
		 * Wire up and Reference the main map object.
		 */ 
		gmap_conf = { 
			'mapTypeControl': false, 
			'panControl': false, 
			'zoomControl': false, 
			'streetViewControl': false, 
			'mapTypeId': google.maps.MapTypeId.SATELLITE,
		}

		// Setup Map and References
		$('#mapViewport').gmap3(gmap_conf);
		
		/*
		 * Load the initial data & Store it in the Body Object. 
		 * Access data store via get_data().
		 */
		$.getJSON("/map/view?"+ (new Date().getTime()), function(the_data) 
		{
			$('body').data('data', the_data);
			prepMarkers();
			displayDefault();
		});

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
		 * End Document.Ready Function here.
		 */
	}
);

// Core JSON Data Retrieval function
function getData() 
{
	return $('body').data('data');
}

// Returns crappy reference to GMAP Object.
function getMap(args) 
{
	return $('body').data('map');
}

// 
function prepMarkers(places) 
{
	var m = getMap();
	var details = {};
	var max_lng, min_lng, max_lat, min_lat;

	// Iterate each data element, preparing map info.	
	$.each(getData().locations, function(key, val) 
	{
		var imgPath = (val.city) ? "/assets/img/city-pin.png" : "/assets/img/battle-pin.png";
		
		$('#mapViewport').gmap3({
			action: 'addMarker',
			lat: val.lat,
			lng: val.lng,
			marker: {
				options: {
					icon: {
						url: imgPath
					},
					title: val.name
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