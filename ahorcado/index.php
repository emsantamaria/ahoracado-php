<?php
session_start();
$mostrar = "";
$mensaje = "";
function getPalabras(): array{
    $file="data/palabras.txt";
    $content=explode("\n",file_get_contents($file));
return $content;
}


$palabras = getPalabras();
if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $palabras[array_rand($palabras)];
    $_SESSION['intentos'] = 6;
    $_SESSION['letras_usadas'] = [];
}



    if (isset($_POST['letra'])) {
    $letra = strtoupper($_POST['letra']);
    if (!in_array($letra, $_SESSION['letras_usadas'])) {
        $_SESSION['letras_usadas'][] = $letra;
        if (strpos($_SESSION['palabra'], $letra) === false) {
            $_SESSION['intentos']--;
        }
    }
}



        
foreach (str_split($_SESSION['palabra']) as $letra) {
    $mostrar .= in_array($letra, $_SESSION['letras_usadas']) ? $letra : "_";
}
if ($mostrar === $_SESSION['palabra']) {
    $mensaje = "Felicidades ¡Ganaste! La palabra era: " . $_SESSION['palabra'];
}
if ($_SESSION['intentos'] <= 0) {
    $mensaje = "Lo siento ¡Perdiste! La palabra era: " . $_SESSION['palabra'];
}




function dibujoAhorcado($intentos) {
    $estados = [
        6 =>file_get_contents("data/6.txt"),
        5 =>file_get_contents("data/5.txt"),
        4 =>file_get_contents("data/4.txt"),
        3 =>file_get_contents("data/3.txt"),
        2 =>file_get_contents("data/2.txt"),
        1 =>file_get_contents("data/1.txt"),
        0 =>file_get_contents("data/1.txt")
    ];
    return "<pre>
    " .$estados[$intentos] . "</pre>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
</head>
<body>
<h1>Juego del Ahorcado</h1>

<?php echo dibujoAhorcado($_SESSION['intentos']); ?>

<p>Palabra: <?php echo implode(" ", str_split($mostrar)); ?></p>
<p>Intentos restantes: <?php echo $_SESSION['intentos']; ?></p>
<p>Letras usadas: <?php echo implode(", ", $_SESSION['letras_usadas']); ?></p>

<?php if ($mensaje == ""): ?>
    <form method="post">
        <label>Introduce una letra:</label>
        <input type="text" name="letra" maxlength="1" required>
        <button type="submit">Adivinar</button>
    </form>
<?php else: ?>
    <p><strong><?php echo $mensaje; ?></strong></p>
    <a href="reset.php">Jugar de nuevo</a>
<?php endif; ?>

</body>
</html>
