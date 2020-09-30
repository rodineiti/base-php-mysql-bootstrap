<div class="jumbotron">
    <h1 class="display-4">BEM VINDO AO <?=SITE_NAME?></h1>
    <p class="lead">Este é um template base para suas aplicações frontend e backend utilizando PHP, MYSQL e Bootstrap no padrão MVC,
        utilizando as boas práticas para criar suas aplicações de maneira organizada.</p>
    <hr class="my-4">
    <p>Está é a home do template, onde poderá utilizar para ser a página principal do seu site.</p>
    <?php if (!auth()): ?>
        <p>Logo abaixo você poderá acessar a parte Administrativa do template.</p>
        <a class="btn btn-primary btn-lg" href="<?= route("admin.login"); ?>" role="button">Acessar Admin</a>
    <?php endif; ?>
</div>