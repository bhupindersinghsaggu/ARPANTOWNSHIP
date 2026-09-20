<?php
// Copy this file to "mail-config.php" and fill in your real values.
// mail-config.php is git-ignored so your credentials never get committed.

return [
    // Gmail address the form will send FROM (the account that logs into SMTP).
    'smtp_username' => 'your-gmail-address@gmail.com',

    // 16-character Gmail "App Password" (NOT your normal Gmail password).
    // Generate it at: https://myaccount.google.com/apppasswords
    // (requires 2-Step Verification to be turned on for the Gmail account)
    'smtp_password' => 'xxxx xxxx xxxx xxxx',

    // Where contact-form submissions should be delivered.
    'mail_to'       => 'arpanntownship@gmail.com',
    'mail_to_name'  => 'Arpann Township',
];
