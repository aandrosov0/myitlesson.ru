<?php

use App\Enums\Role;
use App\Rendering\TemplateManager;

echo TemplateManager::render('header'); ?>

<link rel="stylesheet" href="/public/editor/css/froala_editor.min.css">
<link rel="stylesheet" href="/public/editor/css/plugins/char_counter.min.css">
<link rel="stylesheet" href="/public/editor/css/plugins/markdown.min.css">
<link rel="stylesheet" href="/public/editor/css/plugins/code_view.min.css">
<link rel="stylesheet" href="/public/editor/css/plugins/image.min.css">

<script src="/public/editor/js/froala_editor.min.js"></script>
<script src="/public/editor/js/plugins/align.min.js"></script>
<script src="/public/editor/js/plugins/link.min.js"></script>
<script src="/public/editor/js/plugins/char_counter.min.js"></script>
<script src="/public/editor/js/plugins/markdown.min.js"></script>
<script src="/public/editor/js/plugins/code_view.min.js"></script>
<script src="/public/editor/js/plugins/font_size.min.js"></script>
<script src="/public/editor/js/plugins/colors.min.js"></script>
<script src="/public/editor/js/plugins/font_family.min.js"></script>
<script src="/public/editor/js/plugins/paragraph_format.min.js"></script>
<script src="/public/editor/js/plugins/image.min.js"></script>

<script>
    function addModule(arrData) {
        addEntity({
            courseId: 0,
            title: 0,
            content: 0,
        }, arrData, 'module', data => {
            $('#modules_list').append(`<ul id="module_${data.id}"><span id="module_title_${data.id}" onclick="openModule(${data.id})">${data.title}</span></ul>`);
            openModule(data.id);
        }, "Модуль добавлен");
    }

    function openModule(id) {
        ajax('/module/get', {
            id: id
        }, 'POST', data => {
            $('#entity_content').html(`<h1>${data.title}</h1>${data.content}`);

            $('#entity_actions').html(`
                <button class="btn btn-success text-white" onclick="openForm('#add_lesson', [${id}])">Добавить урок</button>
                <button class="btn btn-warning text-white" onclick="openForm('#edit_module', [${id}, \`${data.title}\`, \`${data.content}\`])">Редактировать</button>
                <button class="btn btn-danger text-white" onclick="deleteModule(${id})">Удалить</button>
            `);
        });
    }

    function editModule(arrData) {
        editEntity('module', {
            id: 0,
            title: 0,
            content: 0,
        }, arrData, data => {
            $(`#module_title_${data.id}`).text(data.title);
            $('#entity_content').html(`<h1>${data.title}</h1>${data.content}`);
        }, 'Модуль отредактирован');
    }

    function deleteModule(id) {
        deleteEntity('module', id, data => {
            $(`#module_${id}`).remove()
            $(`#entity_content`).text("empty");
            $(`#entity_actions`).text("");
        }, 'Модуль удалён');
    }
</script>

