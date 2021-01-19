<div class="container profile">
    <h1>Meu perfil</h1>
    <div class="row">
        <div class="col-4 text-center">
            <?php if (auth()->avatar): ?>
                <img src="<?=media("avatars/".auth()->avatar);?>"
                     style="border-radius: 50%;border: 2px solid #ccc;"
                     alt="avatar" title="avatar" />
            <?php endif; ?>
        </div>
        <div class="col-8">
            <form method="POST" action="<?= route("profile") ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?=auth()->name?>" required />
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?=auth()->email?>" readonly disabled />
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar:</label>
                    <input type="file" name="avatar" id="avatar" />
                </div>
                <hr />
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" name="password" id="password" class="form-control" />
                </div>
                <input type="submit" value="Atualizar" class="btn btn-success btn-custom-success" />
            </form>
        </div>
    </div>
</div>