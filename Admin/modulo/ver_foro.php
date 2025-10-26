<?php
include "../conexion.php"; 


$sql = "SELECT f.Id_foro, f.titulo_foro, f.contenido, f.documento, 
               c.Nombre_categoria,
               u.primer_nombre, u.primer_apellido
        FROM foro f
        LEFT JOIN categoria c ON f.Id_categoria = c.Id_categoria
        LEFT JOIN usuarios u ON f.documento = u.documento
        ORDER BY f.Id_foro DESC";
$res = mysqli_query($conexion, $sql) or die("Error en consulta: " . mysqli_error($conexion));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Foros</title>
<style>
    .container { max-width:1100px; margin:20px auto; padding:0 16px; }
    .card { border:1px solid #e6e6ee; border-left:4px solid #e83e8c; border-radius:6px; margin-bottom:18px; background:#fff; }
    .card-header { padding:12px 16px; background:#f8f9fb; border-bottom:1px solid #eee; }
    .card-header h6 { margin:0; color:#e83e8c; font-size:16px; }
    .card-body { padding:14px 16px; color:#444; }
    .card-meta { font-size:13px; color:#888; margin-bottom:8px; }
    .truncate { max-height:4.6em; overflow:hidden; }

    .bot{
            cursor: pointer;
            transition: .4s;
            color: white; 
            background: #f790cc; 
            padding: 8px 12px; 
            border-radius: 5px; 
            text-decoration: none;"
        }

        .bot:hover{
            transform: scale(0.9);
            color: #f56fbdff;
            background: #f8d7f3ff;
            box-shadow: 15px 15px 15px rgba(247, 144, 204, 1);
        }
</style>
</head>
<body>
    <center>
        <a href="dashboard.php?mod=ver_foro">Ver</a> | <a href="dashboard.php?mod=crear_foro">Crear</a>
        <?php if ($rol == 1 || $rol == 3) { ?> | <a href="dashboard.php?mod=foro">Gestionar</a>
        <?php } ?>
    </center>
    <div class="container">
        <h1 style="text-align:center; color:#666;">Foros</h1>

        <?php if(mysqli_num_rows($res) == 0): ?>
            <p style="text-align:center; color:#777;">No hay foros todavía.</p>
        <?php else: ?>
            <?php while($f = mysqli_fetch_assoc($res)): ?>
                <div class="card" role="article" aria-labelledby="titulo-<?php echo (int)$f['Id_foro']; ?>">
                    <div class="card-header">
                        <center>
                            <h5 style="color: #df4ea3;" id="titulo-<?php echo (int)$f['Id_foro']; ?>"><?php echo htmlspecialchars($f['titulo_foro']); ?></h5>
                        </center>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <?php
                            
                            if(!empty($f['primer_nombre']) || !empty($f['primer_apellido'])){
                                echo "Por: " . htmlspecialchars(trim($f['primer_nombre'] . ' ' . $f['primer_apellido']));
                            } else {
                                echo "Por: " . htmlspecialchars($f['documento']);
                            }
                            
                            if(!empty($f['Nombre_categoria'])) {
                                echo " • Categoría: " . htmlspecialchars($f['Nombre_categoria']);
                            }
                            ?>
                        </div>

                        <!-- Mostrar una vista previa (truncada) -->
                        <div class="truncate">
                            <?php
                            // Mostrar contenido con saltos (pero seguro)
                            echo nl2br(htmlspecialchars($f['contenido']));
                            ?>
                        </div>

                        <div style="margin-top:10px; text-align: center;">
                            <a class="btn btn-primary bot" href="dashboard.php?mod=foro_detalle&id=<?php echo (int)$f['Id_foro']; ?>">Ver foro</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
