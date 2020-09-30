<div class="container login">
    <h1>Login</h1>
    <form method="POST" action="<?= route("login");?>">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <input type="submit" value="Fazer Login" class="btn btn-success btn-custom-success" />
    </form>

</div>