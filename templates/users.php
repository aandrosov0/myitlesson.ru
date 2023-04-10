<?php
use App\Entity\UserEntity;
use App\Enums\Role;
use App\Rendering\TemplateManager;

const OFFSET_STEP = 5;

$limit = $limit > -1 ? $limit : OFFSET_STEP;
$offset = $offset > -1 ? $offset : 0;

if(!isset($username)) {
    $users = UserEntity::getRepository()->findBy([], null, $limit, $offset);
} else {
    $users = UserEntity::getRepository()
        ->createQueryBuilder('u')
        ->select('u')
        ->where("u.username LIKE :username")
        ->setParameter('username', "$username%")
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()->getResult();
}

echo TemplateManager::render('header');
?>

<script>
    $('#nav-users').addClass('active');
    let roles = [<?= mb_strtoupper('\''.Role::ROOT->strval() . '\', \'' . Role::ADMIN->strval() . '\', \'' . Role::TEACHER->strval() . '\', \'' . Role::STUDENT->strval(), 'utf-8') . '\''?>]; 

    function addUser(arrData) {
        addEntity({
            username: 0,
            password: 0,
            role: 0
        }, arrData, 'user', data => {
            $('#users_not_found_message').remove();

            let tableRow = `
                <td><a class="link-warning" href="/user?id=${data.id}" id="username_${data.id}">${data.username}</a></td>
                <td class="text-success">${roles[data.role]}</td>
                <td>
                    <a class="btn btn-warning mt-2" onclick="openForm('#add_user', [${data.id}, '${data.username}', '', ${data.role}]);">
                        <i class="bi bi-person-fill-gear"></i>
                    </a>
                    <a class="btn btn-danger mt-2" onclick="deleteUser(${data.id})">
                        <i class="bi bi-person-fill-x"></i>
                    </a>
                </td>`;

            $('#users_table').append(`<tr class="align-middle text-break" id="user_${data.id}">` + tableRow + '</tr>');
        }, 'Добавлен');
    }

    function editUser(arrData) {
        editEntity('user', {
            id: 0,
            username: 0,
            password: 0,
            role: 0,
        }, arrData, data => {
            $(`#username_${data.id}`).text(data.username);
            $(`#userrole_${data.id}`).text(roles[data.role]);
        }, 'Отредактирован');
    }

    function deleteUser(id) {
        deleteEntity('user', id, data => $(`#user_${id}`).remove(), 'Удален');
    }
</script>

