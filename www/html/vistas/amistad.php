<?php if (empty($amistades)): ?>

    <div class="sin_amigos">
        <p>La lista de amigos está vacía 😢.</p>
    </div>

<?php else: ?>

    <?php foreach ($amistades as $amigo): ?>
        <a class="amigo_item" href="prototipo_main.php?vista=amistad_detalles&id=<?= $amigo["id"] ?>">
            <img 
                src="<?= !empty($amigo["imagen"]) ? $amigo["imagen"] : '../img/default.png' ?>" 
                class="amigo_avatar"
            >

            <div class="amigo_info">
                <span class="amigo_nombre"><?= htmlspecialchars($amigo["usuario"]) ?></span>

                <?php if ($amigo["conectado"] == 1): ?>
                    <span class="amigo_estado online">● Online</span>
                <?php else: ?>
                    <span class="amigo_estado offline">● Offline</span>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>

<?php endif; ?>