<div class="container login">
    <h1>Login Admin</h1>
    <?=$this->view("admin/_includes/messages");?>
    <form method="POST" action="<?= BASE_URL?>admin/login">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <input type="submit" value="Fazer Login" class="btn btn-primary" />
    </form>

</div>