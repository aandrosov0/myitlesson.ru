<?php

namespace App\Entity;

use App\Entity\CourseEntity;
use App\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'module')]
class ModuleEntity extends Entity {
	#[ORM\Column(type: 'string', length: 64)]
	protected string $title; 

	#[ORM\Column(type: 'string')]
	protected string $content;

	#[ORM\ManyToOne(targetEntity: CourseEntity::class, inversedBy: 'modules', cascade: ['persist'])]
	protected CourseEntity $course;

	#[ORM\OneToMany(targetEntity: LessonEntity::class, mappedBy: 'module', cascade: ['persist', 'remove'])]
	protected Collection $lessons;

	public static function new(string $title, string $content, CourseEntity $course) {
		return (new ModuleEntity())
			->setTitle($title)
			->setContent($content)
			->setCourse($course);
	}

	public function __construct() {
		$this->lessons = new ArrayCollection();
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

    public function getCourse() {
        return $this->course;
    }

    public function setCourse(?CourseEntity $course) {
        $this->course = $course;
		$course->getModules()->add($this);
        return $this;
    }

	public function removeCourse() {
		$this->course->getModules()->removeElement($this);
		$this->course = null;
		return $this;
	}

    public function getLessons() {
        return $this->lessons;
    }

    public function setLessons(Collection $lessons) {
        $this->lessons = $lessons;
        return $this;
    }
}