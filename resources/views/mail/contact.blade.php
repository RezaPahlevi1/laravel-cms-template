<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Message</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 32px 16px;
            color: #1e293b;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: #1e293b;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #ffffff;
            font-weight: 600;
        }
        .body {
            padding: 32px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 4px;
        }
        .field p {
            margin: 0;
            font-size: 15px;
            color: #1e293b;
            line-height: 1.6;
        }
        .message-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 16px;
            font-size: 15px;
            color: #1e293b;
            line-height: 1.7;
            white-space: pre-wrap;
        }
        .footer {
            padding: 16px 32px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>New Contact Form Message</h1>
        </div>
        <div class="body">
            <div class="field">
                <label>From</label>
                <p>{{ $senderName }} &lt;{{ $senderEmail }}&gt;</p>
            </div>
            <div class="field">
                <label>Subject</label>
                <p>{{ $messageSubject }}</p>
            </div>
            <div class="field">
                <label>Message</label>
                <div class="message-box">{{ $messageBody }}</div>
            </div>
        </div>
        <div class="footer">
            This message was sent via the contact form on your website.
            Reply directly to this email to respond to {{ $senderName }}.
        </div>
    </div>
</body>
</html>