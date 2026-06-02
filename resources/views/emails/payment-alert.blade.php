<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Alert Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f5f7;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 48px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            padding: 32px 32px 24px 32px;
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
        }
        .logo-mark {
            display: inline-block;
            height: 36px;
            width: 36px;
            line-height: 36px;
            background-color: #0f172a;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 8px;
        }
        .logo-text {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0;
        }
        .content {
            padding: 32px;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            text-align: center;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.5;
            color: #64748b;
            margin-top: 0;
            margin-bottom: 24px;
            text-align: center;
        }
        .status-banner {
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: left;
        }
        .status-banner-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        .status-banner-warning {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
        }
        .status-title {
            font-size: 13px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-title-info {
            color: #1d4ed8;
        }
        .status-title-warning {
            color: #b45309;
        }
        .status-text {
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }
        .status-text-info {
            color: #1e40af;
        }
        .status-text-warning {
            color: #92400e;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            display: table-cell;
            width: 35%;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: top;
            padding-right: 12px;
        }
        .detail-value {
            display: table-cell;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            vertical-align: top;
        }
        .btn-container {
            text-align: center;
            margin-top: 28px;
        }
        .btn {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: background-color 0.15s ease;
        }
        .footer {
            padding: 24px 32px;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }
        .footer p {
            margin: 0 0 6px 0;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <span class="logo-mark">TD</span>
                <h1 class="logo-text">Techodesk</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <h2 class="title">Payment Alert Notification</h2>
                <p class="intro-text">
                    This is an automated payment alert notification regarding client services.
                </p>

                <!-- Status Banner -->
                @if ($alert->alert_type === 'interval_days')
                    <div class="status-banner status-banner-info">
                        <div class="status-title status-title-info">Interval Reached</div>
                        <p class="status-text status-text-info">
                            The configured payment alert interval of <strong>{{ $alert->days_interval }} days</strong> has passed since this alert was created.
                        </p>
                    </div>
                @else
                    <div class="status-banner status-banner-warning">
                        <div class="status-title status-title-warning">Scheduled Date Reached</div>
                        <p class="status-text status-text-warning">
                            The scheduled payment alert target of <strong>{{ $alert->alert_date ? $alert->alert_date->format('M d, Y h:i A') : 'N/A' }}</strong> has been reached.
                        </p>
                    </div>
                @endif

                <!-- Details Card -->
                <div class="details-card">
                    <div class="detail-row">
                        <div class="detail-label">Client</div>
                        <div class="detail-value">{{ $alert->client?->business_name ?? $alert->client?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Contact</div>
                        <div class="detail-value">{{ $alert->client?->email ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Service</div>
                        <div class="detail-value">{{ $alert->service?->name ?? 'N/A' }}</div>
                    </div>
                    @if ($alert->alert_type === 'interval_days')
                        <div class="detail-row">
                            <div class="detail-label">Interval</div>
                            <div class="detail-value">{{ $alert->days_interval }} days</div>
                        </div>
                    @else
                        <div class="detail-row">
                            <div class="detail-label">Alert Date</div>
                            <div class="detail-value">{{ $alert->alert_date ? $alert->alert_date->format('M d, Y h:i A') : 'N/A' }}</div>
                        </div>
                    @endif
                    <div class="detail-row">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">{{ $alert->created_at->format('M d, Y h:i A') }}</div>
                    </div>
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
