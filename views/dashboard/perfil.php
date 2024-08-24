<?php include_once __DIR__ .'/header-dashboard.php' ?>
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <div class="contenedor-enlace">
            <a href="/cambiar-password" class="enlace">Cambiar Contraseña</a>
        </div>
        <form action="/perfil" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    value="<?php echo $usuario->nombre; ?>" 
                    name="nombre" 
                    placeholder="Tu Nombre"
                >
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="text" 
                    value="<?php echo $usuario->email; ?>" 
                    name="email" 
                    placeholder="correo@correo.com"
                >
            </div>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
<?php include_once __DIR__ .'/footer-dashboard.php' ?>