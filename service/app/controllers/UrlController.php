<?php declare(strict_types=1);

namespace app\controllers;

use app\Helper\Password;
use app\models\Shortener;
use Datto\JsonRpc\Exceptions\ApplicationException;
use Phalcon\Mvc\Controller;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Url;

class UrlController extends Controller
{
    public function shortenAction(): array
    {
        $validation = new Validation();
        $validation->add(
            'url',
            new Url(
                [
                    'message' => 'Invalid URL',
                ]
            )
        );

        $messages = $validation->validate($this->dispatcher->getParams());
//        $messages = $validation->validate(['url' => 'la-la']);

        if (count($messages)) {
            $msgArray = [];
            foreach ($messages as $m) {
                $msgArray[] = $m->getMessage();
            }
            throw new ApplicationException(join(', ', $msgArray), 1);
        }

        // processing request normally

        $shortener      = new Shortener();
        $hash           = $shortener->generateHash();
        $shortener->url = $this->dispatcher->getParam('url');

        if (!$shortener->save()) {
            throw new ApplicationException('Unknown database error, sorry...', 1);
        }

        return array(
            'hash' => $hash
        );
    }
}
