<?php
include("php_serial.class.php");

$serial = new phpSerial();


$serial->deviceSet("/dev/ttyUSB0");
$serial->confBaudRate(9600);
$serial->deviceOpen();

$serial->sendMessage("OHMS\n");
$serial->sendMessage("RATE M\n");
while (1) {
	$serial->sendMessage("VAL1?\n");
	sleep(1);
	$value = $serial->readPort();
//	echo $value;

	$parsedValue = floatval($value);
//	echo $parsedValue."\n";
	echo number_format(pt1000toC($parsedValue,1000), 2)." °C                 \r";

}

function pt1000toC2 ($r) {
	$R0 = 1000;
	$A = 3.9083E-3;
	$B = -5.775E-7;

	$Rt = $R0 * (1 + $A* $t + $B*$t2 + $C*($t-100)* $t3);
	
	return $Rt;
}
function pt1000toC ($r, $r0) {
    $a=3.9083E-3; 
    $b=-5.775E-7; 
    
    $r = $r / $r0; 
    
    $t=0.0-$a; 
    $t+=sqrt(($a*$a) - 4.0 * $b * (1.0 - $r)); 
    $t/=(2.0 * $b); 
    
    if($t>0) { 
      return $t; 
    } 
    else { 
      //T=  (0.0-A - sqrt((A*A) - 4.0 * B * (1.0 - R))) / 2.0 * B; 
      $t=0.0-$a; 
      $t-=sqrt(($a*$a) - 4.0 * $b * (1.0 - $r)); 
      $t/=(2.0 * $b); 
      return $t; 
    } 
}
$serial->deviceClose();

