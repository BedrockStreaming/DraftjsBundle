<?php

namespace M6Web\Bundle\DraftjsBundle\Tests\Fixtures\Renderer\Entity;

use M6Web\Bundle\DraftjsBundle\Model\DraftEntity;
use M6Web\Bundle\DraftjsBundle\Renderer\Entity\AbstractBlockEntityRenderer;

class AcmeBlockEntityRenderer extends AbstractBlockEntityRenderer
{
    /**
     * @param DraftEntity $entity

     * @return string
     */
    public function render(DraftEntity $entity)
    {
        $data = $entity->getData();

        return $this->templating->render('M6WebDraftjsBundle:Block:default.html.twig', [
            'className' => $this->getClassName(),
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
