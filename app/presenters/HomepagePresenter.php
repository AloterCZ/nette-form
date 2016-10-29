<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Forms\Form;
use Nette\Application\UI;

class HomepagePresenter extends BasePresenter
{
        private $database;


        public function __construct(Nette\Database\Context $database) {
            
        }
        
        
        public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
        
        protected function createComponentRegistrationForm()
    {
        $login = new UI\Form;
        $login->addText('user', 'Name:');
        $login->addPassword('password', 'Password:');
        $login->addSubmit('login', 'Sign up');
        $login->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $login;
    }

    // called after form is successfully submitted
    public function registrationFormSucceeded(UI\Form $form, $values)
    {
        // ...
        $this->flashMessage('You have successfully signed up.');
        $this->redirect('Homepage:form');
    }
    
    protected function createComponentAkceForm($param) {
        $form = new UI\Form;
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
            "led" => "Leden",
            "uno" => "Únor",
            "bre" => "Březen",
            "dub" => "Duben",
            "kve" => "Květen",
            "cerven" => "Červen",
            "cervenec" => "Červenec",
            "srp" => "Srpen",
            "zar" => "Září",
            "rij" => "Říjen",
            "lis" => "Listopad",
            "pro" => "prosinec",
            ];
        $form->addSelect('mesic','',$mesic)
             ->setRequired(TRUE);
        $year = [16,17,18,19,20,21,23,24,25,26];
        $form->addSelect('rok','',$year)
             ->setRequired(TRUE);
        $form->addTextArea('info','Informace: ')
             ->setRequired(FALSE)
             ->addRule(Form::MIN_LENGTH,'too long',10000);
        //button priloha
        
        
        
        
        return $form;
        
    }
    

}
