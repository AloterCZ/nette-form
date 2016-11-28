<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Queries;

use App\Model\Entities\User;
use Doctrine\ORM\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

/**
 * Pomocná třída pro tvoření DQL dotazů nad uživateli.
 * @package App\Model\Queries
 */
class UsersListQuery extends QueryObject
{
	/** @var array Pole filtrů, které se aplikují na dotaz. */
	private $filters = array();

	/**
	 * Skládá DQL dotaz na uživatele.
	 * @param Queryable $repository databázový repozitář
	 * @return QueryBuilder objekt pro sestavování DQL dotazů s přednastaveným dotazem
	 * @inheritdoc
	 */
	public function doCreateQuery(Queryable $repository)
	{
		$qb = $repository->createQueryBuilder()
			->select("u")
			->from(User::class, "u");

		foreach ($this->filters as $filter) $filter($qb);
		return $qb;
	}

	/**
	 * Seřadí uživatele podle jejich jména v zadaném pořadí.
	 * @param string $order pořadí; ASC - vzestupně, DESC - sestupně
	 * @return $this pro možnost aplikovat další operace
	 */
	public function orderByName($order = "ASC")
	{
		if ($order !== "ASC" && $order !== "DESC") $order = "ASC";

		$this->filters[] = function (QueryBuilder $qb) use ($order) {
			$qb->addOrderBy("u.username", $order);
		};
		return $this;
	}
}