<div class="block rounded">
    <form method="get" id="find_user" action="/users" class="d-flex justify-content-center flex-wrap align-items-center w-100">
        <label for="username" class="m-2" style="font-size: 21px;"><b>Найти Пользователя</b></label>
        <input name="limit" type="hidden" value="<?= OFFSET_STEP ?>">
        <input name="offset" type="hidden" value="0">
        <input name="username" type="text" class="m-2 rounded" value="<?= isset($username) ? $username : ''?>">
        <input type="submit" value="Найти" class="btn btn-success m-2">
    </form>
    <hr class="text-black w-100">
    <?php if(count($users) == 0 || (count($users) == 1 && $users[0] == $superUser)) : ?>
        <h1 class="text-warning text-center" id="users_not_found_message">Не найдено пользователей</h1>
    <?php endif; ?>
    <table class="table table-dark text-white text-center" id="users_table">
        <thead>
            <tr>
                <th>Пользователь</th>
                <th>Роль</th>
                <?php if($superUser->getRole()->value < Role::STUDENT->value) : ?>
                    <th>Действия</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user) : ?>
                <?php if($superUser !== $user) : 
                    $role = $user->getRole();
                    $name = $user->getUsername();
                    $id = $user->getId(); ?>
                    <tr class="align-middle text-break" id="user_<?=$id?>">
                        <td><a class="link-warning" href="/user?id=<?=$id?>" id="username_<?=$id?>""><?= $name ?></a></td>
                        <td class="text-success" id="userrole_<?=$id?>"><?= mb_strtoupper($role->strval(), 'utf-8') ?></td>
                        <td>
                            <?php if($superUser->getRole()->value < $role->value) : ?>
                                <a class="btn btn-warning mt-2" onclick="openForm('#edit_user', [<?= "$id, '$name', '', $role->value" ?>]);">
                                    <i class="bi bi-person-fill-gear"></i>
                                </a>
                                <a class="btn btn-danger mt-2" onclick="deleteUser(<?= $id ?>)">
                                    <i class="bi bi-person-fill-x"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="w-100 d-flex justify-content-between p-2 mt-auto mb-2">
        <?php if($offset >= OFFSET_STEP) : ?>
            <a class="btn btn-success" href="/users?limit=<?= $limit?>&offset=<?= $offset - OFFSET_STEP ?><?= isset($username) ?  "&username=$username" : '' ?>">
                <i class="bi bi-arrow-left"></i>
            </a>
        <?php endif; ?>

        <?php if($superUser->getRole()->value <= Role::TEACHER->value) : ?>
            <a class="btn btn-success" onclick="openForm('#add_user', [0])">
                <i class="bi bi-person-fill-add"></i>
            </a>
        <?php endif; ?>

        <?php if(count($users) == OFFSET_STEP) : ?>
            <a class="btn btn-success" href="/users?limit=<?= $limit?>&offset=<?= $offset + OFFSET_STEP ?><?= isset($username) ?  "&username=$username" : '' ?>">
                <i class="bi bi-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
    <form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="add_user">
        <div class="content">
            <h1 id="add_user_header">Добавить пользователя</h1>

            <label for="username" class="form-label mt-2">Логин</label>
            <input type="text" name="username_input" id="username_input" placeholder="Введите логин" class="form-control" required>

            <label for="password" class="form-label mt-2">Пароль</label>
            <input type="password" name="password" id="password_input" placeholder="Введите пароль" class="form-control" required>

            <label for="role" class="form-label mt-2">Роль</label>
            <select name="role" id="user_role" class="form-select mt-2">
                <?php if($superUser->getRole()->value == Role::ROOT->value) : ?>
                    <option value="<?= Role::ROOT->value?>" id="root_role">РУТ</option>
                    <option value="<?= Role::ADMIN->value ?>" id="admin_role">АДМИН</option>
                    <option value="<?= Role::TEACHER->value ?>" id="teacher_role">УЧИТЕЛЬ</option>
                <?php endif; ?>
                <?php if($superUser->getRole()->value <= Role::TEACHER->value) : ?>
                    <option value="<?= Role::STUDENT->value ?>" id="student_role">СТУДЕНТ</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="actions">
            <button type="button" class="btn btn-success" onclick="addUser(getUnpackedFormData('#add_user'))">Добавить</button>
            <button type="button" class="btn btn-danger" onclick="closeForm('#add_user')">Отменить</button>
        </div>
    </form>
    <form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="edit_user">
        <div class="content">
            <h1 id="add_user_header">Редактировать пользователя</h1>

            <input type="text" name="id" hidden>

            <label for="username" class="form-label mt-2">Логин</label>
            <input type="text" name="username_input" id="username_input" placeholder="Введите логин" class="form-control" required>

            <label for="password" class="form-label mt-2">Пароль</label>
            <input type="password" name="password" id="password_input" placeholder="Введите пароль" class="form-control" required>

            <label for="role" class="form-label mt-2">Роль</label>
            <select name="role" id="user_role" class="form-select mt-2">
                <?php if($superUser->getRole()->value == Role::ROOT->value) : ?>
                    <option value="<?= Role::ROOT->value?>" id="root_role">РУТ</option>
                    <option value="<?= Role::ADMIN->value ?>" id="admin_role">АДМИН</option>
                    <option value="<?= Role::TEACHER->value ?>" id="teacher_role">УЧИТЕЛЬ</option>
                <?php endif; ?>
                <?php if($superUser->getRole()->value <= Role::TEACHER->value) : ?>
                    <option value="<?= Role::STUDENT->value ?>" id="student_role">СТУДЕНТ</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="actions">
            <button type="button" class="btn btn-success" onclick="editUser(getUnpackedFormData('#edit_user'))">Редактировать</button>
            <button type="button" class="btn btn-danger" onclick="closeForm('#edit_user')">Отменить</button>
        </div>
    </form>
</div>

<?php echo TemplateManager::render('footer'); ?>