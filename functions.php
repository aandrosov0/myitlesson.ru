<?php 
namespace App;

function getOnlyClassName(string $className) {
	$className = explode('\\', $className);
    return end($className);
}
