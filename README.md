<a href="#" id="status-image-popup" title="Latest push build on default branch: started" name="status-images" class="open-popup" data-ember-action="" data-ember-action-957="957">
    <img src="https://travis-ci.org/M6Web/DraftjsBundle.svg?branch=master" alt="build:started">
</a>

# DraftjsBundle

Convert a rawState from Draft.js to a PHP object ContentState.
Render a ContentState to html.

##Installation

###For Symfony ###

````
{
    "require": {
        "m6web/draftjs-bundle": "~0.1",
    }
}
````

Register the bundle:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new M6Web\Bundle\DraftjsBundle\M6WebDraftjsBundle(),
    );
}
```

Install the bundle:

```
$ composer update m6web/draftjs-bundle
```

## Usage ##

You can configure custom class names and custom block rendering

```yaml
m6_web_draftjs:
    classNames:
        bold: str-strong                             # overriding default u-bold classname by str-strong classname 
        italic: str-italic                           # overriding default u-italic classname
        strikethrough: str-strikethrough             # overriding default u-strikethrough classname
    blocks:
        unstyled: MyBundle:MyDir:customBlock.html.twig # overriding default unstyled block by customBlock 
```   
