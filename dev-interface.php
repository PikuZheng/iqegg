<?php
$statusfile = fopen("xiaodan.txt", "w");
fwrite($statusfile,json_encode($_GET));
fclose($statusfile); 

echo '{"result":"1","message":"ok","content":""}';

?>
