<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <style>
    body {
      font-size: 0.9rem;
      margin: 0;
      padding: 0;
      width: 100vw;
    }

    .letterhead-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .letterhead-table td {
      vertical-align: top;
      padding: 10px;
      border: none;
    }

    .letterhead-border {
      border-bottom: 2px solid #000;
    }

    h2,
    p {
      text-align: center;
      margin: 10px 0;
    }

    .table-container {
      width: 100vw;
      overflow-x: auto;
      display: flex;
      justify-content: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
    }

    th {
      background-color: #343a40;
      color: white;
    }
  </style>
</head>

<body>
  <table class="letterhead-table letterhead-border">
    <tr>
      <td style="width: 180px;">
        <img src="{{ public_path('storage/assets/static/images/logo/logo.png') }}" alt="Logo" style="width: 180px; height: auto;">
      </td>
      <td style="text-align: right; font-size: 0.85rem; line-height: 1.4;">
        <strong>Dessert by Agatha Delights</strong><br>
        Jl. Karangrejo Timur I No.20<br>
        Kel. Wonokromo, Kota Surabaya (60243)<br>
        087851714421
      </td>
    </tr>
  </table>

  <h2>{{ $title }}</h2>
  <p>Period: {{ $date_start }} to {{ $date_end }}</p>

  <table>
    <thead>
      <tr>
        <th>Transaction Date</th>
        <th>Batch Code</th>
        <th>Product Name</th>
        <th>Variant</th>
        <th>Shelf Name</th>
        <th>Stock Start</th>
        <th>Current Stock</th>
        <th>Expiration Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($inventoryIn as $item)
        <tr>
          <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('Y-m-d') }}</td>
          <td>{{ $item->batch_code }}</td>
          <td>{{ $item->product->name }}</td>
          <td>{{ $item->product->variant->label() }}</td>
          <td>{{ $item->shelf_name }}</td>
          <td>{{ $item->stock_start }}</td>
          <td>{{ $item->current_stock }}</td>
          <td>{{ \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') }}</td>
        </tr>
      @empty
        <tr>
          <td style="text-align:center;" colspan="8">No Inventory [IN] data available.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>

</html>
