<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class UserEntity extends Entity {
	#[ORM\Column(type: 'string', length: 16)]
	protected string $username;

	#[ORM\Column(type: 'string', length: 32)]
	protected string $password;

	public static function new(string $username, string $password) {
		return (new UserEntity())
			->setUsername($username)
			->setPassword($password);
	}

	public function __construct() {
		
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
}