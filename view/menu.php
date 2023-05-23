<div class="menu">

    <nav class="menu-list">
        <a class="menu-button" href="/">Главная</a>
        <?php if ($userName !== null): ?>
            <a class="menu-button" href="/?controller=tasks">Задачи</a>
        <?php endif; ?>
    </nav>


    <div class="user-panel">
        <?php if ($userName !== null) : ?>
            <div class="user-panel__user" title="<?=$userName?>">
                <?=$userName[0]?>
            </div>

            <a class="menu-button menu-button--danger" href="/?controller=security&action=logout">Выход</a>
        <?php else: ?>
            <a class="menu-button"  href="/?controller=security">Войти</a>
        <?php endif; ?>
    </div>

</div>

