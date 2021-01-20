<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <?=$this->view("admin/menu");?>
        </div>
        <div class="col-md-9">
            <?=$this->view("admin/_includes/messages");?>
            <a href="<?= route("admin.users.create"); ?>" class="btn btn-primary mb-2">Adicionar</a>
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
                <?php foreach ($list as $item): ?>
                    <tr>
                        <th scope="row"><?= $item->id ?></th>
                        <td><?= $item->name ?></td>
                        <td><?= $item->email ?></td>
                        <td><?= $item->created_at ?></td>
                        <td>
                            <a href="<?= route("admin.users.edit", ["id" => $item->id]); ?>" class="btn btn-info">Editar</a>
                            <a href="<?= route("admin.users.destroy", ["id" => $item->id]); ?>"
                               onclick="return confirm('Confirma a exclusão?');" class="btn btn-danger">Deletar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (isset($pages) && isset($page) && $pages > 1): ?>
                <?=$this->view("admin/_includes/pagination", [
                    "pages" => $pages, "page" => $page,"redirect" => "admin.users.index"
                ]);?>
            <?php endif; ?>
        </div>
    </div>
</div>