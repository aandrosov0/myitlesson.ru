<?php 
namespace App\Entity;

use App;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

abstract class Entity {
	#[ORM\Id, ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	protected int $id;

    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    }

	public static function add(Entity $entity) {
        global $entityManager;

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public static function delete(Entity $entity) {
        global $entityManager;

        $entityManager->remove($entity);
        $entityManager->flush();
    }

    public static function find(int $id) {
        global $entityManager;
        return $entityManager->find('App\Entity\\' . App\getOnlyClassName(static::class), $id);
    }

	public static function flush() {
		global $entityManager;
		$entityManager->flush();
	}

    public static function getRepository() {
        global $entityManager;
        return $entityManager->getRepository('App\Entity\\' . App\getOnlyClassName(static::class));
    }

	public static function getIds(array|Collection $entities): array {
		$ids = [];

		foreach($entities as $entity) {
			$ids[] = $entity->getId();
		}

		return $ids;
	}

	public function toArray(): array {
		$classVars = get_class_vars($this::class);

		foreach(array_keys($classVars) as $key) {
			$var = $this->$key;

			if(is_a($var, Collection::class)) {
				$var = $this::getIds($var);
			}

			$classVars[$key] = $var;
		}

		return $classVars;
	}
}