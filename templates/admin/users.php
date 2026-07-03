<?php
partial('header', ['styles' => styles(['admin/main', 'admin/users'])]);
partial('navbar');
?>
<div class="container">
    <form method="GET" action="/admin/users" class="filters">
        <input name="name" type="text" value="<?= request()->getQueryString('name', '') ?>">
        <select name="is_admin">
            <option value="" <?= request()->getQueryInt('is_admin') === null ? 'selected' : ''?>>Все роли</option>
            <option value="0" <?= request()->getQueryInt('is_admin') === 0 ? 'selected' : ''?>>Пользователь</option>
            <option value="1" <?= request()->getQueryInt('is_admin') === 1 ? 'selected' : ''?>>Администратор</option>
        </select>
        <select name="is_blocked">
            <option value="" <?= request()->getQueryInt('is_blocked') === null ? 'selected' : ''?>>Все статусы</option>
            <option value="0" <?= request()->getQueryInt('is_blocked') === 0 ? 'selected' : ''?>>Незаблокированные</option>
            <option value="1" <?= request()->getQueryInt('is_blocked') === 1 ? 'selected' : ''?>>Заблокированные</option>
        </select>
        <button type="submit" class="btn white-btn">Поиск</button>
    </form>
    <div class="table-wrapper window">
        <table class="users-table">
            <thead>
            <tr>
                <th>Пользователь</th>
                <th>Телефон</th>
                <th>Почта</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($paginator->getItems() as $user): ?>
                    <tr>
                        <td>
                            <div class="user">
                                <div class="avatar user-avatar">
                                    <img src="<?= avatarSrc($user['avatar']) ?>" class="avatar-image">
                                </div>
                                <div class="user-name">
                                    <?= h($user['name']) ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?= h($user['phone']) ?>
                        </td>
                        <td>
                            <?= h($user['email']) ?>
                        </td>
                        <td>
                            <?= $user['is_admin'] ? 'Администратор' : 'Пользователь' ?>
                        </td>
                        <td>
                            <?= h($user['created_at']) ?>
                        </td>
                        <td class="actions">
                            <a class="btn dark-btn" href="">Открыть</a>
                            <a class="btn dark-btn" href="">Заблокировать</a>
                            <a class="btn dark-btn" href="">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        <?php partial('pagination', ['paginator' => $paginator]) ?>
    </div>
</div>
<?php partial('footer') ?>