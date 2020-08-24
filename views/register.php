<div class="container register">
    <?php if (isset($_GET["error"]) && $_GET["error"] === "fields"): ?>
        <div class="alert alert-warning">
            Preencha todos os campos!
        </div>
    <?php endif; ?>
    <?php if (isset($_GET["error"]) && $_GET["error"] === "exists"): ?>
        <div class="alert alert-warning">
            Este usuário já existe! <a href="<?= BASE_URL ?>auth?login" class="alert-link">Faça o login agora</a>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success">
            <strong>Parabéns!</strong> Cadastrado com sucesso. <a href="<?= BASE_URL ?>auth?login" class="alert-link">Faça o login agora</a>
        </div>
    <?php endif; ?>
    <h1>Cadastre-se</h1>
    <form method="POST" action="<?= BASE_URL ?>auth/save">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <input type="submit" value="Cadastrar" class="btn btn-success btn-custom-success" />
    </form>

</div>