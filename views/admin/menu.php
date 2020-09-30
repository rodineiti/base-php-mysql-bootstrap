<div class="card">
    <div class="card-body">
        <div class="nav flex-column nav-pills" aria-orientation="vertical">
            <a class="nav-link border mb-1 <?=setMenuActive(["admin/home"])?>" href="<?= route("admin.home");?>">
                Home
            </a>
            <a class="nav-link border mb-1 <?=setMenuActive(["admin/users","admin/users/create","admin/users/edit"])?>"
               href="<?= route("admin.users.index");?>">
                Usuários
            </a>
        </div>
    </div>
</div>