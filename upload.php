
<?php
unset($_COOKIE['chunk']);
unset($_COOKIE['chunks_lenght']);

setcookie('chunk', $_REQUEST['chunk'], time() + (86400 * 30), "/"); // 86400 = 1 day
setcookie('chunks_lenght', $_REQUEST['chunks_lenght'], time() + (86400 * 30), "/"); // 86400 = 1 day

echo $_REQUEST['chunk'];