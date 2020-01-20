<?php declare(strict_types=1);

namespace app\controllers;

use app\forms\Url as FormUrl;
use Datto\JsonRpc\Client;
use Phalcon\Http\Client\Request as ClientRequest;
use Phalcon\Http\Client\Response as ClientResponse;
use Phalcon\Http\RequestInterface;
use Phalcon\Translate\AdapterInterface as Translate;
use function array_key_exists;
use function is_array;

class IndexController extends \Phalcon\Mvc\Controller
{
    /**
     * displays the form
     */
    public function indexAction()
    {
        $this->view->form = $this->getDI()->get(FormUrl::class);
        return $this->view->render('index', 'index');
    }

    /**
     * this handles POST query passing results to the index view
     */
    public function generateAction()
    {
        // preparing client body

        $client = new Client();
        $client->query(
            1,
            'url.shorten',
            [
                'url' => $this->request->getPost('url'),
            ]
        );

        $body = $client->encode();

        // sending request

        $provider = $this->getDI()->get(ClientRequest::class);
        $response = $provider->post(
            $this->getDI()->getConfig()['SERVICE_CONTAINER_URL'],
            $body
        );

        $this->view->request  = $body;
        $this->view->response = $response->body;
        $responseArr          = json_decode($this->view->response, true);

//        print_r(json_decode($body));
//        print_r(json_decode($this->view->response)); die();
//        $this->view->response

        $this->view->url       = json_decode($body, true)['params']['url'];
        $this->view->shortened = isset($responseArr['result']) && isset($responseArr['result']['hash']) ? $this->getDI()->getConfig()['SERVICE_CONTAINER_DOMAIN'] . $responseArr['result']['hash'] : false;
        $this->view->message   = isset($responseArr['error']) && $responseArr['error']['message'] ? $responseArr['error']['message'] : false;

//        print_r($this->view->shortened); die();

        return $this->indexAction();
    }
}
