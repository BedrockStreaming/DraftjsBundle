<?php

namespace M6Web\Bundle\DraftjsBundle\Renderer\Block;

use M6Web\Bundle\DraftjsBundle\Exception\DraftjsException;
use M6Web\Bundle\DraftjsBundle\Guesser\BlockEntityGuesser;
use M6Web\Bundle\DraftjsBundle\Model\ContentBlock;
use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;

/**
 * Class AtomicBlockRenderer
 *
 * @package M6Web\Bundle\DraftjsBundle\Renderer\Block
 */
class AtomicBlockRenderer extends AbstractBlockRenderer
{
    const TYPE = 'atomic';

    /**
     * @var BlockEntityGuesser
     */
    private $blockEntityGuesser;

    /**
     * @param BlockEntityGuesser $blockEntityGuesser
     */
    public function setBlockEntityGuesser(BlockEntityGuesser $blockEntityGuesser)
    {
        $this->blockEntityGuesser = $blockEntityGuesser;
    }

    /**
     * @param \ArrayIterator $iterator
     * @param array          $entities
     *
     * @return string
     *
     * @throws DraftjsException
     */
    public function render(\ArrayIterator &$iterator, array $entities)
    {
        $contentBlock = $iterator->current();
        $iterator->next();

        $content = $contentBlock->getText();

        $entity = $this->getEntity($contentBlock, $entities);

        if ($entity) {
            $renderer = $this->blockEntityGuesser->getRenderer($entity);

            if (!$renderer) {
                throw new DraftjsException(sprintf('Undefined block entity renderer for type "%s"', strtolower($entity->getType())));
            }

            $content = $renderer->render($entity);
        }

        return $this->templating->render('M6WebDraftjsBundle:Block:atomic.html.twig', [
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
        return self::TYPE === $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE;
    }

    /**
     * @param ContentBlock $contentBlock
     * @param array $entities
     *
     * @return null|DraftEntity
     */
    protected function getEntity(ContentBlock $contentBlock, array $entities)
    {
        $characterList = $contentBlock->getCharacterList();

        if (!isset($characterList[0])) {
            return null;
        }

        $characterMetadata = $characterList[0];
        $index = $characterMetadata->getEntityIndex();

        if (!isset($entities[$index])) {
            return null;
        }

        return $entities[$index];
    }
}
