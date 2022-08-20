<?php
require __DIR__.'/../../src/Entity/Consumers.php';

$consumers = new Consumers();
$data = $consumers->getAll();

$data = $data['data'] ?? [];
?>

<!-- Begin page content -->

<?php require __DIR__.'/../layouts/header.php' ?>
    <div class="row g-5  mt-5">
        <?php if (empty($data)) : ?>
            <center>
                <p style="color: gray;"><i>Data not found</i></p>
            </center>
        <?php endif; ?>

        <?php foreach ($data as $record) : ?>
            <div class="col-md-6">
                <h2><a href="/view/consumers/view.php?id=<?=$record['id']?>">Сообщение: <?=$record['msg']?></a></h2>
                <p><?=$record['createdAt']?></p>
                <?php if ($record['code']) : ?>
                <p>Status Code: <?=$record['code']?></p>
                <pre>
                    <?=$record['content']?>
                </pre>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php require __DIR__.'/../layouts/footer.php' ?>
