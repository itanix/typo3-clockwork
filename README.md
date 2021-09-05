> This is an **unofficial TYPO3 Extension** for the **Clockwork development tool for PHP**. 
> For more information about Clockwork, visit the [Clockwork website](https://underground.works/clockwork).

### Installation

Install the Clockwork TYPO3 Extension via [Composer](https://getcomposer.org/).

```
$ composer require itanix/clockwork
```

### Enable TYPO3 FE Debug Mode

To enable data collection, set `$GLOBALS['TYPO3_CONF_VARS']['FE']['debug']` in the **LocalConfiguration.php**.

Once data collection is enabled, clockwork stores all requests as JSON files in the **/typo3temp/clockwork** folder by default.

Data is kept for 30 days by default and older data should be deleted automatically. It would probably still be a good idea to keep an eye on the folder, especially if disk space is tight.


#### Web Interface

The webinterface is not integrated yet.

#### Browser extension

The browser dev tools extension is available for Chrome and Firefox:

[Chrome Web Store](https://chrome.google.com/webstore/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp)

[Firefox Addons](https://addons.mozilla.org/en-US/firefox/addon/clockwork-dev-tools/)