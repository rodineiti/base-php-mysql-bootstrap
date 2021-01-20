<div class="row">
    <div class="col-md-12">
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