<?php

namespace Itanix\Clockwork\Log\Writer;

use Clockwork\Support\Vanilla\Clockwork;
use TYPO3\CMS\Core\Log\LogRecord;

class ClockworkLogWriter extends \TYPO3\CMS\Core\Log\Writer\AbstractWriter
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    public function writeLog(LogRecord $record)
    {
        $clockwork = Clockwork::instance();
        $clockwork && $clockwork->log($record->getLevel(), $record->getMessage(), $record->getData());
    }
}