<?php

echo '</p>Consumo del JSON del ET</p>';

$url = 'https://api.mercadopublico.cl/servicios/v1/publico/licitaciones.json?fecha=30052025&estado=revocada&ticket=AC3A098B-4CD0-41AF-81A5-41284248419B';

try {
    $jsonDataString = file_get_contents($url);
    if ($jsonDataString === false) {
        throw new Exception("No se pudo obtener el contenido de la URL.");
    }
    // Decode the JSON string into a PHP array/object
    $data = json_decode($jsonDataString, true); // true para array asociativo
    
    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
    }

    // Now, $data is a PHP array/object.
    // When passing it to JavaScript, you need to encode it back to a JSON string.
    $dataForJs = json_encode($data);

} catch (Throwable $th) {
    echo '<p style="color: red">Error: ' . $th->getMessage() . '</p>';
    $dataForJs = 'null'; // Set to null or an empty object/array for JavaScript in case of error
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON Data</title>
</head>
<body>
    <p>Info del JSON</p>
    <hr>
    <pre>
        <div id="contenidoJSON"></div>
    </pre>
    <hr>
    <script>
        const divJSON = document.getElementById('contenidoJSON');
        const dataJSON = <?php echo $dataForJs ?? 'null'; ?>; // Using null coalescing operator for safety
        
        if (dataJSON) {
            // Use JSON.stringify to pretty-print the JSON in the div
            divJSON.innerText = JSON.stringify(dataJSON, null, 2);
            console.log('dataJSON: ' + dataJSON['Cantidad']);
        } else {
            divJSON.innerText = 'No se pudo cargar o decodificar la información JSON.';
        }
    </script>
</body>
</html>
