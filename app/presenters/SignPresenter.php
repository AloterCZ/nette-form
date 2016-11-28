<?php

namespace App\Presenters;

use App\Forms\SignFormFactory;
use Nette\Application\UI\Form;

/**
 * Presenter pro registraci uživatelů.
 * @package App\Presenters
 */
class SignPresenter extends BasePresenter
{
	/**
	 * @var SignFormFactory Továrnička pro tvorbu registračního formuláře.
	 * @inject
	 */
	public $formFactory;

	/**
	 * Přesměruje uživatele na domovskou stránku, pokud je již přihlášen a pokusí se přejít na přihlašovací stránku.
	 */
	public function actionIn()
	{
		if ($this->getUser()->isLoggedIn()) $this->redirect("Homepage:default");
	}

	/**
	 * Vytváří a vrací komponentu registračního formuláře.
	 * @return Form komponenta registračního formuláře
	 */
	protected function createComponentSignUpForm()
	{
		$form = $this->formFactory->createSignUp();
		$form->onSuccess[] = function (Form $form) {
			$p = $form->getPresenter();
			$p->flashMessage($this->translator->translate("sign.youWereSignedUp"));
			$p->redirect("this");
		};
		return $form;
	}

	/**
	 * Vytváří a vrací komponentu přihlašovacího formuláře.
	 * @return Form komponenta přihlašovacího formuláře
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->formFactory->createSignIn();
		$form->onSuccess[] = function (Form $form) {
			$p = $form->getPresenter();
			$p->flashMessage($this->translator->translate("sign.youWereLoggedIn"));
			$p->redirect("this");
		};

		return $form;
	}


       
}