<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Passionweb\PayPalApi\Service\PayPalService;
use Passionweb\PayPalApi\Controller\PayPalController;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->load('Passionweb\\PayPalApi\\', __DIR__ . '/../Classes/')
        ->exclude([
            __DIR__ . '/../Classes/Domain/Model',
        ]);

    $services->set('ExtConf.paypalData', 'array')
        ->factory([service(ExtensionConfiguration::class), 'get'])
        ->args(
            [
                'paypal_api',
            ]
        );

    $services->set(PayPalService::class)
        ->arg('$extConf', service('ExtConf.paypalData'))
        ->public();

    $services->set(PayPalController::class)
        ->arg('$extConf', service('ExtConf.paypalData'));
};
