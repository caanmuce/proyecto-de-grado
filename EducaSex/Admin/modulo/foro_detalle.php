<?php
include "../conexion.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
    echo "Foro inválido.";
    exit;
}


if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_comentar'])){
    $foro_id = (int) $_POST['Id_foro'];
    $documento = isset($_POST['documento']) ? mysqli_real_escape_string($conexion, $_POST['documento']) : '';
    $comentario = isset($_POST['comentario']) ? mysqli_real_escape_string($conexion, $_POST['comentario']) : '';

    if($foro_id === $id && $comentario !== ''){
        $sql_ins = "INSERT INTO comentarios (Id_foro, documento, contenido, fecha) VALUES ($foro_id, '$documento', '$comentario', NOW())";
        mysqli_query($conexion, $sql_ins) or die("Error insertando comentario: ".mysqli_error($conexion));
        header("Location: dashboard.php?mod=foro_detalle&id=$id");
        exit;
    } else {
        $msg_error = "El comentario no puede estar vacío.";
    }
}


$sql = "SELECT f.Id_foro, f.titulo_foro, f.contenido, f.documento, c.Nombre_categoria,
               u.primer_nombre, u.primer_apellido
        FROM foro f
        LEFT JOIN categoria c ON f.Id_categoria = c.Id_categoria
        LEFT JOIN usuarios u ON f.documento = u.documento
        WHERE f.Id_foro = $id LIMIT 1";
$res = mysqli_query($conexion, $sql) or die("Error en consulta: " . mysqli_error($conexion));
if(mysqli_num_rows($res) == 0){
    echo "Foro no encontrado.";
    exit;
}
$foro = mysqli_fetch_assoc($res);

$comments = [];
$has_comments_table = mysqli_query($conexion, "SHOW TABLES LIKE 'comentarios'");
if(mysqli_num_rows($has_comments_table) > 0){
    $q = mysqli_query($conexion, 
    "SELECT c.contenido, c.fecha, u.primer_nombre, u.primer_apellido, c.documento
    FROM comentarios c
    INNER JOIN usuarios u ON c.documento = u.documento
    WHERE c.Id_foro = $id
    ORDER BY c.fecha DESC
") or die("Error en comentarios: " . mysqli_error($conexion));
    while($c = mysqli_fetch_assoc($q)) $comments[] = $c;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo htmlspecialchars($foro['titulo_foro']); ?></title>
<style>
.container { max-width:900px; margin:18px auto; padding:0 12px; }
.card { border:1px solid #e6e6ee; border-radius:6px; margin-bottom:18px; background:#fff; }
.card-header { padding:12px 16px; background:#f8f9fb; border-bottom:1px solid #eee; }
.card-header h6 { margin:0; color:#e83e8c; }
.card-body { padding:14px 16px; color:#333; }
.card-meta { color:#777; font-size:13px; margin-bottom:10px; }
.comment { border-top:1px solid #f0f0f0; padding:10px 0; }
.comment div {word-wrap: break-word; overflow-wrap: break-word; white-space: pre-wrap;}
.comment .meta { color:#666; font-size:13px; margin-bottom:6px; }
.form-control { width:100%; padding:8px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box; }
.btn { display:inline-block; padding:8px 12px; border-radius:6px; text-decoration:none; color:#fff; background:#007bff; }
.btn-primary { background:#e83e8c; border:none; color:white; padding:10px 14px; border-radius:20px; }
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <center>
                <h6><?php echo htmlspecialchars($foro['titulo_foro']); ?></h6>
            </center></div>
        <div class="card-body">
            <div class="card-meta">
                <?php
                if(!empty($foro['primer_nombre']) || !empty($foro['primer_apellido'])){
                    echo "Por: " . htmlspecialchars(trim($foro['primer_nombre'] . ' ' . $foro['primer_apellido']));
                } else {
                    echo "Por: " . htmlspecialchars($foro['documento']);
                }
                if(!empty($foro['Nombre_categoria'])) echo " • Categoría: " . htmlspecialchars($foro['Nombre_categoria']);
                ?>
            </div>

            <div style="margin-bottom:16px;">
                <?php echo nl2br(htmlspecialchars($foro['contenido'])); ?>
            </div>

            <a href="dashboard.php?mod=ver_foro" class="btn btn-primary">Volver a la lista</a>
        </div>
    </div>

    <div style="margin-bottom:18px;">
        <h3>Comentarios</h3>

        <?php if(empty($comments)): ?>
            <p style="color:#666;">No hay comentarios aún. Sé el primero en comentar.</p>
        <?php else: ?>
            <?php foreach($comments as $c): ?>
                <div class="comment">
                    <div class="meta">
                        <?php
                        if(!empty($c['primer_nombre']) || !empty($c['primer_apellido'])){
    $author = htmlspecialchars(trim($c['primer_nombre'] . ' ' . $c['primer_apellido']));
} else {
    $author = htmlspecialchars($c['documento']); 
}
echo $author . " • " . htmlspecialchars($c['fecha']);
                        ?>
                    </div>
                    <div><?php echo nl2br(htmlspecialchars($c['contenido'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


    <div style="margin-bottom:60px;">
        <h3>Añadir comentario</h3>
        <?php if(isset($msg_error)): ?><p style="color:red;"><?php echo htmlspecialchars($msg_error); ?></p><?php endif; ?>

        <form action="dashboard.php?mod=foro_detalle&id=<?php echo (int)$id; ?>" method="post">
            <input type="hidden" name="Id_foro" value="<?php echo (int)$id; ?>">

            <?php
            $doc_val = '';
            if(isset($_SESSION['user'])) $doc_val = $_SESSION['user'];
            ?>
            <label>Documento</label><br>
            <input type="text" name="documento" class="form-control" value="<?php echo htmlspecialchars($doc_val); ?>" required readonly><br>

            <label>Comentario</label><br>
            <textarea name="comentario" class="form-control" rows="4" required></textarea><br>

            <button type="submit" name="btn_comentar" class="btn-primary">Publicar comentario</button>
        </form>
    </div>
</div>
</body>
</html>
