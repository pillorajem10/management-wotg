<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Group Member Approval Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .btn-approve {
            display: inline-block;
            background-color: #c0392b;
            color: #ffffff;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-approve:hover {
            background-color: #c0392b;
            color: #ffffff;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #888888;
        }
        .footer a {
            color: #c0392b;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .note {
            background-color: #f7f7f7;
            padding: 15px;
            border-left: 5px solid #c0392b;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <p>Dear {{ $dgroupLeader->user_fname }},</p>

        <p>One of your members, <strong>{{ $memberEmail }}</strong>, has requested to join your D-Group. Please review their registration and approve them to finalize the process.</p>

        <a href="https://blogs.wotgonline.com/d-group" class="btn-approve">
            See Member Request
        </a>              

        <div class="note">
            <p><strong>Note:</strong> If you have any questions, feel free to reach out to the admin or contact support.</p>
        </div>

        <div class="footer">
            <p>Thank you for your time and leadership!</p>
            <p>Best regards, <br> WOTG Online</p>
        </div>
    </div>
</body>
</html>
