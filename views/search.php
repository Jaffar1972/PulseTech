
<body>

<?php 
$search = isset($search) ? htmlspecialchars($search) : '';
?>
<h2>Résultats de recherche pour "<?php echo $search; ?>"</h2>

<?php if (!empty($tableau)) : ?>
    <ul>
        <?php foreach ($tableau as $item) : ?>
            <li>
                <strong>Nom :</strong> <?php echo htmlspecialchars($item->nom); ?><br>
                <strong>Président :</strong> <?php echo htmlspecialchars($item->president_association); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Aucun résultat trouvé pour "<?php echo $search; ?>".</p>
<?php endif; ?>

</body>
