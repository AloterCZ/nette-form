<?php

namespace App\Forms;

use App\Model\Entities\User as UserEntity;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Application\UI\Form;
use Nette\InvalidArgumentException;
use Nette\Utils\ArrayHash;

/**
 * Továrnička pro tvorbu formulářů pro uživatele.
 * @package App\Forms
 */
class UserFormFactory extends BaseFormFactory
{
	/**
	 * Vytváří a vrací komponentu formuláře pro přidání uživatele.
	 * @return Form formulář pro přidání uživatele
	 */
	public function createAddUser()
	{
		$form = new Form();
		$form->addText("username", $this->translator->translate("form.user.add.username"))
			->setRequired($this->translator->translate("form.user.add.usernameNotFilled"))
			->addRule(Form::MAX_LENGTH, $this->translator->translate("form.user.add.usernameMayHaveMaxLetters"), UserEntity::MAX_NAME_LENGTH)
			->addRule(Form::PATTERN, $this->translator->translate("common.usernameBadFormat"), UserEntity::NAME_FORMAT);

		$form->addPassword("password", $this->translator->translate("form.user.add.password"))
			->setRequired($this->translator->translate("form.user.add.passwordNotFilled"));

		$form->addText("name", $this->translator->translate("form.user.add.name"))
			->setRequired($this->translator->translate("form.user.add.nameNotFilled"));

                $form->addText("email", $this->translator->translate("form.user.add.email"))
			->setRequired($this->translator->translate("form.user.add.emailNotFilled"))
			->addRule(Form::EMAIL, $this->translator->translate("form.user.add.emailBadFormat"));

		$form->addCheckbox("isAdmin", $this->translator->translate("form.user.add.admin"));

		$form->addSubmit("addUser", $this->translator->translate("form.user.add.add"));
		$form->onSuccess[] = array($this, "addUserSubmitted");

		return $form;
	}

	/**
	 * Funkce se vykoná při uspěšném odeslání formuláře pro přidání uživatele a pokusí se přidat nového uživatele.
	 * @param Form      $form   formulář pro přidání uživatele
	 * @param ArrayHash $values odeslané hodnoty formuláře
	 */
	public function addUserSubmitted(Form $form, $values)
	{
		try {
			$this->userFacade->addUser($values);
		} catch (UniqueConstraintViolationException $e) {
			$form->addError($this->translator->translate("form.user.add.userWithThisNameAlreadyExists"));
		}
	}

	/**
	 * Vytváří a vrací komponentu formuláře pro editaci uživatelů.
	 * @return Form formulář pro editaci uživatelů
	 */
	public function createEditUser()
	{
		$form = new Form();
		$form->addHidden("userId");

                $form->addText("username", $this->translator->translate("form.user.add.username"))
			->setRequired($this->translator->translate("form.user.add.usernameNotFilled"))
			->addRule(Form::MAX_LENGTH, $this->translator->translate("form.user.add.usernameMayHaveMaxLetters"), UserEntity::MAX_NAME_LENGTH)
			->addRule(Form::PATTERN, $this->translator->translate("common.usernameBadFormat"), UserEntity::NAME_FORMAT);

		$form->addText("email", $this->translator->translate("form.user.edit.email"))
			->setRequired($this->translator->translate("form.user.edit.emailNotFilled"))
			->addRule(Form::EMAIL, $this->translator->translate("form.user.edit.emailBadFormat"));

		$form->addText("name", $this->translator->translate("form.user.add.name"))
			->setRequired($this->translator->translate("form.user.add.nameNotFilled"));
                $roles = [
                    
                    'user' => 'user',
                    'administrator' => 'administrator',
                ];
                $form->addSelect('role', 'Role:')
                    ->setItems($roles, FALSE);
                //$form->addCheckbox("isAdmin", $this->translator->translate("form.user.edit.admin"));

		$form->addSubmit("editUser", $this->translator->translate("form.user.edit.edit"));
		$form->onSuccess[] = array($this, "editUserSubmitted");

		return $form;
	}


	/**
	 * Funkce se vykoná při uspěšném odeslání formuláře pro editaci uživatelů a pokusí se uložit editovaný profil uživatele.
	 * @param Form      $form   formulář pro editaci uživatelů
	 * @param ArrayHash $values odeslané hodnoty formuláře
	 */
	public function editUserSubmitted(Form $form, $values)
	{
		try {
			$user = $this->userFacade->getUser($values->userId);
			if (is_null($user)) throw new InvalidArgumentException("userDoesntExist");

			$this->userFacade->editUser($user, $values);
		} catch (InvalidArgumentException $e) {
			$form->addError($this->translator->translate("exception.{$e->getMessage()}"));
		}
	}
}
