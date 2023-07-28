
  <h2>New Order Placed</h2>

  <p>Dear Team,</p>

  <p>A new order has been placed. Here are the order details:</p>

  <ul>
      <li><strong>Order ID:</strong> {{ $data['order_id'] }}</li>
      <li><strong>Order Date:</strong> {{ $data['order_date']->format('Y-m-d H:i:s') }}</li>
      <li><strong>Total Price:</strong> ${{ $data['total_price'] }}</li>
      <li><strong>Shipping Address:</strong> {{ $data['shipping_address'] }}</li>
      <li><strong>City:</strong> {{ $city_name ?: 'N/A' }}</li>
      <li><strong>Place:</strong> {{ $place_name ?: 'N/A' }}</li>
  </ul>

  <p><strong>Customer Information:</strong></p>
  <ul>
      <li><strong>Name:</strong> {{ $data['user_name'] }}</li>
      <li><strong>Phone:</strong> {{ $data['user_phone'] }}</li>
  </ul>

  <p>Thank you for your attention.</p>

  <p>Regards,<br>
      Your E-commerce Team</p>







