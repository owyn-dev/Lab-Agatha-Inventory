<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Report</title>
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

  <h2>Sales Report</h2>
  <p>From: {{ $date_start }} - To: {{ $date_end }}</p>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Trans. Date</th>
          <th>Product Code</th>
          <th>Product Name</th>
          <th>Variant</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Sub Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reports as $report)
          @foreach ($report->detailSale as $detail)
            <tr>
              <td>{{ \Carbon\Carbon::parse($report->transaction_date)->format('Y-m-d') }}</td>
              <td>{{ $detail->product->code }}</td>
              <td style="text-align: left;">{{ $detail->product->name }}</td>
              <td>{{ $detail->product->variant->label() }}</td>
              <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
              <td>{{ $detail->quantity }}</td>
              <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        @empty
          <tr>
            <td colspan="7">No Data Available</td>
          </tr>
        @endforelse
        <tr>
          <td style="text-align: right; font-weight: bold;" colspan="6">Total</td>
          <td style="font-weight: bold;">{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>
