<?php
session_start();
session_unset();
session_destroy();

header('Location: /vite-gourmand/index.php');
exit;