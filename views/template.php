<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=SITE_NAME?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="<?=asset("css/bootstrap.min.css")?>" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="<?=asset("css/style.css")?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="<?= BASE_URL; ?>" class="navbar-brand"><?=SITE_NAME;?></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <?php if(auth()): ?>
                <li class="dropdown mr-2">
                    <a class="nav-item nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:;">
                        <?=auth()->name?></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-item nav-link" href="<?= BASE_URL ?>auth/profile"><?=auth()->name?></a></li>
                        <li><a class="nav-item nav-link" href="<?= BASE_URL ?>auth/logout">Sair</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a class="nav-item nav-link" href="<?php echo BASE_URL; ?>auth?login">Login</a></li>
                <li><a class="nav-item nav-link" href="<?php echo BASE_URL; ?>auth/register">Cadastro</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
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
    <div class="row">
        <div class="col-sm-12"><?php $this->viewTemplate($view, $data); ?></div>
    </div>
</div>
<script>var baseUrl = '<?=BASE_URL?>';</script>
<script src="<?=asset("js/jquery.min.js")?>"></script>
<script src="<?=asset("js/bootstrap.min.js")?>"></script>
<script src="<?=asset("js/jquery-ui.min.js")?>"></script>
<script src="<?=asset("js/script.js")?>"></script>
</body>
</html>