<!DOCTYPE html>
<html>
<head>
    <title>Purchased clothes</title>
</head>
<body>
    <p>Dear {{ $userName }},</p>
    <p>You have purchased the following items:</p>
    <ul>
        @foreach ($items as $item)
            <li>
                <p>Name: {{ str_replace('_', ' ', $item['clothes']->name) }}</p>
                <img src="{{ $item['clothes']->clothes_url }}" alt="{{ $item['clothes']->name }}" width="300">
                <p>Price: {{ $item['clothes']->price }}</p>
                <p>Quantity: {{ $item['quantity'] }}</p>
                <p>Item Total: {{ $item['totalItem'] }}</p>
            </li>
        @endforeach
    </ul>
    <p>Total Purchase: {{ $total }}</p>
    <p>Thank you for your purchase!</p>
</body>
</html>