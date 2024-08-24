<?php include_once __DIR__ .'/header-dashboard.php' ?>
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <div class="contenedor-enlace">
            <a href="/perfil" class="enlace">&#10094; Volver a Perfil</a>
        </div>

        <form action="/cambiar-password" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Contraseña Actual</label>
                <input 
                    type="password" 
                    name="password_actual" 
                    placeholder="Tu Contraseña Actual"
                >
            </div>
            <div class="campo">
                <label for="email">Contraseña Nueva</label>
                <input 
                    type="password" 
                    name="password_nuevo" 
                    placeholder="Tu Contraseña Nueva"
                >
            </div>
            <div class="campo">
                <label for="email">Repita la Contraseña</label>
                <input 
                    type="password" 
                    name="password2" 
                    placeholder="Repite tu contraseña"
                >
            </div>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
<?php include_once __DIR__ .'/footer-dashboard.php' ?>