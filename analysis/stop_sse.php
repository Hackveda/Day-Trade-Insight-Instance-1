<?php
session_start();
$sse = $_POST["sse"];
$_SESSION["sse"] = $sse;
echo "Session Set SSE: ".$_SESSION["sse"];
?>