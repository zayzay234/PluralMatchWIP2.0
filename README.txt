PluralMatch — PHP + SQLite + Gothic Theme

No npm, Node.js, MySQL, or XAMPP required.

Run from the project folder:
  .\php\php.exe -S localhost:8000
Then open http://localhost:8000

Profile photos:
- JPG, PNG, WEBP, GIF
- Maximum 5 MB
- Saved in uploads/profiles/

Blocking and reporting:
- Block buttons are available on Discover, Matches, and Messages.
- Reports are saved in the SQLite database for moderation.

Admin access:
1. Open config.php.
2. Change ADMIN_EMAIL to the exact email address of the account you want to be an admin.
3. Log in with that account.
4. The Admin link will appear in the navigation and open admin.php.

Important: keep config.php private if you deploy this publicly. This starter is intended for local/development use and should receive additional production security work before public launch (email verification, rate limiting, stronger moderation controls, secure deployment, backups, etc.).
