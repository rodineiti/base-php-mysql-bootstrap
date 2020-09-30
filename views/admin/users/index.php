<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?=$this->view("admin/menu");?>
        </div>
        <div class="col-md-9">
            <a href="<?= route("admin.users.create"); ?>" class="btn btn-primary mb-2">Adicionar</a>
            <?php if (isset($_GET["error"])): ?>
                <div class="alert alert-danger">
                    Opss. Ocorreu um erro no processamento, tente mais tarde.
                </div>
            <?php endif; ?>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Criado em</th>
                    <th scope="col">Opções</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <th scope="row"><?= $user->id ?></th>
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->created_at ?></td>
                        <td>
                            <a href="<?= route("admin.users.edit", ["id" => $user->id]); ?>" class="btn btn-info">Editar</a>
                            <a href="<?= route("admin.users.destroy", ["id" => $user->id]); ?>" class="btn btn-danger">Deletar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>