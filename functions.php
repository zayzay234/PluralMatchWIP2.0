<?php
require_once __DIR__.'/config.php';
function h($s){return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8');}
function logged_in(){return isset($_SESSION['user_id']);}
function require_login(){if(!logged_in()){header('Location: login.php');exit;}}
function user(){global $db; static $u=null;if($u===null&&logged_in()){$q=$db->prepare("SELECT * FROM users WHERE id=?");$q->execute([$_SESSION['user_id']]);$u=$q->fetch(PDO::FETCH_ASSOC);}return $u;}
function redirect($url){header("Location: $url");exit;}
function age18($dob){try{$d=new DateTime($dob);$now=new DateTime();return $d->diff($now)->y>=18;}catch(Exception $e){return false;}}
function csrf(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function check_csrf(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(403);exit('Invalid request.');}}
function blocked_between($a,$b){global $db;$q=$db->prepare("SELECT COUNT(*) FROM blocks WHERE (user_id=? AND blocked_id=?) OR (user_id=? AND blocked_id=?)");$q->execute([$a,$b,$b,$a]);return (int)$q->fetchColumn()>0;}
function mutual($a,$b){global $db;$q=$db->prepare("SELECT COUNT(*) FROM likes WHERE user_id=? AND liked_user_id=?");$q->execute([$a,$b]);$x=$q->fetchColumn();$q->execute([$b,$a]);return $x && $q->fetchColumn();}
function profile_photo_url($path){return $path ? h($path) : '';}
function is_admin(){global $db; $u=user(); if(!$u)return false; if((int)($u['is_admin']??0)===1)return true; if(ADMIN_EMAIL!=='' && strcasecmp(trim($u['email']),trim(ADMIN_EMAIL))===0){$q=$db->prepare("UPDATE users SET is_admin=1 WHERE id=?");$q->execute([$u['id']]);return true;} return false;}
function require_admin(){require_login();if(!is_admin()){http_response_code(403);exit('Admin access required.');}}
function layout_start($title){echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.h($title).'</title><link rel="stylesheet" href="style.css"></head><body><header><a class="brand" href="discover.php">✦ PluralMatch</a><nav><a href="discover.php">Discover</a><a href="matches.php">Matches</a><a href="messages.php">Messages</a><a href="profile.php">Profile</a>'.(is_admin()?'<a href="admin.php">Admin</a>':'').'<a href="logout.php">Log out</a></nav></header><main>';}
function layout_end(){echo '</main></body></html>';}
