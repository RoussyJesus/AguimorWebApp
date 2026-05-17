<?php
// Validar que el acceso sea exclusivamente por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Configura aquí el correo donde quieres recibir las cotizaciones
    $to = "tu-correo@aguimorwebapp.com"; 
    
    // 2. Recoger y sanitizar los datos del formulario HTML
    $name    = strip_tags(trim($_POST["username"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone   = strip_tags(trim($_POST["phone"]));
    $service = isset($_POST["service"]) ? strip_tags(trim($_POST["service"])) : "No especificado";

    // Validar campos obligatorios vacíos
    if (empty($name) || empty($email) || empty($phone)) {
        http_response_code(400);
        echo "Por favor, completa todos los campos requeridos.";
        exit;
    }

    // 3. Construir el asunto del correo
    $subject = "Nueva Cotización de: $name - AguimorWebApp";

    // 4. Diseñar el cuerpo del mensaje en formato Texto Plano / HTML simple
    $email_content = "Has recibido una nueva solicitud de cotización desde la Landing Page:\n\n";
    $email_content .= "Nombre del Cliente: $name\n";
    $email_content .= "Correo Electrónico: $email\n";
    $email_content .= "Teléfono de Contacto: $phone\n";
    $email_content .= "Servicio Solicitado: $service\n\n";
    $email_content .= "--- Mensaje generado automáticamente ---";

    // 5. Configurar las cabeceras del correo (Headers)
    // El 'Reply-To' permite que al darle "Responder" en tu bandeja le escribas directamente al cliente
    $headers = "From: Cotizaciones Web <no-reply@aguimorwebapp.com>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // 6. Enviar el correo usando la función nativa de PHP
    if (mail($to, $subject, $email_content, $headers)) {
        http_response_code(200);
        echo "¡Gracias! Tu solicitud de cotización ha sido enviada con éxito.";
    } else {
        http_response_code(500);
        echo "Ups! Algo salió mal y no pudimos procesar tu mensaje.";
    }

} else {
    // Si intentan entrar directo al archivo .php sin enviar el formulario
    http_response_code(403);
    echo "Hubo un problema con tu envío, por favor inténtalo de nuevo.";
}
?>