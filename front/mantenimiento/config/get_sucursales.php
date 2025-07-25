<?php
// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// URL del servicio externo (API) que devuelve las sucursales de empresas
$url = 'http://localhost:3000/empresas-sucursales';

// Inicializa una nueva sesión cURL para hacer la petición HTTP
$ch = curl_init();

// Configura la URL a la que se va a hacer la petición
curl_setopt($ch, CURLOPT_URL, $url);

// Indica que se desea recibir la respuesta como string, no imprimirla directamente
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecuta la petición HTTP y guarda la respuesta en $response
$response = curl_exec($ch);

// Cierra la sesión cURL para liberar recursos
curl_close($ch);

// Imprime la respuesta (JSON) para que la reciba el frontend o quien haga la petición
echo $response;
