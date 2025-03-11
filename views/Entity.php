<?php 
 

$title = "Entity";

// Définir les colonnes à exclure
$excludedColumns = ['id', 'numero', 'courriel'];
?>

<?php

if (isset($message)) {
    echo "<div class='alert alert-success' role='alert'>" . htmlspecialchars($message) . "</div>";
}
?>
<body>
<a href='/EntityCreate/<?= htmlspecialchars($entity, ENT_QUOTES, 'UTF-8') ?>/<?= 1 ?>/?ajouter=true/' class="btn btn-success my-3">
    Ajouter une Nouvelle <?php echo $entity; ?>
</a>

<h2><?php echo $entity; ?></h2>
<div class="table-container">
<table id="kamel" class="table table-hover" data-excluded=data-excluded='<?= json_encode($excludedColumns) ?>'>
 <!-- Dans le header du tableau -->
<thead class="thead-dark">
    <tr>
        <?php foreach ($columns as $index => $column): ?>
            <?php if (!in_array($column, $excludedColumns)): ?>
                <th>
                    <span class="header-with-icon">
                        <?= htmlspecialchars($column) ?>
                        <img src="/img/image.png" alt="icône">
                    </span>
                </th>
            <?php endif; ?>
        <?php endforeach; ?>
        <th>Détails</th>
        <th>Modifier</th>
        <th>Supprimer</th>
    </tr>
</thead>

<tbody>
    <?php if (isset($tableau) && is_array($tableau) && !empty($tableau)): ?>
        <?php $premiers5 = array_slice($tableau, 0, 5); ?>
        <?php foreach ($premiers5 as $resultat): ?>
            <tr>
                <?php foreach ($columns as $column): ?>
                    <?php if (!in_array($column, $excludedColumns)): ?>
                        <td>
                            <?php if ($column === "message"): ?>
                                <?= htmlspecialchars($resultat->getExtract()); ?>
                            <?php else: ?>
                                <?= htmlspecialchars($resultat->$column ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href="/EntityCreate/<?= htmlspecialchars($entity) ?>/<?= htmlspecialchars($resultat->id) ?>/?details=true" class="btn btn-primary">Détails</a></td>
                <td><a href="/EntityCreate/<?= htmlspecialchars($entity) ?>/<?= htmlspecialchars($resultat->id) ?>/?modifier=true" class="btn btn-warning">Modifier</a></td>
                <td><a href="/entityDeleted/<?= htmlspecialchars($entity) ?>/<?= htmlspecialchars($resultat->id) ?>" class="btn btn-danger">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= count($columns) - count($excludedColumns) + 3 ?>" class="text-center">Aucun enregistrement trouvé.</td>
        </tr>
    <?php endif; ?>
</tbody>
</table>
</div>
<button id="load-more" data-page="1" class="btn btn-primary my-3">Charger plus</button>
    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_rgpd">&copy; Les mentions légales</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_rgpd_cgu">Conditions générales d'utilisation</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_contact">Politiques des Cookies</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_contact">&hearts; Contact Moi</a></div>
            </div>
        </div>
    </div>
<script>
    $('#load-more').click(function() {
        var button = $(this);
        var page = button.data('page');

        $.ajax({
            url: '/getData.php?entity=<?= urlencode($entity) ?>',
            type: 'GET',
            data: {
                page: page + 1
            },
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    var elementsHtml = '';
                    $.each(data, function(index, element) {
                        elementsHtml += '<tr>';
                        <?php foreach ($columns as $column): ?>
                            if ('<?= $column ?>' === 'url_site_association' && element.getExtract) {
                                elementsHtml += '<td>' + $('<div/>').text(element.getExtract).html() + '</td>';
                            } else {
                                elementsHtml += '<td>' + $('<div/>').text(element['<?= $column ?>'] ?? '').html() + '</td>';
                            }
                        <?php endforeach; ?>
                        elementsHtml += '<td><a href="/EntityCreate/' + encodeURIComponent('<?= $entity ?>') + '/' + element.id + '/?details=true" class="btn btn-primary">Détails</a></td>';
                        elementsHtml += '<td><a href="/EntityCreate/' + encodeURIComponent('<?= $entity ?>') + '/' + element.id + '/?modifier=true" class="btn btn-warning">Modifier</a></td>';
                        elementsHtml += '<td><a href="/entityDeleted/' + encodeURIComponent('<?= $entity ?>') + '/' + element.id + '" class="btn btn-danger">Supprimer</a></td>';
                        elementsHtml += '</tr>';
                    });
                    $('table tbody').append(elementsHtml);
                    button.data('page', page + 1);
                } else {
                    button.text('Aucun élément à charger');
                    button.prop('disabled', true);
                }
            },
            error: function() {
                alert('Erreur lors du chargement des données.');
            }
        });
    });
</script>

<script>
    document.querySelectorAll('#kamel th').forEach((th, colIndex) => {
        let isAscending = true;

        th.addEventListener('click', function() {
            const table = th.closest('table');
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            isAscending = !isAscending;

            const sortedRows = rows.sort((a, b) => {
                const aText = a.children[colIndex].innerText.trim();
                const bText = b.children[colIndex].innerText.trim();

                if (!isNaN(aText) && !isNaN(bText)) {
                    return isAscending ?
                        parseFloat(aText) - parseFloat(bText) :
                        parseFloat(bText) - parseFloat(aText);
                } else {
                    return isAscending ?
                        aText.localeCompare(bText, 'fr', {
                            numeric: true
                        }) :
                        bText.localeCompare(aText, 'fr', {
                            numeric: true
                        });
                }
            });

            table.querySelector('tbody').append(...sortedRows);
        });
    });
</script>

</body>
