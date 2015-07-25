<?php

namespace MMDAXBase\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Description of AbstractService
 *
 * @author mateus
 */
abstract class AbstractServiceData
{

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var string
     */
    protected $entityNamespace;

    /**
     *
     * @var string
     */
    protected $entity;

    /**
     *
     * @var EntityRepository 
     */
    protected $repo;

    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository($this->entityNamespace);
    }

    /**
     * 
     * @param array $data
     * @return \MMDAXUser\Service\entity
     */
    public function insert(array $data)
    {
        $entity = new $this->entityNamespace($data);

        return $this->save($entity);
    }

    /**
     * 
     * @param array $data
     * @return \MMDAXUser\Service\entity
     */
    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entityNamespace, $data['id']);
        (new ClassMethods)->hydrate($data, $entity);

        return $this->save($entity);
    }

    public function delete(array $data)
    {
        $entity = $this->em->getReference($this->entityNamespace, $data['id']);

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
            return $entity;
        }

        return false;
    }

    protected function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

}
