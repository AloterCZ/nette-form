<?php

namespace App\Forms;

use App\Model\Entities\User as UserEntity;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 * Továrnička pro tvorbu registračního formuláře.
 * @package App\Forms
 */
class SignFormFactory extends BaseFormFactory
{
	/**
	 * Vytváří a vrací komponentu registračního formuláře.
	 * @return Form registrační formulář
	 */
	public function createSignUp()
	{
		$form = new Form;
		$form->addText("username", $this->translator->translate("form.sign.up.username"))
			->setRequired($this->translator->translate("form.sign.up.usernameNotFilled"))
			->addRule(Form::MAX_LENGTH, $this->translator->translate("form.sign.up.usernameMayHaveMaxLetters"), UserEntity::MAX_NAME_LENGTH)
			->addRule(Form::PATTERN, $this->translator->translate("common.usernameBadFormat"), UserEntity::NAME_FORMAT);

		$form->addPassword("password", $this->translator->translate("form.sign.up.password"))
			->setRequired($this->translator->translate("form.sign.up.passwordNotFilled"));

		$form->addText("name", $this->translator->translate("form.sign.up.name"))
			->setRequired($this->translator->translate("form.sign.up.nameNotFilled"));

		$form->addText("email", $this->translator->translate("form.sign.up.email"))
			->setRequired($this->translator->translate("form.sign.up.emailNotFilled"))
			->addRule(Form::EMAIL, $this->translator->translate("form.sign.up.emailBadFormat"));
                
		$form->addSubmit("signUp", $this->translator->translate("form.sign.up.signUp"));

		$form->onSuccess[] = array($this, "signUpSubmitted");

		return $form;
	}

	/**
	 * Funkce se vykoná při uspěšném odeslání formuláře pro registraci a pokusí se registrovat nového uživatele.
	 * @param Form $form        formulář pro registraci
	 * @param ArrayHash $values odeslané hodnoty formuláře
	 */
	public function signUpSubmitted($form, $values)
	{
		try {
			$this->userFacade->registerUser($values);
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		} catch (UniqueConstraintViolationException $e) {
			$form->addError($this->translator->translate("form.sign.up.userWithThisNameAlreadyExists"));
		}
	}

	/**
	 * Vytváří a vrací komponentu přihlašovacího formuláře.
	 * @return Form přihlašovací formulář
	 */
	public function createSignIn()
	{
		$form = new Form;
		$form->addText("username", $this->translator->translate("form.sign.in.username"))
			->setRequired($this->translator->translate("form.sign.in.usernameNotFilled"));

		$form->addPassword("password", $this->translator->translate("form.sign.in.password"))
			->setRequired($this->translator->translate("form.sign.in.passwordNotFilled"));

		$form->addCheckbox("remember", $this->translator->translate("form.sign.in.remember"));
		$form->addSubmit("signIn", $this->translator->translate("form.sign.in.signIn"));

		$form->onSuccess[] = array($this, "signInSubmitted");

		return $form;
	}

	/**
	 * Funkce se vykoná při uspěšném odeslání formuláře pro přihlášení a pokusí se přihlásit uživatele.
	 * @param Form $form        formulář pro přihlíšení
	 * @param ArrayHash $values odeslané hodnoty formuláře
	 */
	public function signInSubmitted($form, $values)
	{
		try {
			$this->user->login($values->username, $values->password);
			if ($values->remember) {
				$this->user->setExpiration("14 days", FALSE);
			} else {
				$this->user->setExpiration("20 minutes", TRUE);
			}
		} catch (AuthenticationException $e) {
			$form->addError($this->translator->translate("exception.{$e->getMessage()}"));
		}
	}
}
