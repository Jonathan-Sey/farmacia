<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>verificacion de devoluciones</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f4f4;
    }

    h1 {
      color: #333;
    }

    p {
      color: #555;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    a:hover {
      background-color: #0056b3;
    }

    table th {
      background-color: #007BFF;
      color: white;
    }
  </style>
</head>

<body>
  <h1>Autorización de devolución</h1>
  <p>Se ha creado una devolución con ID: {{ $devolucion->id }}</p>
  <p>Detalles de la devolucion</p>
  <table>
    <thead>
      <th>Nombre del producto</th>
      <th>Cantidad</th>
      <th>Monto</th>
      <th>Motivo</th>
      <th>Observaciones</th>
    </thead>
    <tbody>
    @foreach ($devoluciones as $proveedor)
      <tr>
        <td>{{ $proveedor->productos->nombre }}</td>
        <td>{{ $proveedor->cantidad }}</td>
        <td>{{ $proveedor->monto }}</td>
        <td>{{ $proveedor->motivo }}</td>
        <td>{{ $proveedor->observaciones }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <p><a href="{{ $url }}">Autorizar devolución</a></p>
</body>

</html>