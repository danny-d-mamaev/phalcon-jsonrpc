<?php declare(strict_types=1);

namespace app\JsonRpc;

use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exceptions\ApplicationException;
use Phalcon\DispatcherInterface;
use function count;

class Api implements Evaluator
{
    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return DispatcherInterface
     */
    private function getDispatcher(): DispatcherInterface
    {
        return $this->dispatcher;
    }

    public function evaluate($method, $arguments): array
    {
        $this->validateControllerAndAction($method);
        $this->getDispatcher()->forward($this->getControllerAndAction($method));
        $this->getDispatcher()->setParams($arguments);
        try {
            $this->getDispatcher()->dispatch();
        } catch (\Throwable $e) {
            // @todo only for debug mode
            //error_log($e->__toString());
            throw new ApplicationException($e->getMessage(), $e->getCode());
        }
        return $this->getDispatcher()->getReturnedValue();
    }

    private function validateControllerAndAction(string $method): void
    {
        $methodAndController = explode('.', $method);
        if (count($methodAndController) !== 2) {
            throw new ApplicationException(
                'Invalid controller and action name, need use `controllerName.actionName`',
                1
            );
        }
    }

    private function getControllerAndAction(string $method): array
    {
        $methodAndController = explode('.', $method);
        return [
            'controller' => 'app\controllers\\' . ucfirst($methodAndController[0]),
            'action' => $methodAndController[1],
        ];
    }
}
