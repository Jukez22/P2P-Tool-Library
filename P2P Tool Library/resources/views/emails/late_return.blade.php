<!DOCTYPE html>
<html>
<head>
    <title>Late Return Notice</title>
</head>
<body>
    <h1>Late Return Notice</h1>
    <p>Dear {{ $escalation->borrow->borrower->name }},</p>
    <p>This is a notice regarding your late return of the tool: <strong>{{ $escalation->borrow->tool->title }}</strong>.</p>
    <ul>
        <li><strong>Days Late:</strong> {{ $escalation->days_late }}</li>
        <li><strong>Penalty Amount:</strong> ${{ number_format($escalation->penalty_amount, 2) }}</li>
        <li><strong>Escalation Level:</strong> {{ ucfirst(str_replace('_', ' ', $this->escalation->escalation_level)) }}</li>
    </ul>
    <p>Please return the tool as soon as possible to avoid further penalties.</p>
    <p>Thank you.</p>
</body>
</html>
