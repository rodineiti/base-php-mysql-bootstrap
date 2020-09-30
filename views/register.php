<div class="container register">
    <h1>Cadastre-se</h1>
    <form method="POST" action="<?= route("register") ?>">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" value="<?=oldInput("name")?>" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" value="<?=oldInput("email")?>" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <input type="submit" value="Cadastrar" class="btn btn-success btn-custom-success" />
    </form>
</div>