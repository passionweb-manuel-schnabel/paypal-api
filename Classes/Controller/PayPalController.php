<?php

namespace Passionweb\PayPalApi\Controller;


use Passionweb\PayPalApi\Service\PayPalService;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PayPalController extends ActionController
{
    public function __construct(
        protected PayPalService $paypalService,
        protected array $extConf,
        protected LoggerInterface $logger
    ) {
    }

    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function paymentAction(): ResponseInterface
    {
        if($this->request->hasArgument('buyer') && $this->request->hasArgument('price')) {
            $redirectUrl = $this->uriBuilder
                ->reset()
                ->setTargetPageUid($this->extConf['paypalRedirectPageUid'])
                ->setCreateAbsoluteUri(true)
                ->buildFrontendUri();
            $checkoutUrl = $this->paypalService->createPayment(
                $this->request->getArgument('buyer'),
                (float) $this->request->getArgument('price'),
                $redirectUrl
            );

            if (!empty($checkoutUrl)) {
                header('Location: ' . $checkoutUrl);
                exit;
            }
            $this->addFlashMessage('An unexpected error occured. Maybe you have not entered a val', 'Error during payment process', ContextualFeedbackSeverity::ERROR, false);
            return $this->getForwardResponse('index', $this->request->getArguments());
        }

        $this->addFlashMessage('.', 'Missing form data', ContextualFeedbackSeverity::ERROR, false);
        return $this->getForwardResponse('index', $this->request->getArguments());
    }

    public function paymentReturnAction(): ResponseInterface
    {
        // get transaction by paymentId (or other unique params) and do additional steps to handle the payment
        if(array_key_exists('paymentId', $this->request->getAttribute('routing')->getArguments())) {
            // payment succeeded
            if($this->request->getAttribute('routing')->getPageId() === (int)$this->extConf['paypalRedirectPageUid']) {
                $this->view->assign('success', true);
            }
            // payment failed
            else {
                $this->view->assign('failure', true);
            }
        }
        return $this->htmlResponse();
    }

    private function getForwardResponse(string $target, array $arguments = []): ForwardResponse
    {
        return (new ForwardResponse($target))
            ->withControllerName($this->request->getControllerName())
            ->withExtensionName($this->request->getControllerExtensionName())
            ->withArguments($arguments !== [] ? $arguments : $this->request->getArguments());
    }
}
