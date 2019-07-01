# olcs-cpms

## Synopsis
This repository contains the cpms client which is used by the VOL backend api (olcs-backend repository). Guzzle is used for the HTTP requests and Monolog for the logging.


## Setup
Create a new instance of olcs-cpms/src/Service/ApiServiceFactory.php and pass in the following two arguments: 
	1. config - An array of config (see below).
	2. userId - The user Id as a string.

## Config
Required config:
```
[
	'cpms_api' => [  
	   'rest_client' => [  
		  'options' => [  
			  //CPMS API version to use  
			  'version' => 2,  
			  // CPMS hostname *Environment specific* 
			  'domain' => 'cpms.example-domain.uk', 
			  'grant_type' => 'client_credentials',  
			  'timeout' => 15.0,  
			  'headers' => [  
				  'Accept' => 'application/json',  
			 ],
		]
	 ]
	 'cpms_credentials' => [   													
	      // CPMS client ID *Environment specific*  
		  'client_id' => 'CD01',  
		  // CPMS Client secret *Environment specific*  
		  'client_secret' => 'secret',  
		  // CPMS NI client ID *Environment specific*  
		  'client_id_ni' => 'AB01',  
		  // CPMS NI Client secret *Environment specific*  
		  'client_secret_ni' => 'secret',  
	]
]
 ```