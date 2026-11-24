<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE usuario = ? AND estado = 1 LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($resultado);

    // Comparamos directamente si el usuario existe y la contraseña coincide en texto plano
    if ($user && $password === $user['password']) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre']     = $user['nombre'];
        $_SESSION['rol']        = $user['rol']; 
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        header("Location: ../login.php?error=1");
        exit;
    }
}