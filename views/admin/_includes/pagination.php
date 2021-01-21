<nav aria-label="navigate-pagination">
    <ul class="pagination">
        <?php for($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?=($page === $i) ? "active" : ""?>">
                <a class="page-link" href="<?= route($redirect); ?>?<?php
                $pageArray["page"] = $i;
                echo http_build_query($pageArray);
                ?>"><?=$i?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>