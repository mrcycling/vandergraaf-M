<?php

/*
= = = = = = = = = = = = = = config section = = = = = = = = = = = = = =

1) update server file path for where the static html files will be stored
2) update url to modx install for calling the pgaes to be stored

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
*/

// set path to html storage, can be changed for testing 
$basePath = '/FULL_SERVER_PATH/public_html/';

// set url to your modx install
$mdxurl = 'http://URL-TO-MODX-INSTALL/';

/* end config section */

    


// check if new or update
if ($mode == 'upd') {
    // run single page code
    
    // Get the page id
    $rid = $resource->get('id');
    $modxUri = $resource->get('uri');
    
    $modx->reloadContext('web');
    
    // resource created
    // determine if folders exist and create if not
    $path_parts = pathinfo($basePath . $modxUri);
    $target_path = $path_parts['dirname'];
      
    if (!file_exists($target_path)) {
        mkdir($target_path, 0755, true);
    }
      
    // get the webpage from MODX
    $contents = file_get_contents($modxInstl . 'index.php?id=' . $rid);
     
    // remove comments
    $contents = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/Uis', '', $contents);
      
    // minify
    $contents = preg_replace('/^\s+|\n|\r|\s+$/m', '', $contents);
    
    // save new copy
    file_put_contents($basePath . $modxUri, $contents);

}
else {
    // rebuild full website

    $modx->reloadContext('web');
        
    //getting the published ids
    // get collection of resources, determine id and if published
    $docs = $modx->getCollection('modResource');
    foreach ($docs as $doc) {
    $pub = $doc->get('published');
    $folder = $doc->get('isfolder');
    $rid = $doc->get('id');
    $modxUri = $doc->get('uri');
    
    // if published, fetch url and build static webpage
    if (($pub == '1') && ($folder == '0')) {
      
        // determine if folders exist and create if not
        $path_parts = pathinfo($basePath . $modxUri);
        $target_path = $path_parts['dirname'];
          
        if (!file_exists($target_path)) {
            mkdir($target_path, 0755, true);
        }
          
        // get the webpage from MODX
        $contents = file_get_contents($modxInstl . 'index.php?id=' . $rid);
         
        // remove comments
        $contents = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/Uis', '', $contents);
          
        // minify
        $contents = preg_replace('/^\s+|\n|\r|\s+$/m', '', $contents);
        
        // save new copy
        file_put_contents($basePath . $modxUri, $contents);
          
        }
    }
  
}
