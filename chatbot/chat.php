<?php
include 'datos.php';

function obtener_respuesta($pregunta) {
    global $preguntas_respuestas;

    $pregunta = trim($pregunta);
    $pregunta_cercana = null;
    $distancia_minima = PHP_INT_MAX;

    foreach ($preguntas_respuestas as $key => $respuesta) {
        $distancia = levenshtein($pregunta, $key);
        if ($distancia < $distancia_minima) {
            $distancia_minima = $distancia;
            $pregunta_cercana = $key;
        }
    }

    return isset($preguntas_respuestas[$pregunta_cercana]) ? $preguntas_respuestas[$pregunta_cercana] : "Lo siento, no sé la respuesta.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pregunta = isset($_POST['pregunta']) ? $_POST['pregunta'] : '';
    $pregunta = filter_var($pregunta, FILTER_SANITIZE_STRING);
    $respuesta = obtener_respuesta($pregunta);
    echo json_encode(['respuesta' => $respuesta]);
    exit;
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Método no permitido']);
}
?>
