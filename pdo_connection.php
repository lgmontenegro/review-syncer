<?php
try {
  $db = new PDO('mysql:host=127.0.0.1;dbname=mysql', 'root', 'password');
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}
