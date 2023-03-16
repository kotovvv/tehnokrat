jQuery(document).ready(function($){
    // var lat = $('.cont_l').attr('data-lat');
    // var lng = $('.cont_l').attr('data-lng');
    var lat = 50.446143;
    var lng = 30.443096;

    google.maps.event.addDomListener(window, 'load', init);

    function init() {
        var mapOptions = {
            zoom: 14,
            draggable: true,
            scrollwheel: false,
            center: new google.maps.LatLng(lat, lng),
            styles: [	{"featureType":"landscape",		"stylers":[
                {				"hue":"#FFA800"			},			{				"saturation":0			},
                {				"lightness":0			},			{				"gamma":1			}		]	},
                {		"featureType":"road.highway",		"stylers":[			{				"hue":"#53FF00"			},			{				"saturation":-73			},			{				"lightness":40			},			{				"gamma":1			}		]	},	{		"featureType":"road.arterial",		"stylers":[			{				"hue":"#FBFF00"			},			{				"saturation":0			},			{				"lightness":0			},			{				"gamma":1			}		]	},	{		"featureType":"road.local",		"stylers":[			{				"hue":"#00FFFD"			},			{				"saturation":0			},			{				"lightness":30			},			{				"gamma":1			}		]	},	{		"featureType":"water",		"stylers":[			{				"hue":"#00BFFF"			},			{				"saturation":6			},			{				"lightness":8			},			{				"gamma":1			}		]	},	{		"featureType":"poi",		"stylers":[			{				"hue":"#679714"			},			{				"saturation":33.4			},			{				"lightness":-25.4			},			{				"gamma":1			}		]	}]
        };
        var mapElement = document.getElementById('map2');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map,
            title: 'Snazzy!',
            icon: tehnokrat_script.mapIcon
        });
    }
});
