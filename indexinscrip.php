<?php
session_start();
echo "Welcome mother fucker, " . ($_SESSION['utilisateur']['nom'] ?? "invité");
?>
