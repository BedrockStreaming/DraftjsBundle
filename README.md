<a href="#" id="status-image-popup" title="Latest push build on default branch: started" name="status-images" class="open-popup" data-ember-action="" data-ember-action-957="957">
    <img src="https://travis-ci.org/M6Web/DraftjsBundle.svg?branch=master" alt="build:started">
</a>

# DraftjsBundle

This Symfony bundle aims to convert Draft.js state into an equivalent PHP object model and providing necessary tools for rendering html.

##Installation

This library can be easily installed via composer

    composer require m6web/draftjs-bundle

Then register the bundle:

    # app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            new M6Web\Bundle\DraftjsBundle\M6WebDraftjsBundle(),
        );
    }

## YAML configuration reference

The configuration allow you to customize class names of blocks, inline styles and text alignment.

    m6_web_draftjs:
        class_names:
            blocks: # overriding class name of block
                atomic: 'custom-atomic'
                default: 'custom-paragraph'
                list: 'custom-list'
                heading: 'custom-heading'
            inline: # define inline styles class name
                <string>: <string>
            text_alignment: # define text alignment class name
                left: 'text-left'
                center: 'text-center'
                right: 'text-right'

The inline key allow any string to customize class name of inline style text, for example, 
if you want to define the class name of BOLD style, just define your configuration as below:

    m6_web_draftjs:
        class_names:
            inline:
                bold: 'u-bold'

## Objects model

DraftjsBundle follow the Draft.js object model.
 
    ContentState : Represent the document
    ContentBlock : Represent a single block
    DraftEntity : Represent a Draft.js entity
    CharacterMetadata : Represent a character with style and entity

## Draft.js supports

DraftjsBundle supports default Draft.js blocks type, has listed below:

    - atomic
    - unstyled
    - paragraph
    - unordered-list-item
    - ordered-list-item
    - header-one
    - header-two
    - header-three
    - header-four
    - header-five
    - header-six
    - blockquote

