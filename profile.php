<?php
require_once 'database.php';require_once 'functions.php';session_start();require_login();$me=user();$saved='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();
 $username=trim($_POST['username']??'');$pronouns=trim($_POST['pronouns']??'');$about=trim($_POST['about']??'');$interests=trim($_POST['interests']??'');
 if(strlen($username)<2){$error='Username must be at least 2 characters.';}
 else {
  $photo=$me['profile_photo']??'';
  if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error']!==UPLOAD_ERR_NO_FILE){
   $file=$_FILES['profile_photo'];
   if($file['error']!==UPLOAD_ERR_OK || $file['size']>5*1024*1024){$error='Profile photos must be 5 MB or smaller.';}
   else {
    $info=@getimagesize($file['tmp_name']);$mime=$info['mime']??'';
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    if(!$info || !isset($allowed[$mime])){$error='Please upload a JPG, PNG, WEBP, or GIF image.';}
    else {
     $dir=__DIR__.'/uploads/profiles';if(!is_dir($dir))mkdir($dir,0755,true);
     $name=bin2hex(random_bytes(16)).'.'.$allowed[$mime];$destination=$dir.'/'.$name;
     if(!move_uploaded_file($file['tmp_name'],$destination)){$error='The profile photo could not be saved.';}
     else {$photo='uploads/profiles/'.$name;if($me['profile_photo'] && is_file(__DIR__.'/'.$me['profile_photo']))@unlink(__DIR__.'/'.$me['profile_photo']);}
    }
   }
  }
  if(!$error){$q=$db->prepare("UPDATE users SET username=?,pronouns=?,about=?,interests=?,profile_photo=? WHERE id=?");try{$q->execute([$username,$pronouns,$about,$interests,$photo,$me['id']]);$saved='Profile saved.';$me=null; $me=user();}catch(PDOException $e){$error='That username may already be in use.';}}
 }
}
layout_start('Profile');
echo '<h1>My Profile</h1>'.($saved?'<p class="success">'.$saved.'</p>':'').($error?'<p class="error">'.h($error).'</p>':'');
echo '<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="'.h(csrf()).'">';
if(!empty($me['profile_photo'])) echo '<div class="profile-preview"><img src="'.h($me['profile_photo']).'" alt="Profile photo"></div>';
echo '<label>Profile photo<input type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp,image/gif"><small class="muted">JPG, PNG, WEBP or GIF · max 5 MB</small></label><label>Username<input name="username" value="'.h($me['username']).'" required></label><label>Pronouns<input name="pronouns" value="'.h($me['pronouns']).'"></label><label>About<textarea name="about">'.h($me['about']).'</textarea></label><label>Interests<input name="interests" value="'.h($me['interests']).'"></label><button class="btn">Save Profile</button></form>';layout_end();
