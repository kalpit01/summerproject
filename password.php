<?php
$plainPassword="admin123";
$hashPassword= sha1($plainPassword);
echo "Plain password: ".$plainPassword;
echo "Hash password: ".$hashPassword;
?>