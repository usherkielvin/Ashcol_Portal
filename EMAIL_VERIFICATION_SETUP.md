# Email Verification Setup

## Configuration

The email verification system is now configured to use the existing Ashcol Service Desk email sender (`ashcol.servicedesk@gmail.com`).

### Required .env Settings

Make sure your `.env` file has the following email configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ashcol.servicedesk@gmail.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ashcol.servicedesk@gmail.com
MAIL_FROM_NAME="Ashcol Service Desk"
```

### Gmail App Password Setup

Since Gmail requires app-specific passwords for SMTP:

1. Go to your Google Account: https://myaccount.google.com/
2. Navigate to **Security** → **2-Step Verification** (enable if not already enabled)
3. Go to **App passwords**: https://myaccount.google.com/apppasswords
4. Generate a new app password for "Mail"
5. Copy the 16-character password
6. Paste it in your `.env` file as `MAIL_PASSWORD`

**Important:** Do NOT use your regular Gmail password. You must use an app-specific password.

### Testing

After configuring, test the email setup:

1. Register a new user through the Android app
2. Check the email inbox of the registered email address
3. You should receive an email from `ashcol.servicedesk@gmail.com` with:
   - Subject: "Email Verification Code - Ashcol Service Desk"
   - 6-digit verification code
   - Expires in 10 minutes

### Email Template

The verification email includes:
- Ashcol Service Desk branding
- Clear 6-digit code display
- Expiration notice (10 minutes)
- Professional formatting

### Troubleshooting

If emails are not being sent:

1. **Check .env configuration**: Ensure all MAIL_* variables are set correctly
2. **Clear config cache**: Run `php artisan config:clear`
3. **Check logs**: Check `storage/logs/laravel.log` for email errors
4. **Verify Gmail settings**: Ensure app password is correct and 2FA is enabled
5. **Test SMTP connection**: You can test using Laravel Tinker:
   ```php
   php artisan tinker
   Mail::raw('Test email', function($message) {
       $message->to('your-email@example.com')->subject('Test');
   });
   ```

### Development Mode

For local development without actual email sending, you can use the `log` mailer:

```env
MAIL_MAILER=log
```

This will write emails to `storage/logs/laravel.log` instead of sending them.

