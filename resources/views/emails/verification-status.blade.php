@php
$status = match($status_flag) {
'verified' => ['color' => '#38a169', 'label' => 'Your account has been verified!'],
'declined' => ['color' => '#e53e3e', 'label' => 'Your account has been declined.'],
'pending' => ['color' => '#dd6b20', 'label' => 'Your account is pending verification.'],
default => ['color' => '#718096', 'label' => 'Account status update.'],
};
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $status['label'] }}</title>
</head>

<body style="font-family: Arial, sans-serif; background: #f7fafc; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px;">
        <h2 style="color: {{ $status['color'] }};">{{ $status['label'] }}</h2>

        <p>Hello {{ $user->name }},</p>

        @if ($status_flag === 'verified')
        <p>Congratulations! Your account has been successfully verified. You can now log in and access your dashboard.
        </p>
        @elseif ($status_flag === 'declined')
        <p>Unfortunately, your account has been declined by the administrator. Please contact support if you believe
            this was a mistake.</p>
        @elseif ($status_flag === 'pending')
        <p>Your registration was successful and is now awaiting administrator approval. You'll receive another email
            once your account is verified.</p>
        @else
        <p>We wanted to inform you about a change in your account status.</p>
        @endif

        <p style="margin-top: 30px;">Thank you,<br>The Admin Team</p>
    </div>
</body>

</html>