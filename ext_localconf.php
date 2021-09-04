<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(static function () {

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'][\TYPO3\CMS\Core\Log\LogLevel::DEBUG] = [
        \Itanix\Clockwork\Log\Writer\ClockworkLogWriter::class => []
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Mvc\Dispatcher::class] = [
        'className' => \Itanix\Clockwork\Mvc\Dispatcher::class
    ];

});