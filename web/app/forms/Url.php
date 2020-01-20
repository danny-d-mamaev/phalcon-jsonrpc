<?php declare(strict_types=1);

namespace app\forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

class Url extends \Phalcon\Forms\Form
{
    private function checkToken(): bool
    {
        return $this->security->checkToken('csrf', $this->getValue('csrf'));
    }

    public function getCsrf(): ?string
    {
        return $this->security->getToken();
    }

    public function initialize()
    {
        $url = new Text('url', [
            'required'    => 'required',
            'class'       => 'form-control',
            'placeholder' => 'http://....',
//            'value'       => 'https://jonnyblockchain.com/suite/profile/',
        ]);
        $this->add($url);
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new PresenceOf([
            'message' => 'Csrf is required'
        ]));
        $csrf->addValidator(
            new Identical([
                'value' => $this->checkToken(),
                'message' => 'CSRF validation failed'
            ])
        );
        $this->add($csrf);
    }
}
