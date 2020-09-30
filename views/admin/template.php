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
<div class="row">
    <div class="col-md-8 offset-3">
        <?php if ($errors = flashMessage("errors")): ?>
            <div class="alert alert-<?=$errors["status"]?>">
                <?php if (is_array($errors["messages"])): ?>
                    <ul>
                        <?php foreach ($errors["messages"] as $error): ?>
                            <li><?=$error?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (is_string($errors["messages"])): ?>
                    <?=$errors["messages"]?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?=$this->viewTemplate($view, $data)?>
<div class="mb-5"></div>
<script src="<?=asset("js/jquery.min.js")?>"></script>
<script src="<?=asset("js/bootstrap.min.js")?>"></script>
<script src="<?=asset("js/jquery.mask.js")?>"></script>
<script>var baseUrl = '<?=BASE_URL?>admin';</script>
<script src="<?=asset("js/admin/script.js")?>"></script>
</body>
</html>