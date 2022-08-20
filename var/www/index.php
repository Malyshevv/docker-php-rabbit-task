<?php
require __DIR__.'/src/Entity/Consumers.php';

$consumers = new Consumers();
$data = $consumers->getAll();
?>

<?php require __DIR__.'/view/layouts/header.php' ?>
<h1 class="mt-5">Hello this index page</h1>
<p class="lead">super text.</p>
<?php require __DIR__.'/view/layouts/footer.php' ?>

