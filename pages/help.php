<?php
$path=__DIR__ . '/../data/help/';
?>

<div class="container pb-5">
<?php
if(empty($_GET['arc'])){
$files = array_diff(scandir($path), ['.', '..']);
$articles=[];
foreach ($files as $file) {
    $file_path=$path.$file;
    $data = file_get_contents($file_path);
    $stack = explode('//START_HERE//',$data);
    $header = json_decode($stack[0],true);
    if(empty($header)){ continue; }
    $arc_path=pathinfo($file_path, PATHINFO_FILENAME);
    $articles[$arc_path]['key'] = $arc_path;
    $articles[$arc_path]['title'] = $header['title'];
    $articles[$arc_path]['icon'] = $header['icon'];
    $articles[$arc_path]['date'] = $header['date'];
}
function sortFunction( $a, $b ) {
    return strtotime($a["date"]) - strtotime($b["date"]);
}
usort($articles, "sortFunction");
$articles=array_reverse($articles);
?>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
<div class="card">
    <div class="card-body">
        <div class="toolbar">
            <h3>Helps</h3>
        </div>
        <table 
        class="table"
        id="table"
        data-toggle="table"
        data-toolbar=".toolbar"
        data-search="true"
        data-show-header="false">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
            <tbody>
                <?php
                    foreach ($articles as $k=>$article) {
                        ?>
                        <tr>
                            <td>
                                <a href="?page=help&arc=<?= $article['key']?>" class="text-reset text-decoration-none w-100">
                                    <div class="row w-100">
                                        <div class="col-3 text-center">
                                            <h1 class="mb-0"><i class="<?= $article['icon']?>"></i></h1>
                                        </div>
                                        <div class="col-9">
                                            <p class="mb-0"><?= $article['title']?></p>
                                            <small><?= $article['date']?></small>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
    <?php
} else { 
    $file_path=$path.$_GET['arc'].'.md';
    $data = file_get_contents($file_path);
    $stack = explode('//START_HERE//',$data);
    $header = json_decode($stack[0],true);
    if(empty($header)){
        die('<script>window.location.replace("?page=help")</script>');
    }
    $articles['title'] = $header['title'];
    $articles['date'] = $header['date'];
    $articles['icon'] = $header['icon'];
    $articles['markdown'] = $stack[1];
    require_once'../API/Parsedown.php';
    $Parsedown = new Parsedown();
    $Parsedown->setMarkupEscaped(false);
    ?>
<div class="card">
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?page=help">Help</a></li>
                <li class="breadcrumb-item active"><i class="<?= $articles['icon']?>"></i> <?= $articles['title']?></li>
            </ol>
        </nav>
        <h3 class="mb-0"><i class="<?= $articles['icon']?>"></i> <?= $articles['title']?></h3>
        <p><i class="fal fa-clock"></i> <?= $articles['date']?></p>
        <hr>
        <?= $Parsedown->text($articles['markdown']); ?>
    </div>
</div>
<?php }
?>
</div>