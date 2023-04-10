<?php

use App\Enums\Role;
use App\Rendering\TemplateManager;

$courses = $superUser->getAuthoredCourses();

echo TemplateManager::render('header');
?>

<script>
    $('#nav-courses').toggleClass('active');

    function addCourse(arrData) {
        addEntity({
            authorId: 0,
            title: 0,
            description: 0,
        }, arrData, 'course', data => {
            let cardBody = `
                <div class="card-body">
                    <a href="/course?id=${data.id}" class="d-block h-100 text-decoration-none text-success text-center">
                        <h2 class="card-title">${$('<p/>').html(data.title).text()}</h2>
                        <h5 class="card-subtitle">${$('<p/>').html(data.description).text()}</h5>
                    </a>
                </div>
                <div class="d-flex flex-row flex-nowrap w-100">
                    <a class="btn btn-danger w-100 rounded-0"  onclick="deleteCourse(${data.id})">Удалить</a>
                    <a class="btn btn-warning w-100 rounded-0" onclick="openForm('#add_form', 
                    [${data.id}, \`${data.title}\`,  \`${data.description}\`],
                    data => $(data.filter(el => el.id === 'header')).text('Редактировать курс'))">Редактировать</a><br>
                </div>`;

            $('#courses_view').append(`<div class="card bg-dark m-3" id="course_${data.id}">` + cardBody + `</div>`);
        }, 'Добавлен');
    }

    function deleteCourse(id) {
        deleteEntity('course', id, data => $('#course_' + data.id).remove(), 'Удален');
    }
</script>

<div class="block rounded h-100">
    <h1 class="h1 text-success">КУРСЫ</h1>
    <div class="d-flex flex-grow-1 justify-content-center flex-wrap w-100 align-items-center" id="courses_view">
        <?php foreach ($courses as $course) : ?>
            <?php
            $id = $course->getId();
            $title = htmlspecialchars($course->getTitle());
            $description = htmlspecialchars($course->getDescription());
            ?>
            <div class="card bg-dark m-3" id="course_<?= $id ?>">
                <div class="card-body w-100">
                    <a href="/course?id=<?= $id ?>" class="d-block w-100 h-100 text-decoration-none text-success text-center">
                        <h2 class="card-title"><?= $title ?></h2>
                        <h5 class="card-subtitle"><?= $course->getDescription() ?></h5>
                    </a>
                </div>
                <?php if($superUser->getRole()->value != Role::STUDENT->value): ?>
                    <div class="d-flex flex-row flex-nowrap w-100">
                        <a class="btn btn-danger w-100 rounded-0" onclick="deleteCourse(<?= $id ?>)">Удалить</a>
                        <a class="btn btn-warning w-100 rounded-0" onclick="openForm('#edit_form', [<?= $id ?>, <?= $superUser->getId() ?>, `<?= $title ?>`,  `<?= $description ?>`])">
                            Редактировать</a><br>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="w-100 d-flex justify-content-between p-2 mt-auto">
        <!-- <?php // if ($firstResult > 0) : ?>
            <a class="btn btn-success" href="/courses?firstResult=">
                <i class="bi bi-arrow-left"></i>
            </a>
        <?php // endif; ?> -->

        <!-- <?php // if (!empty($courses) && count($courses) >= $maxStep): ?>
            <a class="btn btn-success" href="/courses?firstResult=">
                <i class="bi bi-arrow-right"></i>
            </a>
        <?php // endif; ?> -->
    </div>
    <?php if($superUser->getRole()->value <= Role::TEACHER->value): ?>
        <div class="mt-auto">
            <button href="" class="btn btn-success w-100 mx-2" onclick="openForm(
                    $('#add_form'), 
                    [<?= $superUser->getId() ?>], data => $(data.filter(el => el.id == 'header')).text('Добавить курс'))">
                Добавить курс</a>
        </div>
        <form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="add_form">
            <div class="content d-flex flex-column">
                <h1 id="header">Добавить курс</h1>

                <input type="text" name="author_id" hidden>

                <label for="course_name" class="form-label mt-2">Название</label>
                <input type="text" name="course_name" id="course_name" placeholder="Введите название курса">

                <label for="course_description" class="form-label mt-2">Описание</label>
                <textarea name="course_description" id="course_description" rows="10" placeholder="Введите описание курса"></textarea>
            </div>
            <div class="actions mt-2">
                <button type="button" class="btn btn-success" onclick="addCourse(getUnpackedFormData('#add_form'))">Добавить</button>
                <button type="button" class="btn btn-danger" onclick="closeForm('#add_form')">Отменить</button>
            </div>
        </form>
        <form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="edit_form">
            <div class="content d-flex flex-column">
                <h1 id="header">Редактировать Курс</h1>

                <input type="text" name="id" hidden>
                <input type="text" name="author_id" hidden>

                <label for="course_name" class="form-label mt-2">Название</label>
                <input type="text" name="course_name" id="course_name" placeholder="Введите название курса">

                <label for="course_description" class="form-label mt-2">Описание</label>
                <textarea name="course_description" id="course_description" rows="10" placeholder="Введите описание курса"></textarea>
            </div>
            <div class="actions mt-2">
                <button type="button" class="btn btn-success" onclick="addCourse(getUnpackedFormData('#edit_form'))">Добавить</button>
                <button type="button" class="btn btn-danger" onclick="closeForm('#edit_form')">Отменить</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php echo TemplateManager::render('footer'); ?>