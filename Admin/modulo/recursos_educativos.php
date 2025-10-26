<?php
include "../conexion.php";


$sql = "SELECT r.id_recurso, r.titulo, r.resumen, r.archivo,
               t.Nombre_tipo, 
               c.Nombre_categoria
        FROM recursos_educativos r
        JOIN tipo t ON r.id_tipo = t.id_tipo
        JOIN categoria c ON r.id_categoria = c.id_categoria
        ORDER BY r.id_recurso DESC";

$result = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos educativos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr; 
            }
        }

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


        .recurso-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            padding: 16px;
        }

        .recurso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }           

        .btn-ver {
            background: #f790cc;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-ver:hover {
            color: #df4ea3;
            background:rgb(247, 211, 233);
            transform: scale(1.05);
            text-decoration: none;
        }

        .recurso-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .titulo-seccion {
            margin-bottom: 20px;
            position: relative;
            text-align: center;
        }

        .titulo-seccion::after {
            content: "";
            width: 60px;
            height: 4px;
            background: #e83e8c;
            display: block;
            margin: 8px auto 0;
            border-radius: 2px;
        }

    </style>
</head>
<body>
    <center>
        <?php if ($rol == 1 || $rol == 3) { ?>
            <a href="dashboard.php?mod=recursos_educativos">Ver</a> | 
            <a href="dashboard.php?mod=crear_recurso">Crear</a> | 
            <a href="dashboard.php?mod=consultar_recurso">Consultar</a>
        <br><br>
        <?php } ?>
        <h1 class="titulo-seccion">
            <i class="fa-solid fa-book-open"></i> 
            Recursos Educativos
        </h1>
    </center>

    <section class="recursos-educativos" style="padding: 20px;">

        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <div class="recurso-card">
                    
                    
                    <h3 style="font-size: 18px; font-weight: bold;"><?php echo $row['titulo']; ?></h3>
                    
                    
                    <h3 style="font-size: 15px; font-weight: bold;">Tipo: <?php echo $row['Nombre_tipo']; ?></h3>
                    
                    
                    <h3 style="font-size: 15px; font-weight: bold;">Categoría: <?php echo $row['Nombre_categoria']; ?></h3>
                    
                    
                    <p style="font-size: 14px; margin: 10px 0;"><?php echo $row['resumen']; ?></p>
                    
                    
                    <?php if(!empty($row['archivo'])) { 
                        
                        $uploadsUrl = '/EducaSex/uploads/';
                        $href = $uploadsUrl . rawurlencode($row['archivo']);
                    ?>
                    
                        <a href="<?php echo $href; ?>" target="_blank" 
                           class="btn-ver">
                           Ver recurso
                        </a>
                    <?php } else { ?>
                        <span style="color: gray; font-size: 12px;">Sin archivo adjunto</span>
                    <?php } ?>
                
                </div> 
            <?php } ?>

        </div>
    </section>
</body>
</html>

