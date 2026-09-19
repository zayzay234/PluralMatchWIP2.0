<?php
require_once 'database.php';
require_once 'functions.php';
session_start();
if (isset($_SESSION['user_id'])) { header('Location: discover.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>PluralMatch</title><link rel="stylesheet" href="style.css"></head>
<body><div class="center"><div class="box"><div class="sigil">✦</div><h1>PluralMatch</h1><p>A gothic dating space for plural people.</p><div class="notice">18+ only. This is a local PHP + SQLite starter.</div><div class="buttons"><a class="btn" href="register.php">Create Account</a><a class="btn ghost" href="login.php">Log In</a></div></div></div></body></html>