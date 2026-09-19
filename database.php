<?php
$dbDir=__DIR__.'/data';
if(!is_dir($dbDir)) mkdir($dbDir,0775,true);
$db=new PDO('sqlite:'.$dbDir.'/pluralmatch.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec('PRAGMA foreign_keys = ON');
$db->exec("CREATE TABLE IF NOT EXISTS users (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 username TEXT UNIQUE NOT NULL,
 email TEXT UNIQUE NOT NULL,
 password TEXT NOT NULL,
 dob TEXT NOT NULL,
 pronouns TEXT DEFAULT '',
 about TEXT DEFAULT '',
 interests TEXT DEFAULT '',
 profile_photo TEXT DEFAULT '',
 created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");
try { $db->exec("ALTER TABLE users ADD COLUMN profile_photo TEXT DEFAULT ''"); } catch (PDOException $e) { /* column already exists */ }
try { $db->exec("ALTER TABLE users ADD COLUMN is_admin INTEGER DEFAULT 0"); } catch (PDOException $e) { /* column already exists */ }
$db->exec("CREATE TABLE IF NOT EXISTS likes (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 user_id INTEGER NOT NULL,
 liked_user_id INTEGER NOT NULL,
 UNIQUE(user_id,liked_user_id),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(liked_user_id) REFERENCES users(id) ON DELETE CASCADE
)");
$db->exec("CREATE TABLE IF NOT EXISTS messages (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 sender_id INTEGER NOT NULL,
 receiver_id INTEGER NOT NULL,
 body TEXT NOT NULL,
 created_at TEXT DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(sender_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(receiver_id) REFERENCES users(id) ON DELETE CASCADE
)");
$db->exec("CREATE TABLE IF NOT EXISTS blocks (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 user_id INTEGER NOT NULL,
 blocked_id INTEGER NOT NULL,
 UNIQUE(user_id,blocked_id),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(blocked_id) REFERENCES users(id) ON DELETE CASCADE
)");

$db->exec("CREATE TABLE IF NOT EXISTS reports (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 reporter_id INTEGER NOT NULL,
 reported_id INTEGER NOT NULL,
 reason TEXT NOT NULL,
 details TEXT DEFAULT '',
 status TEXT DEFAULT 'open',
 created_at TEXT DEFAULT CURRENT_TIMESTAMP,
 resolved_at TEXT DEFAULT '',
 FOREIGN KEY(reporter_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(reported_id) REFERENCES users(id) ON DELETE CASCADE
)");
