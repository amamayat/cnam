<?php
function redirectLocation($fullURL)
{
  // header("Location: $fullURL"); // Old code but does not always work
echo <<<EOT
<HTML> 
<META HTTP-EQUIV="refresh" CONTENT="0; URL=$fullURL"> 
</HTML>  
EOT;

}

function appendURLArg($URL, $args)
{
  $requestURI = '';
  $currentURIargs = '';
  $pos = strpos($URL, '?');
  if ( $pos === false )
    {
      $requestURI = $URL;
      $currentURIargs = $args;
    }
  else
    {
      $requestURI = substr($URL, 0, $pos);
      $currentURIargs = substr($URL, $pos+1);
      $currentURIargs = $currentURIargs . "&$args";
    }
  $URIargs = removeDuplicateURLArgs($currentURIargs);
  $fullURL = $requestURI . '?' . $URIargs;
  return $fullURL;
}

/**
 * remove duplicate arguments
 * @Example 
 *   $res = removeDuplicateURLArgs('x=1&y=5&z=3&y=6&z=4');
 *   (should return 'x=1&y=6&z=4')
 */
function removeDuplicateURLArgs($argsStr)
{
  $argsArray = explode('&', $argsStr);
  foreach ( $argsArray as $arg )
    {
      // store each value in a map
      $pos = strpos($arg, '=');
      if ( $pos === false )
	continue;
      $argName = substr($arg, 0, $pos);
      $argValue = substr($arg, $pos+1);
      $map[$argName] = $argValue;
    }
  // walk the map to build the result
  $result = '';
  foreach($map as $k => $v) 
    {
      if ( $result != '' )
	$result .= '&';
      // print "\$map[$k] => $v.\n";
      $result .= "$k=$v";
    }

  return $result;
}

function getCurrentScriptFullPath()
{
  $requestURI = $_SERVER['REQUEST_URI'];
  $pos = strpos($requestURI, '?');
  if ( $pos === false ) 
    return $requestURI;
  else
    return substr($requestURI, 0, $pos);
}

/**
 * returns the value of an HTTP parameter
 * @Example 
 *   $content = getHttpParameter('content');
 */
function getHttpParameter($paramName, $defaultValue = '')
{
  $result = $defaultValue;
  if ( isset($_REQUEST[$paramName]) )
    $result = $_REQUEST[$paramName];
  return $result;
}

/**
 * 
 *   getAllRequestParamsAsHiddenParams()
 */
function getAllRequestParamsAsHiddenParams($excludeNames = array())
{
	$res = '';
	$_GET_OR_POST = $_GET + $_POST;
	foreach ( $_GET_OR_POST as $key => $value )
		{
			if ( $key == 'PHPSESSID') continue;
			$str = array_search($key, $excludeNames);
			if ( $str === false )
			{
				$str = <<<EOT
 <INPUT name="$key" type="hidden"  value="$value"> 
EOT;
				$res .= $str;
			}		
		}
	return $res;
}

function appendPostParamsToURL($URL, $excludeNames = array())
{
	foreach ( $_POST as $key => $value )
	{
		$str = array_search($key, $excludeNames);
		if ( $str === false )
			$URL = appendURLArg($URL, "$key=$value");
	}
	return $URL;
}

?>
