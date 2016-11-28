<?php
namespace App;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

/**
 * Routovací továrnička.
 * Řídí routování v celé aplikaci.
 * @package App
 */
class RouterFactory
{

	/**
	 * Vytváří router pro aplikaci.
	 * @return IRouter výsledný router pro aplikaci
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route("[<locale=cs cs|en>/]<presenter>/<action>[/<id>]", "Homepage:default");
		return $router;
	}
}
