<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=SITE_NAME?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="<?= asset("css/bootstrap.min.css") ?>" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="<?= asset("css/admin/style.css") ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="<?= route("admin.login"); ?>" class="navbar-brand"><?=SITE_NAME?></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <?php if(auth("admins")): ?>
                <li class="dropdown mr-2">
                    <a class="nav-item nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?=auth("admins")->name?></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-item nav-link" href="<?= route("admin.profile"); ?>"><?=auth("admins")->name?></a></li>
                        <li><a class="nav-item nav-link" href="<?= route("admin.logout"); ?>">Sair</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?=$this->viewTemplate($view, $data)?>
<div class="mb-5"></div>
<script src="<?=asset("js/jquery.min.js")?>"></script>
<script src="<?=asset("js/bootstrap.min.js")?>"></script>
<script src="<?=asset("js/jquery.mask.js")?>"></script>
<script src="<?=asset("js/ckeditor/ckeditor.js")?>"></script>
<script src="<?=asset("js/admin/script.js")?>"></script>
<script>var baseUrl = '<?=BASE_URL?>admin';</script>
</body>
</html>