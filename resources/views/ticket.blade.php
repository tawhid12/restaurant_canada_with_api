<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Information</title>
</head>
<body>
    @php dd($festivalReg); @endphp
    <h1>Ticket Information</h1>
    <p>Your ticket details:</p>
    <ul>
        <li><strong>Mobile:</strong> {{ $mobile }}</li>
        <li><strong>Ticket Number:</strong> {{ $ticketNumber }}</li>
    </ul>
</body>
</html>
