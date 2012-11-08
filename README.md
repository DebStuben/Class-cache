# ClassCache4Php #

This class allows to put in cache file a part of code or php file include.  
This class provides methods to use simple cache for small project.



## Usage

Import the class in your project, like this:
	
	require('/class/class.cache.php');

### Instantiate the class ###

When you instantiante the class you must provide two parameters:
1. Directory where you want save the caches files (It's better to provide absolute path)
2. Number of minute of cache

	// Cache in /tmp directory and cache duration of 10 minutes
	$Cache = new Cache(ROOT.'/tmp',10);

### Use start() and end() methods ###

This method is mainly use when you need a cache for a bloc of code.  

You must put the method start() in 'if' condition, 
the 'if' condition is executed only if it is not present in the or the cache expired  
  
The method start() ask one parameter. This parameter is the name of the cache.

	<?php
		// Start the cache 
	    if(!$Cache->start('demoCache')){
	      
	    	// long threatment
	    	$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.49.wsdl");
			$session = $soap->login('********', '**********',"fr", false);	

			$result = $soap->domainList($session);
			foreach ($result as $key ) {
				echo '<li>'.$key.'</li>';
			}
	    }
	    // Stop the cache
	    $Cache->end();
	?>

### Use start() and end() methods when you need use serialization ###

If you need to use serialization in your cache.  
The method start() et end() allows to use a parameters to change the type of return.  
If you set the type to true the method return the content and do not print.

For the end() method you must set the name of cache so.
  

	<?php
		// Start the cache 
	    if(! $return = $Cache->start('demoCache', true)){
	      
	    	// long threatment
	    	$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.49.wsdl");
			$session = $soap->login('********', '**********',"fr", false);	

			$result = $soap->domainList($session);
			echo serialize($result);
	    }

	    // Stop the cache
	    $return = $Cache->end('demoCache', true);

	    print_r(unserialize($return));
	?>

### Use method inc() ###

This method is used for include a php file containing treatments  
This method replace 'include' function.

Parameters :
1. path file to include (It's better to provide absolute path)
2. (optionnal) Name of cache file

	// Standard inclusion
	include('./twitter.php');

	// Inclusion with cache
	$Cache->inc(ROOT.'/twitter.php', 'lastTweet');

## Other methods ##

### delete() ###

This method allows to delete a specific cache file.  
It's typically use when you update a part of your site

### clean() ###

This method delete all of cache file 


