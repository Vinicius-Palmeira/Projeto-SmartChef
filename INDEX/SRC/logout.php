<?php
foreach ($_COOKIE as $cookie_name => $cookie_value) {
    setcookie($cookie_name, '', time() - 1);
}
die("<script> location.href = '../auth.html' </script>");
