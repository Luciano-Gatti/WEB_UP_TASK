<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <p class="descripcion-pagina">Ingresa tu email para reestablecer tu contraseña</p>
        <form action="/olvide" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="ejemplo@ejemplo.com" name="email">
            </div>
            <input type="submit" class="boton" value="Enviar Email">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crear Cuenta</a>
        </div>
    </div>
</div>