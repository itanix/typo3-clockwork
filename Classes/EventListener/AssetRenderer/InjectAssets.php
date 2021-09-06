<?php
namespace Itanix\Clockwork\EventListener\AssetRenderer;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class InjectAssets
{
    protected array $sources = [
        'metrics' => 'https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/metrics.js',
        'toolbar' => 'https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js'
    ];

    public function __invoke(BeforeJavaScriptsRenderingEvent $event)
    {
        if (!$event->isInline() && !$event->isPriority())
        {
            /** @var ExtensionConfiguration $extensionConfiguration */
            $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

            $extConf = $extensionConfiguration->get('clockwork');

            if($extConf['toolbar'] ?? false)
            {
                $event->getAssetCollector()->addJavaScript('clockwork-browser-toolbar', $this->sources['toolbar'], []);
            }

            if($extConf['features']['performance']['client_metrics'] ?? false)
            {
                $event->getAssetCollector()->addJavaScript('clockwork-browser-metrics', $this->sources['metrics'], []);
            }
        }
    }
}