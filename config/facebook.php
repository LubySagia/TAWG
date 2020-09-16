<?php

return [
    'config' => [
		'facebook_app_id' => env('FACEBOOK_APP_ID', null),
    	'facebook_app_secret' => env('FACEBOOK_APP_SECRET', null),
   		'facebook_default_graph_version' => env('FACEBOOK_DEFAULT_GRAPH_VERSION', 'v2.8')
   	]
];