<div class="d-flex flex-row justify-content-beetween w-100" style="min-height: 640px;">
    <div class="block h-auto p-2 border-success border rounded" style="width: 350px;">
        <?php if ($superUser->getRole()->value != Role::STUDENT->value) : ?>
            <button class="btn btn-success w-100 rounded-0 mt-auto" style="font-size: 14px;" onclick="openForm('#add_module', [<?= $course->getId() ?>])">Добавить модуль</button>
        <?php endif ?>
        <?php if ($superUser->getRole()->value != Role::STUDENT->value) : ?>
            <button class="mt-2 btn btn-success rounded-0 w-100" style="font-size: 14px;" onclick="openForm('#add_user', [<?= $course->getId() ?>])">Участники</button>
        <?php endif ?>
        <div class="pt-2 text-success h-100 d-flex flex-column w-100">
            <div id="modules_list" class="px-2 text-break">
                <?php foreach ($course->getModules() as $module) : ?>
                    <ul id="module_<?= $module->getId() ?>">
                        <span onclick="openModule(<?= $module->getId() ?>)" id="module_title_<?= $module->getId() ?>"><?= $module->getTitle() ?></span>
                        <?php foreach ($module->getLessons() as $lesson) : ?>
                            <li>
                                <ul class="mt-2" id="lesson_<?= $lesson->getId() ?>">
                                    <?php $tasks = $lesson->getTasks() ?>
                                    <span onclick="openLesson(<?= $lesson->getId() ?>)"><?= $lesson->getTitle() ?>
                                        <?php foreach ($tasks as $task) : ?>
                                            <li id="task_<?= $task->getId() ?>"><?= $task->getTitle() ?></li>
                                        <?php endforeach ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="block w-100 rounded" style="margin-left: 14px;">
        <div id="entity_content" class="w-100 mb-5">
            <h1><?= $course->getTitle(); ?></h1>
            <p><?= $course->getDescription(); ?></p>
        </div>
        <div id="entity_actions" class="w-100 mt-auto"></div>
    </div>
</div>
<form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="add_module">
    <div class="content d-flex flex-column">
        <h1 id="add_header">Добавить Модуль</h1>

        <input type="text" name="course_id" hidden>

        <label for="module_name" class="form-label mt-2">Название</label>
        <input type="text" name="module_name" id="module_name" placeholder="Введите название модуля">

        <label for="module_description" class="form-label mt-2">Описание</label>
        <textarea name="module_description" id="module_description" rows="10" placeholder="Введите описание модуля"></textarea>
    </div>
    <div class="actions mt-2">
        <button type="button" class="btn btn-success" onclick="addModule(getUnpackedFormData('#add_module'))">Добавить</button>
        <button type="button" class="btn btn-danger" onclick="closeForm('#add_module')">Отменить</button>
    </div>
</form>
<form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="edit_module">
    <div class="content d-flex flex-column">
        <h1 id="add_header">Добавить Модуль</h1>

        <input type="text" name="module_id" hidden>

        <label for="module_name" class="form-label mt-2">Название</label>
        <input type="text" name="module_name" id="module_name" placeholder="Введите название модуля">

        <label for="module_description" class="form-label mt-2">Описание</label>
        <textarea name="module_description" id="module_description" rows="10" placeholder="Введите описание модуля"></textarea>
    </div>
    <div class="actions mt-2">
        <button type="button" class="btn btn-success" onclick="editModule(getUnpackedFormData('#edit_module'))">Добавить</button>
        <button type="button" class="btn btn-danger" onclick="closeForm('#edit_module')">Отменить</button>
    </div>
</form>
<form method="post" class="bg-dark popup-form form rounded border border-success p-3" id="add_lesson">
    <div class="content d-flex flex-column">
        <h1 id="add_header">Добавить Урок</h1>

        <input type="text" name="module_id" hidden>

        <label for="module_name" class="form-label mt-2">Название</label>
        <input type="text" name="module_name" id="module_name" placeholder="Введите название модуля">
        
        <label for="module_description" class="form-label mt-2">Контент</label>
        <textarea name="module_description" id="module_description" rows="10"></textarea>
    </div>
    <div class="actions mt-2">
        <button type="button" class="btn btn-success" onclick="editModule(getUnpackedFormData('#add_lesson'))">Добавить</button>
        <button type="button" class="btn btn-danger" onclick="closeForm('#add_lesson')">Отменить</button>
    </div>
</form>

<script>
    new FroalaEditor('textarea', {
            toolbarButtons: {
                'moreText': {
                    'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting', 'colorsBack', 'paragraphFormat']
                },

                'moreRich': {
                    'buttons': ['insertImage', 'insertLink', 'align']
                },

                'moreMisc': {
                    'buttons': ['undo', 'redo', 'markdown', 'html']
                },
                
            },
            charCounterCount: false,
            language: 'ru',
            heightMax: 320,
        });
</script>

<?= TemplateManager::render('footer'); ?>