<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Alert</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #fafafa;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #fafafa;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            padding: 32px 32px 24px 32px;
            border-bottom: 1px solid #f0f0f0;
        }
        .logo {
            font-size: 14px;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .content {
            padding: 32px;
        }
        .title {
            font-size: 18px;
            font-weight: 600;
            color: #111111;
            margin-top: 0;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #666666;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .alert-box {
            background-color: #f8f9fa;
            border-left: 3px solid #4f46e5;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 28px;
        }
        .alert-box-warning {
            background-color: #fffbeb;
            border-left: 3px solid #d97706;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 28px;
        }
        .alert-text {
            font-size: 13px;
            line-height: 1.5;
            color: #333333;
            margin: 0;
            font-weight: 500;
        }
        .table-container {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table tr:not(:last-child) {
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table th, .details-table td {
            padding: 14px 16px;
            font-size: 13px;
            text-align: left;
        }
        .details-table th {
            color: #666666;
            font-weight: 500;
            width: 35%;
            background-color: #fafafa;
        }
        .details-table td {
            color: #111111;
            font-weight: 600;
        }
        .btn-container {
            margin-top: 24px;
            text-align: left;
        }
        .btn {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
        }
        .footer {
            padding: 24px 32px;
            background-color: #fafafa;
            border-top: 1px solid #f0f0f0;
        }
        .footer p {
            margin: 0 0 6px 0;
            font-size: 11px;
            color: #888888;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <span class="logo">Techodesk</span>
            </div>

            <!-- Content -->
            <div class="content">
                <h2 class="title">Payment Alert Notification</h2>
                <p class="intro-text">
                    This is an automated payment alert notification regarding client services. Please review the details below:
                </p>

                <!-- Alert Card -->
                @if ($alert->alert_type === 'interval_days')
                    <div class="alert-box">
                        <p class="alert-text">
                            <strong>Interval Period Reached:</strong> The configured payment alert interval of <strong>{{ $alert->days_interval }} days</strong> has passed since this alert was created.
                        </p>
                    </div>
                @else
                    <div class="alert-box-warning">
                        <p class="alert-text">
                            <strong>Scheduled Date Reached:</strong> The scheduled payment alert target of <strong>{{ $alert->alert_date ? $alert->alert_date->format('M d, Y h:i A') : 'N/A' }}</strong> has been reached.
                        </p>
                    </div>
                @endif

                <!-- Details Table -->
                <div class="table-container">
                    <table class="details-table">
                        <tr>
                            <th>Client</th>
                            <td>{{ $alert->client?->business_name ?? $alert->client?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Contact Email</th>
                            <td>{{ $alert->client?->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>{{ $alert->service?->name ?? 'N/A' }}</td>
                        </tr>
                        @if ($alert->alert_type === 'interval_days')
                            <tr>
                                <th>Days Interval</th>
                                <td>{{ $alert->days_interval }} days</td>
                            </tr>
                        @else
                            <tr>
                                <th>Alert Date</th>
                                <td>{{ $alert->alert_date ? $alert->alert_date->format('M d, Y h:i A') : 'N/A' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Alert Created</th>
                            <td>{{ $alert->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- CTA Button -->
                <div class="btn-container">
                    <a href="{{ config('app.url') }}/client" class="btn">Manage Client Alerts</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>This is an automated system notification. Please do not reply directly to this email.</p>
                <p>&copy; {{ date('Y') }} Techodesk. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
