<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * Doctrine entita pro tabulku uživatelů (user).
 * @package App\Model\Entities
 * @ORM\Entity
 */
class User extends BaseEntity
{
	// Pomocné konstanty pro náš model.

	/** Konstanty pro uživatelské role. */
	const 
              ROLE_EDITOR = "user",  
              ROLE_ADMIN = "administrator";

	/** Konstanty pro uživatelské jméno. */
	const MAX_NAME_LENGTH = 15,
	      NAME_FORMAT = "^[a-zA-Z0-9]*$";

	// Proměné reprezentující jednotlivé sloupce tabulky.

	/**
	 * Sloupec pro ID uživatele.
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * Sloupec pro uživatelské jméno.
	 * @ORM\Column(type="string")
	 */
	protected $username;

	/**
	 * Sloupec pro heslo.
	 * @ORM\Column(type="string")
	 */
	protected $password;

	/**
	 * Sloupec pro jméno.
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * Sloupec role uživatele. Význam hodnot viz. konstanty pro uživatelské role.
	 * @ORM\Column(type="string")
	 */
	protected $role;

	/**
	 * Sloupec pro email.
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/** Konstruktor s inicializací objektů pro vazby mezi entitami. */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Ověřuje, zda je uživatel v roli administrátora.
	 * @return bool vrací true, pokud je uživatel administrátor; jinak vrací false
	 */
	public function isAdmin()
	{
		return $this->role === self::ROLE_ADMIN;
	}

}
