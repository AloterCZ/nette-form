<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Facades;

use App\Model\Entities\User;
use App\Model\Queries\UsersListQuery;
use Kdyby\Doctrine\ResultSet;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;

/**
 * Fasáda pro manipulaci s uživateli.
 * @package App\Model\Facades
 */
class UserFacade extends BaseFacade implements IAuthenticator
{
	/**
	 * Najde a vrátí uživatele podle jeho ID.
	 * @param int|NULL $id ID uživatele
	 * @return User|NULL vrátí entitu uživatele nebo NULL pokud uživatel nebyl nalezen
	 */
	public function getUser($id)
	{
		return isset($id) ? $this->em->find(User::class, $id) : NULL;
	}

	/**
	 * Uloží nově registrovaného uživatele do databáze.
	 * @param ArrayHash $values hodnoty pro nového uživatele
	 */
	public function registerUser($values)
	{
		$user = new User();
		$user->username = $values->username;
		$user->password = Passwords::hash($values->password);
		$user->email = $values->email;
		$user->name = $values->name;
		$user->role = User::ROLE_EDITOR;

                $this->em->persist($user);
		$this->em->flush();
	}

	/**
	 * Přihlásí uživatele do systému.
	 * @param array $credentials jméno a heslo uživatele
	 * @return Identity identitu přihlášeného uživatele pro další manipulaci
	 * @throws AuthenticationException Jestliže došlo k chybě při prihlašování, např. špatné heslo nebo uživatelské jméno.
	 */
	function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$user = $this->em->getRepository(User::class)->findOneBy(array("username" => $username));

		if (!isset($user) || !Passwords::verify($password, $user->password)) throw new AuthenticationException("youFilledBadNameOrPassword");

		// Zjistí, jestli heslo potřebuje rehashovat a případně to udělá.
		if (Passwords::needsRehash($user->password)) {
			$user->password = Passwords::hash($password);
			$this->em->flush();
		}

		return new Identity($user->id);
	}

	/**
	 * Vrací seznam uživatelů seřazaný podle jejich jmen ve vzestupném pořadí.
	 * @return ResultSet seznam uživatelů
	 */
	public function getUsersList()
	{
		$query = new UsersListQuery();
		$query->orderByName();
		return $this->em->getRepository(User::class)->fetch($query);
	}

	/**
	 * Přidá nového uživatele do databáze.
	 * @param ArrayHash $values hodnoty pro nového uživatele
	 */
	public function addUser($values)
	{
		$user = new User();
		$user->username = $values->username;
		$user->password = Passwords::hash($values->password);
		$user->email = $values->email;
		$user->name = $values->name;
		$user->role = $values->role;

		$this->em->persist($user);
		$this->em->flush();
	}

	/**
	 * Edituje uživatele.
	 * @param User      $user   uživatel
	 * @param ArrayHash $values editované informace o uživateli
	 */
	public function editUser(User $user, $values)
	{
		$role = $values->role;

		$user->username = $values->username;
		$user->email = $values->email;
		$user->name = $values->name;
		$user->role = $values->role;

		$this->em->flush();
	}
}
