<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Content;

use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Guesser\InlineEntityGuesser;
use M6Web\Bundle\DraftjsBundle\Model\CharacterMetadata;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Renderer\Helper\InlineRendererHelperTrait;
use M6Web\Bundle\DraftjsBundle\Renderer\Inline\InlineEntityRendererInterface;
use M6Web\Bundle\DraftjsBundle\Renderer\RendererInterface;

/**
 * class ContentRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Content
 */
class ContentRenderer implements RendererInterface
{
    use InlineRendererHelperTrait;

    const TAG_NAME = 'span';

    /**
     * @var InlineEntityGuesser
     */
    private $inlineEntityGuesser;

    /**
     * @var array
     */
    private $inlineClassNames;

    /**
     * ContentRenderer constructor.
     *
     * @param InlineEntityGuesser $inlineEntityGuesser
     */
    public function __construct(InlineEntityGuesser $inlineEntityGuesser)
    {
        $this->inlineEntityGuesser = $inlineEntityGuesser;
    }

    /**
     * @param string              $text
     * @param CharacterMetadata[] $characterList
     * @param array               $entities
     *
     * @return string
     *
     * @throws DraftjsException
     */
    public function render($text = '', array $characterList = [], array $entities = [])
    {
        if ('' === $text) {
            return '';
        }

        $output = '';
        $stack = [];
        $previousEntity = null;
        $tagEntityOpen = false;
        $chars = str_split($text);

        foreach ($chars as $index => $char) {
            $characterMetadata = $characterList[$index];

            $styles = $characterMetadata->getStyles();
            $entityIndex = $characterMetadata->getEntityIndex();

            $currentDepth = count($stack);

            if ($entityIndex !== $previousEntity || count($styles) !== $currentDepth) {
                // close text node
                if ($currentDepth > 0) {
                    $output .= $this->closeTag();
                }

                // close entity node
                if (is_null($entityIndex) && $entityIndex !== $previousEntity) {
                    $entity = $entities[$previousEntity];
                    $renderer = $this->getInlineEntityRenderer($entity);
                    $output .= $renderer->closeTag();

                    $tagEntityOpen = false;
                }

                // open entity node
                if (!is_null($entityIndex) && $entityIndex !== $previousEntity) {
                    if (!is_null($previousEntity)) {
                        $entity = $entities[$previousEntity];
                        $renderer = $this->getInlineEntityRenderer($entity);
                        $output .= $renderer->closeTag();

                        $tagEntityOpen = false;
                    }

                    $entity = $entities[$entityIndex];
                    $renderer = $this->getInlineEntityRenderer($entity);
                    $output .= $renderer->openTag($entity);

                    $tagEntityOpen = true;
                }

                // open text node
                if (count($styles) > 0) {
                    $output .= $this->openTag($styles);
                }
            }

            $output .= $char;

            $stack = $styles;
            $previousEntity = $entityIndex;
        }

        if ($tagEntityOpen && $previousEntity) {
            $entity = $entities[$previousEntity];
            $renderer = $this->getInlineEntityRenderer($entity);
            $output .= $renderer->closeTag();

            $tagEntityOpen = false;
        }

        return $output;
    }
    
    /**
     * @param array $classNames
     *
     * @return $this
     */
    public function setInlineClassNames(array $classNames = [])
    {
        $this->inlineClassNames = $classNames;

        return $this;
    }

    /**
     * Retrieve classNames
     *
     * @param array $styles
     *
     * @return array
     */
    private function getInlineClassNames(array $styles = [])
    {
        return array_map(function ($style) {
            $style = strtolower($style);

            // custom classNames from config
            if (isset($this->inlineClassNames[$style])) {
                return $this->inlineClassNames[$style];
            }

            return $style;
        }, $styles);
    }

    /**
     * @param DraftEntity $entity
     *
     * @return null|InlineEntityRendererInterface
     *
     * @throws DraftjsException
     */
    private function getInlineEntityRenderer(DraftEntity $entity)
    {
        $renderer = $this->inlineEntityGuesser->getRenderer($entity);

        if (!$renderer) {
            throw new DraftjsException(sprintf('Undefined inline entity renderer for type "%s"', $entity->getType()));
        }

        return $renderer;
    }

    /**
     * @param array $styles
     *
     * @return string
     */
    private function openTag(array $styles = [])
    {
        $classNames = $this->getInlineClassNames($styles);

        return $this->openNode(self::TAG_NAME, [
            'class' => implode(' ', $classNames),
        ]);
    }

    /**
     * @return string
     */
    private function closeTag()
    {
        return $this->closeNode(self::TAG_NAME);
    }
}
