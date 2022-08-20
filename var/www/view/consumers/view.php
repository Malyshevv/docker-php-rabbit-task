<?php
require __DIR__.'/../../src/Entity/Consumers.php';

$consumers = new Consumers();
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = $consumers->getOne($id);
}
$data = $data['data'] ?? [];
?>

<!-- Begin page content -->

<?php require __DIR__.'/../layouts/header.php' ?>
<div class="mt-5">
    <?php if (empty($data)) : ?>
        <center>
            <p style="color: gray;"><i>Data not found</i></p>
            <a href="/consumers/list.php" class="btn btn-info"> GO BACK</a>
        </center>
    <?php else: ?>
        <h1><?=$data['msg']?></h1>
        <p><?=$data['createdAt']?></p>
        <p class="lead"><?=$data['code']?></p>
        <pre>
            <?=$data['content']?>
        </pre>
    <?php endif; ?>

</div>
<?php require __DIR__.'/../layouts/footer.php' ?>
