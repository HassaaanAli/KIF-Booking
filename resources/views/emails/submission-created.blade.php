<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booth Submission</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #f59e0b;
        }
        .header img {
            max-width: 250px;
            height: auto;
        }
        .content {
            padding: 30px 20px;
        }
        .content h1 {
            color: #1f2937;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 15px;
            color: #4b5563;
        }
        .submission-details {
            background-color: #f9fafb;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
        }
        .detail-row {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            color: #1f2937;
            font-size: 16px;
        }
        .detail-value.not-provided {
            color: #9ca3af;
            font-style: italic;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #f59e0b;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #d97706;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }
            .header {
                padding: 20px 15px;
            }
            .header img {
                max-width: 200px;
            }
            .content h1 {
                font-size: 20px;
            }
            .button {
                padding: 12px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="header">
            <img src="{{ asset('img/header.svg') }}" alt="KIF Logo">
        </div>

        <!-- Main Content -->
        <div class="content">
            <h1>New Booth Submission Received</h1>
            <p>A new booth submission has been received through the KIF Booking System. Please review the details below:</p>

            <!-- Submission Details -->
            <div class="submission-details">
                <div class="detail-row">
                    <span class="detail-label">Event</span>
                    <span class="detail-value">{{ $submission->event->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Hall</span>
                    <span class="detail-value">{{ $submission->hall->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Booth ID</span>
                    <span class="detail-value">{{ $submission->booth_id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Phone Number</span>
                    <span class="detail-value">{{ $submission->phone_number }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value {{ $submission->email ? '' : 'not-provided' }}">
                        {{ $submission->email ?: 'Not provided' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Company Name</span>
                    <span class="detail-value {{ $submission->company_name ? '' : 'not-provided' }}">
                        {{ $submission->company_name ?: 'Not provided' }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">{{ ucfirst($submission->status) }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Submitted At</span>
                    <span class="detail-value">{{ $submission->created_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <p>Click the button below to view and manage this submission in the admin panel.</p>

            <!-- View Submission Button -->
            <div class="button-container">
                <a href="{{ url('/admin/submissions/' . $submission->id . '/edit') }}" class="button">
                    View Submission
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated notification from the KIF Booking System.</p>
            <p>&copy; {{ date('Y') }} Kuwait International Fair. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
