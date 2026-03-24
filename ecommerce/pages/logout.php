<?php
session_start();
session_destroy();
header("Location: /ecommerce/pages/login.php?msg=logout");
exit();
?>
