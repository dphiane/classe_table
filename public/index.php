<?php

use App\NumberHelper;
use App\QueryBuilder;
use App\TableHelper;
use App\URLHelper;
use App\Table;

require '../../tableau_dynamique/elements/header.php';
require __DIR__ . '../../vendor/autoload.php';

define('PER_PAGE', 20);

$pdo = new PDO("sqlite:../products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$query = (new QueryBuilder($pdo))->from('products');

// Recherche par Ville
if (!empty($_GET["q"])) {
    $query
        ->where('city LIKE :city')
        ->setParam('city', '%' . $_GET['q'] . '%');
}

// Pagination

$table = (new Table($query, $_GET))
    ->sortable('id', 'city', 'price')
    ->format('price', function ($value) {
        return NumberHelper::price($value);
    })
    ->columns([
        'id' => 'ID',
        'name' => 'Nom',
        'city' => 'Ville',
        'price' => 'Prix'
    ]);
?>

<h1>Les biens immobiliers</h1>
<form action="" class="mb-4">
    <div class="form-group">
        <input class="form-control" type="text" name="q" placeholder="Rechercher par ville" value="<?= isset($_GET['q']) ? htmlentities($_GET['q']) : '' ?>">
    </div>
    <button type="submit" class="btn btn-primary">Rechercher</button>
</form>

<?php $table->render() ?>


<?php
require '../../tableau_dynamique/elements/footer.php';

?>