<?php

namespace App\Presenters;

use App\Forms\UserFormFactory;
use App\Model\Entities\User as UserEntity;
use Nette\Application\UI\Form;

/**
 * Presenter pro uživatele.
 * @package App\Presenters
 */
class UserPresenter extends BasePresenter
{
	/** @var UserFormFactory Továrnička na tvorbu formulářů pro uživatele. */
	private $formFactory;

	/** @var UserEntity Uživatel, kterého chceme editovat. */
	private $searchedUser;

	/**
	 * Konstruktor s injektovanou továrničkou na tvorbu formulářů pro uživatele.
	 * @param UserFormFactory $formFactory automaticky injektovaná továrnička na tvorbu formulářů pro uživatele
	 */
	public function __construct(UserFormFactory $formFactory)
	{
		parent::__construct();
		$this->formFactory = $formFactory;
	}

	/**
	 * Přesměruje uživatele na domovskou stránku pokud není přihlášen.
	 * Naplní formulář pro úpravu nastavení uživatele daty nastavení uživatele, která chceme upravit.
	 */
	public function actionSettings()
	{
		if (!$this->getUser()->isLoggedIn())
			$this->redirect("Homepage:default");

		$settings = $this->userEntity->settings;
		$this["updateSettingsForm"]->setDefaults(array(
			"description" => $settings->description
		));
	}

	/** Přesměruje uživatele na domovskou stránku pokud není přihlášen jako administrátor. */
	public function actionManage()
	{
		if (!$this->userEntity->isAdmin())
			$this->redirect("Homepage:default");
	}

	/** Přesměruje uživatele na domovskou stránku pokud není přihlášen jako administrátor. */
	public function actionAdd()
	{
		if (!$this->userEntity->isAdmin())
			$this->redirect("Homepage:default");
	}

	/**
	 * Přesměruje uživatele na domovskou stránku pokud není přihlášen jako administrátor.
	 * Naplní formulář pro editaci uživatele daty uživatele, kterého chceme editovat.
	 * @param int $id ID uživatele, kterého chceme editovat
	 */
	public function actionEdit($id)
	{
		if (!$this->userEntity->isAdmin())
			$this->redirect("Homepage:default");

		$this->searchedUser = $user = $this->userFacade->getUser($id);

		if (isset($user))
			$this["editUserForm"]->setDefaults(array(
				"userId" => $id,
				"username" => $user->username,
				"email" => $user->email,
				"name" => $user->name,
				"role" => $user->role,
				"isAdmin" => $user->isAdmin()
			));
	}

	/** Předá šabloně data o uživatelých. */
	public function renderManage()
	{
		$this->template->users = $this->userFacade->getUsersList();
	}

	/**
	 * Předá šabloně data o uživatele, od kterého chceme vidět jeho profil.
	 * @param int $id ID uživatele, od kterého chceme vidět jeho profil
	 */
	public function renderProfile($id)
	{
		$this->template->searchedUser = $this->userFacade->getUser($id);
	}

	/** Předá šabloně data o uživateli, kterého chceme editovat. */
	public function renderEdit()
	{
		$this->template->searchedUser = $this->searchedUser;
	}


	/**
	 * Vytváří a vrací komponentu formuláře pro přidání uživatele.
	 * @return Form komponenta formuláře pro přidání uživatele
	 */
	public function createComponentAddUserForm()
	{
		$form = $this->formFactory->createAddUser();
		$form->onSuccess[] = function () {
			$this->flashMessage($this->translator->translate("user.userWasAdded"));
			$this->redirect("this");
		};
		return $form;
	}

	/**
	 * Vytváří a vrací komponentu formuláře pro editaci uživatele.
	 * @return Form komponenta formuláře pro editaci uživatele
	 */
	public function createComponentEditUserForm()
	{
		$form = $this->formFactory->createEditUser();
		$form->onSuccess[] = function () {
			$this->flashMessage($this->translator->translate("user.userWasEdited"));
			$this->redirect("User:manage");
		};
		return $form;
	}
        
         public function createComponentAkceForm($param) {
             $form = new Form();   
                
             $form->addText('prijmeni', 'Přjmeni:*')
                     ->setRequired(TRUE);
                $form->addText('jmeno', 'Jmeno:*')
                     ->setRequired(TRUE);
                $form->addText('akce', 'Akce:*')
                     ->setRequired(TRUE);
                $typ = [
                    "exkurze" => "Exkurze",
                    "prednaska" => "přednáška",
                    "seminar" => "seminář",
                    "skoleni" => "školení",
                    "workshop" => "workshop",
                    "soutez" => "soutěž",
                    "sport" => "sportovní akce",
                    "jiny" => "jiný",
                ];
                $form->addSelect('typ', 'Typ akce:*', $typ)
                     ->setRequired(TRUE);
                $den = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
                $form->addSelect('den', 'Datum akce:*', $den)
                     ->setRequired(TRUE);
                $mesic = [
                    "1" => "Leden",
                    "2" => "Únor",
                    "3" => "Březen",
                    "4" => "Duben",
                    "5" => "Květen",
                    "6" => "Červen",
                    "7" => "Červenec",
                    "8" => "Srpen",
                    "9" => "Září",
                    "10" => "Říjen",
                    "11" => "Listopad",
                    "12" => "prosinec",
                    ];
                $form->addSelect('mesic','',$mesic)
                     ->setRequired(TRUE);
                $year = [2016,2017,2018,2019,2020,2021,2023,2024,2025,2026];
                $form->addSelect('rok','',$year)
                     ->setRequired(TRUE);
                $form->addTextArea('info','Informace: ')
                     ->setRequired(FALSE)
                     ->addRule(Form::MIN_LENGTH,'too long',10000);
                
                //Příloba<buttun>Soubor nevybrán
                //Zde můžete připojit soubor
                $form->addText('pocet_zaku', 'Počet žáků:');
                //Vyplňujte pouze v případě akce pořádané pro žáky
                $form->addText('poradatel', 'Pořadatel akce:');
                //Vyplňte především v případě účasti na školeních, seminářích a workshopech (např. KVIC, NIVD ...).
                /*DVPP:
                    ano
                    ne*/
                //Akce pořádaná v rámci Dalšího vzdělávání pedagogických pracovníků.

                /*Akreditace MŠMT:
                    ano
                    ne*/
                //Akce s akreditací Ministerstva školství a tělovýchovy

                //Počet výukových hodin:  

                //Vyplňte opět v případě účasti na školení, semináři, workshopu apod.

                





                return $form;
                }
}
