<?PHP

// include configuration
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."include/config.php");

// Check for minimum required PHP extensions
if (!extension_loaded('gd')) {
  header('HTTP/1.1 500 Internal Server Error');
  $missing_so = array(gd);
}

if (!extension_loaded('curl')) {
  header('HTTP/1.1 500 Internal Server Error');
  $missing_so[] = curl;
}

// DEBUG with some unusual extension
/*if (!extension_loaded('testext')) {
  header('HTTP/1.1 500 Internal Server Error');
  $missing_so[] = testext;
}*/


// check for optional ffmpeg extension
if (!extension_loaded('ffmpeg')) {
  $missing_so_opt = array(ffmpeg);
}

// check if .cache dir is writable for php
if (!mkdir("./.cache/testwrite", 0755, true)) {
  header('HTTP/1.1 500 Internal Server Error');
  $cache_dir_rw = false;
} 
else {
  rmdir("./.cache/testwrite");
}

// function definition for hstbe checking
function isHstbeAvailible($domain)
  {
  //initialize curl
  $curlInit = curl_init($domain);
  curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
  curl_setopt($curlInit,CURLOPT_HEADER,true);
  curl_setopt($curlInit,CURLOPT_NOBODY,true);
  curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
  
  //get answer
  $response = curl_exec($curlInit);

  curl_close($curlInit);

  if ($response) return true;

  return false;
}

// check if Sites Hosting Backend is accessible
if (isset($config["hosting-backend-server"]) and extension_loaded('curl')) {
  if (isHstbeAvailible($config["hosting-backend-server"]))
    {
      $ret_hstbe_stat = "INFO: Hosting Backend Server: Up and running!\n";
    }
  else
    {
      header('HTTP/1.1 500 Internal Server Error');
      $ret_hstbe_stat = "ERROR: Woops, nothing found there. Check Hosting Backend Server domain/access\n";
    }
}

// CIDR check function
function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = split ("/", $CIDR);
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    $ip_ip_net = $ip_ip & $ip_mask;

    return ($ip_ip_net == $ip_net);
}

// get and check remote IP
$remote_ip=$_SERVER['REMOTE_ADDR'];

if ( ipCIDRCheck ("$remote_ip", "91.199.241.0/24") ) {
  $verbose=true;
}
else if ( ipCIDRCheck ("$remote_ip", "172.30.0.0/16") ) {
  $verbose=true;
}


// print stuff if in verbose mode
if (isset($verbose)) {
  // print Hosting Backend Server
  print "INFO: Hosting Backend Server: " . $config["hosting-backend-server"] . "\n";

  // print Hosting Backend Server status
  print $ret_hstbe_stat;

  // Print PHP version
  print "INFO: PHP version: " . phpversion() . "\n";

  // Print System OS
  print "INFO: System: " . $_SERVER['SERVER_SOFTWARE'] . "\n";

  // not writable cache dir
  if (isset($cache_dir_rw)) {
   print "ERROR: .cm4all/.cache not writable \n";
  }

  // Print missing extension(s), if any
  if (isset($missing_so)) {
    echo "ERROR: Missing PHP extension(s): ";
    foreach ($missing_so as $value) {
      echo $value." ";
    }
    echo "\n";
  }

  // print missing optional extension(s), if any
  if (isset($missing_so_opt)) {
    echo "INFO: Missing optional PHP extension(s): ";
    foreach ($missing_so_opt as $value) {
      echo $value." ";
    }
    echo "\n";
  }

  // Print all loaded extensions
  echo "INFO: Loaded PHP extension(s): ";
    foreach(get_loaded_extensions() as $value) {
      echo $value." ";
    }
  echo "\n";
}


// EOF
?>
