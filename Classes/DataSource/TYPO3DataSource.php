<?php
namespace Itanix\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TYPO3DataSource extends DataSource
{
    public function resolve(Request $request)
    {
        /** @var TypoScriptFrontendController $typoScriptFrontendController */
        $typoScriptFrontendController = $GLOBALS['TSFE'];

        $authUser = $typoScriptFrontendController->fe_user->user ?? [];

        if($authUser)
        {
            $request->setAuthenticatedUser($authUser['username'], $authUser['uid'], $authUser);
        }
    }
}