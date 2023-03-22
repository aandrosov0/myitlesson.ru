<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'lesson')]
class LessonEntity extends Entity {
	#[ORM\Column(type: 'string', length: 64)]
	private string $title; 

	#[ORM\Column(type: 'string')]
	private string $content;

	#[ORM\ManyToOne(targetEntity: ModuleEntity::class, inversedBy: 'lessons', cascade: ['persist'])]
	private ModuleEntity $module;

	public static function new(string $title, string $content, ModuleEntity $module) {
		return (new LessonEntity())
			->setTitle($title)
			->setContent($content)
			->setModule($module);
	}

    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent(string $content) {
        $this->content = $content;
        return $this;
    }

    public function getModule() {
        return $this->module;
    }

    public function setModule(ModuleEntity $module) {
        $this->module = $module;
		$module->getLessons()->add($this);
        return $this;
    }
}