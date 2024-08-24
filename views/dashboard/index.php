<?php include_once __DIR__ .'/header-dashboard.php' ?>
    <?php if(count($proyectos) === 0) {?>
        <p class="no-proyectos">No Hay Proyectos Aún <a href="/crear-proyecto">Comienza ahora</a></p>
        
    <?php } else{ ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto){ ?>
                <li class="proyecto">
                    <a href="/proyecto?url=<?php echo $proyecto->url;?>">
                        <?php echo $proyecto->nombre; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
<?php include_once __DIR__ .'/footer-dashboard.php' ?>