<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festival Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 200px;
        }

        .ticket-info {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .ticket-info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .ticket-info ul li {
            margin-bottom: 10px;
        }

        .ticket-info ul li strong {
            font-weight: bold;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
    </style>
</head>

<body>

    <div class="container">
        @if (isset($successMessage))
            <div class="alert alert-success">
                {{ $successMessage }}
            </div>
        @endif
        <div class="header">
            <img alt="" src="{{ asset('') }}assets/img/panta-ilish.jpg"
                            class="img-fluid rounded">
        </div>
        @if (isset($festivalReg))
            <div class="ticket-info">
                <h2>Your Festival Ticket</h2>
                <ul>
                    <li><strong>Full Name:</strong> {{ $festivalReg->fullName }}</li>
                    <li><strong>Email:</strong> {{ $festivalReg->email }}</li>
                    <li><strong>Mobile:</strong> {{ $festivalReg->mobile }}</li>
                    <li><strong>Authorised Ticket Number:</strong> {{ $festivalReg->ticket_number }}</li>
                    <li><strong>Generate Ticket Number:</strong> {{ $festivalReg->id }}</li>
                </ul>
            </div>
        @endif
    </div>
</body>

</html>
