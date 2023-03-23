<?php

namespace App\Rendering;

use App\Rendering\HTMLMessage;

class TemplateManager {
	public static function render(string $templateName, array $parameters = []) {
		$fileName = DEFAULT_TEMPLATES_DIR . "/$templateName.php";

		if(!file_exists($fileName)) {
			return new HTMLMessage("Template \"$fileName\" not found!", 404);
		}

		extract($parameters, EXTR_OVERWRITE);
		
		ob_start();
		require $fileName;
		$content = ob_get_contents();
		ob_end_clean();

		return new HTMLMessage($content);
	}
}