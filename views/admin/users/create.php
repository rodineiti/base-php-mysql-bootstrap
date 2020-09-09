<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?=$this->view("admin/menu");?>
        </div>
        <div class="col-md-9">
            <a href="<?= BASE_URL . "admin/users/index"; ?>" class="btn btn-info mb-2">Voltar</a>
            <h1>Adicionar usuário</h1>
            <form method="POST" action="<?= BASE_URL?>admin/users/store" enctype="multipart/form-data">
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
                <input type="submit" value="Criar" class="btn btn-primary" />
            </form>
        </div>
    </div>
</div>