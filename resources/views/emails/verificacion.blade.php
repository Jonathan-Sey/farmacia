<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Autorización de Devolución</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    .container {
      max-width: 700px;
      margin: auto;
      background-color: #ffffff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    h1 {
      font-size: 24px;
      color: #0056b3;
      text-align: center;
      margin-bottom: 25px;
    }

    p {
      font-size: 15px;
      line-height: 1.6;
      margin: 10px 0;
    }

    .info-block {
      background-color: #f8f9fa;
      padding: 15px;
      border-left: 4px solid #007BFF;
      margin-bottom: 20px;
      border-radius: 6px;
    }

    .info-block strong {
      display: inline-block;
      min-width: 150px;
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size: 14px;
    }

    th, td {
      padding: 12px 10px;
      border: 1px solid #e0e0e0;
      text-align: left;
    }

    th {
      background-color: #007BFF;
      color: #ffffff;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .btn {
      display: block;
      margin: 30px auto 0;
      padding: 14px 30px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      font-size: 15px;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #218838;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 20px;
      }

      .info-block strong {
        display: block;
        margin-bottom: 5px;
      }

      table {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Autorización de Devolución</h1>

    <div class="info-block">
      <p><strong>ID de devolución:</strong> {{ $devolucion->id }}</p>
      <p><strong>Motivo de la devolución:</strong> {{ $devolucion->motivo }}</p>
      <p><strong>Fecha de solicitud:</strong> {{ $devolucion->created_at }}</p>
    </div>

    <p><strong>Productos incluidos en esta devolución:</strong></p>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>

            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($devolucionesDetalles as $detalle)
          <tr>
            <td>{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</td>
            <td>{{ $detalle->cantidad }}</td>

            <td>Q.{{ number_format($detalle->subtotal, 2) }}</td>
      
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <a class="btn" href="{{ $url }}">Autorizar devolución</a>
  </div>
</body>
</html>
