<?php

defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'PayPalApi',
            'PaymentForm',
            [
                \Passionweb\PayPalApi\Controller\PayPalController::class => 'index,payment'
            ],
            // non-cacheable actions
            [
                \Passionweb\PayPalApi\Controller\PayPalController::class => 'index,payment'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'PayPalApi',
            'PaymentReturn',
            [
                \Passionweb\PayPalApi\Controller\PayPalController::class => 'paymentReturn'
            ],
            // non-cacheable actions
            [
                \Passionweb\PayPalApi\Controller\PayPalController::class => 'paymentReturn'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        paymentform {
                            iconIdentifier = paypal-payment
                            title = LLL:EXT:paypal_api/Resources/Private/Language/locallang_db.xlf:plugin_paypal_payment.name
                            description = LLL:EXT:paypal_api/Resources/Private/Language/locallang_db.xlf:plugin_paypal_payment.description
                            tt_content_defValues {
                                CType = list
                                list_type = paypalapi_paymentform
                            }
                        }
                        paymentreturn {
                            iconIdentifier = paypal-payment-return
                            title = LLL:EXT:paypal_api/Resources/Private/Language/locallang_db.xlf:plugin_paypal_paymentreturn.name
                            description = LLL:EXT:paypal_api/Resources/Private/Language/locallang_db.xlf:plugin_paypal_paymentreturn.description
                            tt_content_defValues {
                                CType = list
                                list_type = paypalapi_paymentreturn
                            }
                        }
                    }
                    show = *
                }
           }'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'paypal-payment',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:paypal_api/Resources/Public/Icons/Extension.png']
        );
        $iconRegistry->registerIcon(
            'paypal-payment-return',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:paypal_api/Resources/Public/Icons/Extension.png']
        );
    },
    'paypal_api'
);
