<?php
session_start();
$mensaje = "";
/**
 * Permite obtener todas las palabras almacenadas
 * @return string[]
 */
function getPalabras(): array{
    $file="data/palabras.txt";
    $content=explode("\n",file_get_contents($file));
return $content;
}

/**
 * Permite iniciar la partida con una palabra random y un numero de intentos administrado
 * @param int $intentos
 * @return void
 */
function init(int $intentos=6){
    $palabras = getPalabras();
    $_SESSION['palabra'] = strtoupper($palabras[array_rand($palabras)]);
    $_SESSION['intentos'] = $intentos;
    $_SESSION['letras_usadas'] = [];
}
/**
 * Permite aniadir la letra usada y comprobar si esta en la palabra o no
 * @param mixed $letra
 * @return void
 */
function agregarLetra($letra){
    if(!in_array($letra,$_SESSION['letras_usadas'])){
        $_SESSION['letras_usadas'][]=$letra;
        if (strpos($_SESSION['palabra'], $letra) === false) {
            $_SESSION['intentos']--;
        }
    }

}
function obtenerMostrarPalabra(): string {
    $mostrar = "";
    foreach (str_split($_SESSION['palabra']) as $letra) {
        $mostrar .= in_array($letra, $_SESSION['letras_usadas']) ? $letra : "_";
    }
    return $mostrar;
}

function dibujoAhorcado($intentos) {
    $content=explode("\n\n\n",file_get_contents("data/6.txt"));
    $estados = [
        6 =>$content[0],
        5 =>$content[1],
        4 =>$content[2],
        3 =>$content[3],
        2 =>$content[4],
        1 =>$content[5],
        0 =>$content[6]
    ];
    if ($intentos>6) {
       return "<pre>
    " .$estados[6] . "</pre>";
    }
    return "<pre>
    " .$estados[$intentos] . "</pre>";
}

if (!isset($_SESSION['palabra'])) {
    init();
}
if (isset($_POST['letra'])) {
    $letra = strtoupper(trim($_POST['letra']));
    if (preg_match('/^[A-Z]$/', $letra)) {
        agregarLetra($letra);
    }
}

$mostrar=obtenerMostrarPalabra();
if ($mostrar === $_SESSION['palabra']) {
    $mensaje = "Felicidades ¡Ganaste! La palabra era: " . $_SESSION['palabra'];
}
if ($_SESSION['intentos'] <= 0) {
    $mensaje = "Lo siento ¡Perdiste! La palabra era: " . $_SESSION['palabra'];
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Ahorcado en PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Juego del Ahorcado</h1>
<div class="dibujo">
<?php echo dibujoAhorcado($_SESSION['intentos']); ?>    
</div>


<p>Palabra: <?php echo implode(" ", str_split($mostrar)); ?></p>
<p>Intentos restantes: <?php echo $_SESSION['intentos']; ?></p>
<p>Letras usadas: <?php echo implode(", ", $_SESSION['letras_usadas']); ?></p>

<?php if ($mensaje == ""): ?>
    <form method="post">
        <label>Introduce una letra:</label>
        <input type="text" name="letra" maxlength="1" required>
        <button type="submit" >Adivinar</button>
    </form>
<?php else: ?>
    <p><strong><?php echo $mensaje; ?></strong></p>
    <a href="reset.php">Jugar de nuevo</a>
<?php endif; ?>

</body>
</html>
