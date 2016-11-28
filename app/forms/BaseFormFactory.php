<?php

namespace App\Forms;

use App\Model\Facades\UserFacade;
use Kdyby\Translation\Translator;
use Nette\Object;
use Nette\Security\User;

/**
 * Základní továrnička pro všechny ostatní továrničky aplikace.
 * Předává přístup pro ke společným prvkům.
 * @package App\Forms
 */
abstract class BaseFormFactory extends Object
{
	/** @var UserFacade Fasáda pro práci s uživateli. */
	protected $userFacade;

	/** @var Translator Překladač. */
	protected $translator;

	/** @var User Uživatel. */
	protected $user;

	/**
	 * Konstruktor s injektovanou fasádou pro práci s uživateli, překladačem a třídou uživatele.
	 * @param UserFacade $userFacade automaticky injektovaná fasáda pro práci s uživateli
	 * @param Translator $translator automaticky injektovaný překladač
	 * @param User $user             automaticky injektovaná třída uživatele
	 */
	public function __construct(UserFacade $userFacade, Translator $translator, User $user)
	{
		$this->userFacade = $userFacade;
		$this->translator = $translator;
		$this->user = $user;
	}
}
