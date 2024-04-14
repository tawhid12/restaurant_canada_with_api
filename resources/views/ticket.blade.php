<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Information</title>
</head>
<body>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($festivalReg))
        <h1>Ticket Information</h1>
        <p>Your ticket details:</p>
        <ul>
            <li><strong>Mobile:</strong> {{ $festivalReg->mobile }}</li>
            <li><strong>Ticket Number:</strong> {{ $festivalReg->ticket_number }}</li>
        </ul>
    @endif
</body>
</html>
