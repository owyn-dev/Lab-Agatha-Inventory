<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Priority Analysis Report</title>
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

    .text-right {
      text-align: right;
    }

    .text-left {
      text-align: left;
    }

    .text-center {
      text-align: center;
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

  <h2>Priority Analysis Report</h2>
  <p>From: {{ $date_start }} - To: {{ $date_end }}</p>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Code</th>
          <th>Product</th>
          <th>Variant</th>
          <th>Total Sold</th>
          <th>Unit Price</th>
          <th>Total Sales</th>
          <th>% Cumulative</th>
          <th>Class</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($results as $item)
          <tr>
            <td>{{ $item['code'] }}</td>
            <td class="text-left">{{ $item['product'] }}</td>
            <td>{{ $item['variant'] ?? '-' }}</td>
            <td>{{ number_format($item['total_sold'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
            <td class="text-right">{{ $item['formatted_sales'] }}</td>
            <td>{{ $item['percentage_cumulative'] }}%</td>
            <td>{{ $item['classification'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8">No Data Available</td>
          </tr>
        @endforelse
        <tr>
          <td class="text-right" colspan="5"><strong>Grand Total</strong></td>
          <td class="text-right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
          <td colspan="2"></td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>
