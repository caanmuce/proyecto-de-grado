<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
</head>
<body>
    
    <div class="container-fluid">
        <center>
            <h1>Chatbot</h1>
        </center>
        <h1 class="h3 mb-4 text-gray-800">SEIN</h1>

        <!-- Chat window -->
        <div class="card shadow mb-4" style="height: 500px; overflow-y: auto;">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Asistente Virtual</h6>
            </div>
            <div class="card-body d-flex flex-column">
            <!-- Mensajes antiguos -->
            <div class="mb-3">
                <div class="d-flex mb-2">
                <div class="bg-light p-2 rounded mr-auto" style="max-width: 75%;">
                    <strong>Bot:</strong> ¡Hola! ¿En qué puedo ayudarte hoy?
                </div>
                </div>
                <div class="d-flex mb-2 justify-content-end">
                <div class="bg-primary text-white p-2 rounded" style="max-width: 75%;">
                    <strong>Tú:</strong> Quiero información sobre el contendio en la aplicacion.
                </div>
                </div>
                <div class="d-flex mb-2">
                <div class="bg-light p-2 rounded mr-auto" style="max-width: 75%;">
                    <strong>Bot:</strong> Claro, ¿deseas conocer algo en particular?
                </div>
                </div>
            </div>
            <!-- Fin de mensajes -->
            <div class="mt-auto">
                <form class="d-flex">
                <input type="text" class="form-control mr-2" placeholder="Escribe tu mensaje..." />
                <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
            </div>
        </div>
    </div>

</body>
</html>