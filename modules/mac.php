<?php 
ob_start(); 
system("ipconfig /all"); 
$cominfo=ob_get_contents(); 
ob_clean();
$primarymac = strpos($cominfo, "Physical"); 
$mac=substr($cominfo,($primarymac+36),17);//MAC Address
?>