You can extends this list by implementing custom renderer has show in section [Renderers](#renderers)

## Exception

- DraftjsException

## ContentStateConverter

Convert a Draft.js state into php model object.

    m6_web_draftjs.content_state_converter
    
## HtmlBuilder

Build html from a ContentState object.

    m6_web_draftjs.html_builder

## HtmlRenderer

Object for converting and rendering a Draft.js state into html.

    m6_web_draftjs.html_renderer

## Renderers

In addition to the global HtmlRenderer, we provide the possibility to extends rendering engine by adding new renderers. 

We distinguished 3 types of renderer, depending on what you we want to customize, 

- [block](#adding-custom-block-renderer)
- [inline entity](#adding-custom-inline-entity-renderer) 
- [block entity](#adding-custom-block-entity-renderer)

There is also another renderer not listed here, the ContentRenderer responsible of rendering the HTML within a block from text and inline style.

The only things you have to do is to create a service then tagged it as expected.

### Adding custom block renderer

First define your service:

    # block entity renderers
    acme_demo.acme_block_renderer:
        class: Acme\Bundle\DemoBundle\Renderer\Block\AcmeBlockRenderer
        parent: m6_web_draft_js.abstract_block_renderer
        calls:
            - [setBlockClassName, ['block-acme']]
            - [setTemplate, ['AcmeDemoBundle:Block:acme.html.twig']]
        tags:
            - { name: draftjs.block_renderer, alias: draftjs_acme_block_renderer }

In order to be fully support by our rendering engine, you must tag your service with draftjs.block_renderer and you must extend AbstractBlockRenderer who implement the BlockRendererInterface interface.

Illustration with the AcmeBlockRenderer class:

    namespace Acme\Bundle\DemoBundle\Renderer\Block;
    
    use M6Web\Bundle\DraftjsBundle\Renderer\Block\AbstractBlockRenderer;
    use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;

    class AcmeBlockRenderer extends AbstractBlockRenderer
    {
        /**
         * @param \ArrayIterator $iterator
         * @param array          $entities
         *
         * @return string
         */
        public function render(\ArrayIterator &$iterator, array $entities)
        {
            // you have acces to the global iterator of ContentBlock
            // so just get current item by use curent()
            $contentBlock = $iterator->current();

            // if your renderer is handling the current ContentBlock
            // you must inform the iterator to move to the next entry for next iteration
            $iterator->next();

            // By extending the AbstractBlockRenderer, you can use the ContentRenderer who allow to render inline html 
            $content = $this->contentRenderer->render(
                $contentBlock->getText(),
                $contentBlock->getCharacterList(),
                $entities
            );

            if (!$this->template) {
                return $content;
            }

            // You also have access to the templating engine 
            return $this->templating->render($this->template, [
                'classNames' => $this->buildClassNames($contentBlock),
                'content' => $content,
            ]);
        }

        /**
         * @param string $type
         *
         * @return bool
         */
        public function supports($type)
        {
            return 'acme' === $type;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'acme';
        }
    }

### Adding custom inline entity renderer

Inline renderer are used for displaying entity information in block content has string.
  
    function createLink() {
      return DraftEntity.__create('LINK', 'MUTABLE', {uri: 'zombo.com'});
    }

First define your service:

    acme_demo.link_inline_entity_renderer:
        class: Acme\Bundle\DemoBundle\Renderer\Inline\LinkInlineEntityRenderer
        calls:
            - [setClassName, ['u-link']]
        tags:
            - { name: draftjs.inline_entity_renderer, alias: draftjs_link_inline_entity_renderer }

In order to be fully support by our rendering engine, you must tag your service with draftjs.inline_entity_renderer and you must extend AbstractInlineEntityRenderer who implement the InlineEntityRendererInterface interface.

Illustration with the LinkInlineEntityRenderer class:

    namespace Acme\Bundle\DemoBundle\Renderer\Inline;

    use M6Web\Bundle\DraftjsBundle\Renderer\Inline\AbstractInlineEntityRenderer;
    use M6Web\Bundle\DraftjsBundle\Renderer\Helper\InlineRendererHelperTrait;
    use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

    class LinkInlineEntityRenderer extends AbstractInlineEntityRenderer
    {
        use InlineRendererHelperTrait;

        /**
         * @param DraftEntity $entity
         *
         * @return string
         */
        public function openTag(DraftEntity $entity)
        {
            $data = $entity->getData();

            $attributes = [];

            if (isset($data['url'])) {
                $attributes['href'] = $data['url'];
            }

            if (isset($data['target']) && '_self' !== $data['target']) {
                $attributes['target'] = $data['target'];
            }

            if (isset($data['nofollow']) && true === $data['nofollow']) {
                $attributes['rel'] = 'nofollow';
            }

            if ($this->className) {
                $attributes['class'] = $this->className;
            }

            return $this->openNode(self::TAG_NAME, $attributes);
        }

        /**
         * @return string
         */
        public function closeTag()
        {
            return $this->closeNode(self::TAG_NAME);
        }

        /**
         * @param string $type
         *
         * @return bool
         */
        public function supports($type)
        {
            return 'link' === $type;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'link';
        }
    }

Notice the use of [InlineRendererHelperTrait](#inline-renderer-helper)

### Adding custom block entity renderer

Entity block renderer are used for displaying entity information as a block.
  
First define your service:

    acme_demo.acme_block_entity_renderer:
        class: Acme\Bundle\DemoBundle\Renderer\Entity\AcmeBlockEntityRenderer
        parent: m6_web_draft_js.abstract_block_entity_renderer
        calls:
            - [setClassName, ['block-entity-acme']]
            - [setTemplate, ['AcmeDemoBundle:Entity:acme.html.twig']]
        tags:
            - { name: draftjs.block_entity_renderer, alias: draftjs_acme_block_entity_renderer }

In order to be fully support by our rendering engine, you must tag your service with draftjs.block_entity_renderer and you must extend AbstractBlockEntityRenderer who implement the BlockEntityRendererInterface interface.

Illustration with the LinkInlineEntityRenderer class:

    namespace Acme\Bundle\DemoBundle\Renderer\Entity;

    use M6Web\Bundle\DraftjsBundle\Renderer\Entity\AbstractBlockEntityRenderer;
    use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

    class AcmeBlockEntityRenderer extends AbstractBlockEntityRenderer
    {
        /**
         * @param DraftEntity $entity
         *
         * @return string
         */
        public function render(DraftEntity $entity)
        {
            // generate content from the entity data
            $content = 'content of your acme block';
    
            return $this->templating->render($this->getTemplate(), [
                'className' => $this->getClassName(),
                'content' => $content,
            ]);
        }

        /**
         * @param string $type
         *
         * @return bool
         */
        public function supports($type)
        {
            return 'acme' === $type;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'acme';
        }
    }

## Helpers

### Inline renderer helper

    trait InlineRendererHelperTrait
    {
        /**
         * @param $tagName
         * @param array $attributes
         *
         * @return string
         */
        protected function openNode($tagName, array $attributes = [])
        {
            $strAttributes = $this->buildAttributes($attributes);
    
            return sprintf('<%s%s>', $tagName, $strAttributes);
        }

        /**
         * @param $tagName
         *
         * @return string
         */
        protected function closeNode($tagName)
        {
            return sprintf('</%s>', $tagName);
        }

        /**
         * Convert an array of attributes in string like http_build_query
         *
         * @param array $attributes
         *
         * @return string
         */
        protected function buildAttributes(array $attributes = [])
        {
            $strAttributes = array_map(function ($key) use ($attributes) {
                return sprintf('%s="%s"', $key, $attributes[$key]);
            }, array_keys(array_filter($attributes)));
    
            if (!$strAttributes) {
                return '';
            }
    
            return sprintf(' %s', implode(' ', $strAttributes));
        }
    }

### Block renderer helper

    trait BlockRendererHelperTrait
    {
        /**
         * Get text alignment from content block data
         *
         * @param ContentBlock $contentBlock
         *
         * @return null
         */
        protected function getTextAlignment(ContentBlock $contentBlock)
        {
            $data = $contentBlock->getData();
    
            if (isset($data['textAlignment'])) {
                return $data['textAlignment'];
            }
    
            return null;
        }

        /**
         * Build string class names from block and text alignment class names
         *
         * @param ContentBlock $contentBlock
         *
         * @return string
         */
        protected function buildClassNames(ContentBlock $contentBlock)
        {
            $textAlignment = $this->getTextAlignment($contentBlock);
    
            $classNames = [
                $this->getBlockClassName(),
            ];
    
            if ($textAlignment) {
                $classNames[] = $this->getTextAlignmentClassName($textAlignment);
            }
    
            return implode(' ', $classNames);
        }
    }