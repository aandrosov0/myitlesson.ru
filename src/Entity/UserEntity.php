<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class UserEntity extends Entity {
	#[ORM\Column(type: 'string', length: 16)]
	protected string $username;

	#[ORM\Column(type: 'string', length: 32)]
	protected string $password;

	#[ORM\JoinTable(name: 'user_courses'), ORM\JoinColumn(name: 'user_id'), ORM\InverseJoinColumn(name: 'course_id')]
	#[ORM\ManyToMany(targetEntity: CourseEntity::class, inversedBy: 'users', cascade: ['persist'])]
	protected Collection $courses;

	#[ORM\OneToMany(targetEntity: CourseEntity::class, mappedBy: 'author', cascade: ['persist', 'remove'], orphanRemoval: true)]
	protected Collection $authoredCourses;

	public static function new(string $username, string $password) {
		return (new UserEntity())
			->setUsername($username)
			->setPassword($password);
	}

	public function __construct() {
		$this->courses = new ArrayCollection();
		$this->authoredCourses = new ArrayCollection();
	}

    public function getUsername() {
        return $this->username;
    }

    public function setUsername(string $username) {
        $this->username = $username;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword(string $password) {
        $this->password = $password;
        return $this;
    }

    public function getCourses() {
        return $this->courses;
    }

    public function setCourses(Collection $courses) {
        $this->courses = $courses;
        return $this;
    }

    public function getAuthoredCourses() {
        return $this->authoredCourses;
    }

    public function setAuthoredCourses(Collection $authoredCourses) {
        $this->authoredCourses = $authoredCourses;
        return $this;
    }

	public function addCourse(CourseEntity $course) {
		$this->courses->add($course);
		$course->getUsers()->add($this);

		return $this;
	}

	public function removeCourse(CourseEntity $course) {
		$this->courses->removeElement($course);
		$course->getUsers()->removeElement($this);

		return $this;
	}

	public function addAuthoredCourse(CourseEntity $course) {
		$course->setAuthor($this);
		return $this;
	}

	public function removeAuthoredCourse(CourseEntity $course) {
		$course->removeAuthor();

		return $this;
	}
}