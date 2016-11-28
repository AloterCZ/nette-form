<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Facades;

use Kdyby\Doctrine\EntityManager;
use Nette\Object;

/**
 * Základní fasáda pro všechny ostatní fasády aplikace.
 * Předává přístup pro práci s entitami.
 * @package App\Model\Facades
 */
abstract class BaseFacade extends Object
{
	/** @var EntityManager Manager pro práci s entitami. */
	protected $em;

	/**
	 * Konstruktor s injektovanou třídou pro práci s entitami.
	 * @param EntityManager $em automaticky injektovaná třída pro práci s entitami
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
}
