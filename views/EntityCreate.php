<?php


$title = "EntityCreate";

$details = isset($_GET['details']) && $_GET['details'] === 'true';
$modifier = isset($_GET['modifier']) && $_GET['modifier'] === 'true';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$actionUrl = "/Add/" . htmlspecialchars($entity, ENT_QUOTES, 'UTF-8'); // Valeur par défaut pour ajouter

if ($details && isset($tableau) && is_object($tableau)) {
    $actionUrl = "/updated/" . htmlspecialchars($entity, ENT_QUOTES, 'UTF-8') . "/" . htmlspecialchars($tableau->id, ENT_QUOTES, 'UTF-8') . "/?details=true";
} elseif ($modifier && isset($tableau) && is_object($tableau)) {
    $actionUrl = "/updated/" . htmlspecialchars($entity, ENT_QUOTES, 'UTF-8') . "/" . htmlspecialchars($tableau->id, ENT_QUOTES, 'UTF-8') . "/?modifier=true";
}
?>

<body>



<form action="<?= $actionUrl ?>" method="POST" class="form-container">
    <!-- 🔥 Champ caché pour transmettre le modèle -->
    <input type="hidden" name="model" value="<?= htmlspecialchars($entity, ENT_QUOTES, 'UTF-8') ?>">

    <?php if (isset($columns) && is_array($columns)): ?>
        <?php foreach ($columns as $column): ?>
            <div class="form-group">
                <label for="<?= htmlspecialchars($column) ?>"><?= htmlspecialchars($column) ?></label>
                <input
                    type="text"
                    id="<?= htmlspecialchars($column) ?>"
                    name="<?= htmlspecialchars($column) ?>"
                    class="form-control"
                    value="<?php
                    if (isset($tableau) && $tableau->$column !== null) {
                        echo htmlspecialchars($tableau->$column, ENT_QUOTES, 'UTF-8');
                    } else {
                        echo ''; // ou une valeur par défaut si nécessaire
                    }
                ?>"
                >
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune colonne à afficher. Vérifiez la configuration de votre table.</p>
    <?php endif; ?>

    <!-- Modification dynamique du bouton en fonction de la variable 'modifier' -->
    <?php if ($details): ?>
        <a href="javascript:history.back()" class="btn btn-link">Retour</a>
    <?php elseif ($modifier): ?>
        <input type="submit" value="Modifier" class="btn btn-link" />
    <?php else: ?>
        <input type="submit" value="Ajouter" class="btn btn-link" />
    <?php endif; ?>
</form>

</body>

