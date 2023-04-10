<?php
use App\Rendering\TemplateManager;

echo TemplateManager::render('header', ['pageTitle' => $pageTitle])->__toString();

echo <<<END
<script>
    document.getElementById('nav-home').classList.toggle('active');
</script>

<div class="h1 text-center text-success">
    <p>МОЙ IT УРОК</p>
</div>
END;

echo TemplateManager::render('footer')->__toString();