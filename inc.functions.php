<?php
function redirection($url)
{
 if(headers_sent())
 {
  echo "<meta http-equiv=\"refresh\" content=\"0;URL=$url\">";
 } 
 else
 {
   header("location: $url");
 }
}

function isE164Number($number)
{
 if(strlen($number) < 10 or $number[0] != '+')
  return false;
 
 for($cpt = 1; $cpt < strlen($number); $cpt++)
  if(!is_numeric($number[$cpt]))
   return false;

 return true;
}

function isADate($string)
{
 if(strlen($string) != 10 or $string[4] != '-' or $string[7] != '-' )
  return false;

 if(!is_numeric($string[0]) or !is_numeric($string[1]) or !is_numeric($string[2]) or !is_numeric($string[3]) or !is_numeric($string[5]) or !is_numeric($string[6]) or !is_numeric($string[8]) or !is_numeric($string[9]))
 return false;

 return true;
}

?>
