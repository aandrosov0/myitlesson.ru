<?php

namespace App\Entity;

use App\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'course')]
class CourseEntity extends Entity {
	#[ORM\Column(type: 'text', length: 64)]
	protected string $title;

	#[ORM\Column(type: 'text', length: 512)]
	protected string $description;

	#[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'authoredCourses', cascade: ['persist'])]
	protected UserEntity $author;

	#[ORM\JoinTable(name: 'user_courses')]
	#[ORM\ManyToMany(targetEntity: UserEntity::class, mappedBy: 'courses', cascade: ['persist'])]
	protected Collection $users;

	#[ORM\OneToMany(targetEntity: ModuleEntity::class, mappedBy: 'course', cascade: ['persist', 'remove'], orphanRemoval: true)]
	protected Collection $modules;

	public static function new(string $title, string $description, UserEntity $author) {
		return (new CourseEntity())
			->setTitle($title)
			->setDescription($description)
			->setAuthor($author);
	}

	public function __construct() {
		$this->users = new ArrayCollection();
		$this->modules = new ArrayCollection();
	}

    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = $description;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor(?UserEntity $author) {
        $this->author = $author;
		$author->getAuthoredCourses()->add($this);
        return $this;
    }

	public function removeAuthor() {
		$this->author->getAuthoredCourses()->removeElement($this);
		$this->author = null;
		return $this;
	}

    public function getUsers() {
        return $this->users;
    }

    public function setUsers(Collection $users) {
        $this->users = $users;
        return $this;
    }

    public function getModules() {
        return $this->modules;
    }

    public function setModules(Collection $modules) {
        $this->modules = $modules;
        return $this;
    }

	public function addUser(UserEntity $user) {
		$this->users->add($user);
		$user->getCourses()->add($this);

		return $this;
	}

	public function removeUser(UserEntity $user) {
		$this->users->removeElement($user);
		$user->getCourses()->removeElement($this);

		return $this;
	}

	public function addModule(ModuleEntity $module) {
		$module->setCourse($this);
		return $this;
	}

	public function removeModule(ModuleEntity $module) {
		$module->removeCourse();
		return $this;
	}
}