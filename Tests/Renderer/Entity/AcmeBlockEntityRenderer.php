<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Renderer\Entity;

use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Renderer\Entity\AbstractBlockEntityRenderer;

class AcmeBlockEntityRenderer extends AbstractBlockEntityRenderer
{
    CONST TYPE = 'acme';

    /**
     * @param DraftEntity $entity
     *
     * @return string
     */
    public function render(DraftEntity $entity)
    {
        $data = $entity->getData();

        return $this->templating->render('M6WebDraftjsBundle:Block:default.html.twig', [
            'classNames' => $this->getClassName(),
            'content' => $data['content'],
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
}
