<header class="window header">
    <div class="logo">
        User management
    </div>
    <div class="nav">
        <ul class="nav-item nav-links">
            <?php if (isAuth()): ?>
                <li class="nav-links-item">
                    <a class="nav-link" href="/logout">Выйти</a>
                </li>
                <?php if (isAdmin()): ?>
                    <li class="nav-links-item">
                        <a class="nav-link" href="/admin/dashboard">Панель</a>
                    </li>
                <?php endif ?>
            <?php else: ?>
                <li class="nav-links-item">
                    <a class="nav-link" href="/">Главная</a>
                </li>
                <li class="nav-links-item">
                    <a class="nav-link" href="/login">Вход</a>
                </li>
                <li class="nav-links-item">
                    <a class="nav-link" href="/register">Регистрация</a>
                </li>
            <?php endif ?>
        </ul>
        <div class="nav-item user">
            <?php if (isAuth()): ?>
                <div class="role">
                    <?= isAdmin() ? 'Администратор' : 'Пользователь' ?>
                </div>
                <a class="avatar" href="/profile">
                    <img src="<?= avatarSrc(user()['avatar']) ?>" alt="" class="avatar-image">
                </a>
            <?php endif ?>
        </div>
    </div>
</header>