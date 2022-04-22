<?php
    if(!$is_admin){
        die('<script>window.location.replace("?page=home")</script>');
    }
    $path=__DIR__ . '/../data/help/';
    ?>

<div class="container pb-5">
    <?php
    if(!isset($_GET['arc']) || is_array($_GET['arc'])){
        $files = array_diff(scandir($path), ['.', '..']);
        $articles=[];
        foreach ($files as $file) {
            $file_path=$path.$file;
            $data = file_get_contents($file_path);
            $stack = explode('//START_HERE//',$data);
            $header = json_decode($stack[0],true);
            if(empty($header)){ continue; }
            $arc_path=pathinfo($file_path, PATHINFO_FILENAME);
            $articles[$arc_path]['title'] = $header['title'];
            $articles[$arc_path]['date'] = $header['date'];
        }
    ?>
    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
    <div class="card">
        <div class="card-body">
        <div class="toolbar d-flex flex-row">
            <h3 class="mb-0 mr-2">Articles</h3>
            <a href="?page=b_arcticles&arc=" class="btn btn-primary">Add</a>
        </div>
            <table
            class="table"
            id="table"
            data-toggle="table"
            data-toolbar=".toolbar"
            data-sortable="true"
            data-show-columns="true"
            data-show-columns-toggle-all="true"
            data-pagination="true"
            data-search="true">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($articles as $k=>$article) {
                            ?>
                            <tr>
                                <td><?= $k?></td>
                                <td><?= $article['title']?></td>
                                <td><?= $article['date']?></td>
                                <td>
                                    <a href="?page=b_arcticles&arc=<?= $k?>" class="btn btn-primary btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_arcticle" data-arcticle="<?=$k?>"><i class="fal fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="remove_arcticle" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove arcticle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form onSubmit='ajax("b_arcticle_remove",$(this).serialize());return false;'>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Remove arcticle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#remove_arcticle').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var arcticle = button.data('arcticle')
            var modal = $(this)
            $('input[name$="name"]').val(arcticle)
        })
    </script>
        <?php
    } else { 
        $file_path=$path.$_GET['arc'].'.md';
        $data = file_get_contents($file_path);
        $stack = explode('//START_HERE//',$data);
        $header = json_decode($stack[0],true);
        
        $articles['title'] = "";
        $articles['date'] = "";
        $articles['icon'] = "";
        $articles['markdown'] = "";
        if(!empty($header)){
            $articles['title'] = $header['title'];
            $articles['date'] = $header['date'];
            $articles['icon'] = $header['icon'];
            $articles['markdown'] = $stack[1];
        }
        ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/editormd.min.css" />
        <script src="assets/editormd.min.js"></script>
        <script src="assets/plugins/en.js"></script>
        <div class="card">
            <form onSubmit='ajax("b_arcticle_update",$(this).serialize());return false;'>
                <div class="card-body">
                    <div class="form-group">
                        <label>Arcticle file name</label>
                        <input type="text" class="form-control" name="name" value="<?=$_GET['arc']?>">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" value="<?= $articles['title']?>">
                    </div>
                    <div class="form-group mb-0">
                        <label>Icon</label>
                        <input type="text" class="form-control" name="icon" value="<?= $articles['icon']?>">
                    </div>
                </div>
                <div id="editor">
                    <textarea style="display:none;" name="editor-markdown-doc"><?= $stack[1]?></textarea>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a class="btn btn-secondary mr-2" href="?page=b_arcticles">I want to go home</a>
                    <button class="btn btn-primary" type="submit">Edit</button>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(function() {
                var editor = editormd("editor", {
                    path   : "assets/editor_md_lib/",
                    width  : "100%",
                    height : "700px",
                    htmlDecode : true
                });
            });
        </script>
    <?php }
    ?>
    </div>
    