
<!-- order_checked.blade.php -->
Order Checked<br><br>

Hello {{ $order->user->name }},<br><br>

We wanted to inform you that your order with the following details has been checked:<br><br>

<ul>
    <li><strong>Order ID:</strong> {{ $order->id }}</li>
    <li><strong>Order Date:</strong> {{ $order->order_date }}</li>
    <li><strong>Total Price:</strong> ${{ $order->Total_price }}</li>
</ul>

<p><strong>Order Information:</strong></p>
<ul>
    @foreach ($order->cartItems as $cartItem)
        <li><strong>Product Name:</strong> {{ $cartItem->product->name }}</li>
        <li><strong>Quantity:</strong> {{ $cartItem->quantity }}</li>
        <li><strong>Price:</strong> ${{ $cartItem->price }}</li>
    @endforeach
</ul>

Thank you for your purchase!<br><br>

Regards,<br>
The Store Team




