<?php

namespace App\Finder; 
use Doctrine\ORM\EntityManagerInterface;

Class RegionFinder
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    } 

    public function findById($regionId)
    {
        $regionRepository = $this->em->getRepository('App\Entity\Region');
        $region = $regionRepository->find($regionId);

        return $region;
    }
}

?>