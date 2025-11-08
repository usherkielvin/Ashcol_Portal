<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification - Ashcol Service Desk</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f7fafc; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #1a202c; margin: 0; font-size: 24px;">Ashcol Service Desk</h2>
            <p style="color: #718096; margin: 5px 0 0 0; font-size: 14px;">Email Verification</p>
        </div>
        
        @if($name)
        <p style="color: #2d3748; font-size: 16px;">Hello {{ $name }},</p>
        @else
        <p style="color: #2d3748; font-size: 16px;">Hello,</p>
        @endif
        
        <p style="color: #4a5568; font-size: 15px; margin-top: 20px;">
            Thank you for registering with Ashcol Service Desk. Please use the following verification code to verify your email address:
        </p>
        
        <div style="background-color: #f7fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 30px; text-align: center; margin: 30px 0;">
            <h1 style="color: #1a202c; font-size: 36px; letter-spacing: 10px; margin: 0; font-weight: bold; font-family: 'Courier New', monospace;">{{ $code }}</h1>
        </div>
        
        <p style="color: #718096; font-size: 14px; margin-top: 20px;">
            <strong>⚠️ Important:</strong> This code will expire in <strong>10 minutes</strong>.
        </p>
        
        <p style="color: #4a5568; font-size: 14px; margin-top: 20px;">
            If you did not request this verification code, please ignore this email or contact our support team.
        </p>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <p style="color: #718096; font-size: 14px; margin: 0;">
                Best regards,<br>
                <strong style="color: #2d3748;">Ashcol Service Desk</strong><br>
                <span style="font-size: 12px;">ashcol.servicedesk@gmail.com</span>
            </p>
        </div>
        
        <p style="color: #a0aec0; font-size: 12px; margin-top: 30px; text-align: center;">
            This is an automated message from Ashcol Airconditioning Corporation.<br>
            Please do not reply to this email.
        </p>
    </div>
</body>
</html>

