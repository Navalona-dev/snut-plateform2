<?php

namespace App\Test\Controller;

use App\Entity\Province;
use App\Repository\ProvinceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProvincesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProvinceRepository $repository;
    private string $path = '/province/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Province::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Province index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'province[NomFR]' => 'Testing',
            'province[NomMG]' => 'Testing',
            'province[ChefLieu]' => 'Testing',
            'province[CodeISO]' => 'Testing',
            'province[CodeFIPS]' => 'Testing',
            'province[Superficie]' => 'Testing',
            'province[Population]' => 'Testing',
        ]);

        self::assertResponseRedirects('/province/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Province();
        $fixture->setNomFR('My Title');
        $fixture->setNomMG('My Title');
        $fixture->setChefLieu('My Title');
        $fixture->setCodeISO('My Title');
        $fixture->setCodeFIPS('My Title');
        $fixture->setSuperficie('My Title');
        $fixture->setPopulation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Province');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Province();
        $fixture->setNomFR('My Title');
        $fixture->setNomMG('My Title');
        $fixture->setChefLieu('My Title');
        $fixture->setCodeISO('My Title');
        $fixture->setCodeFIPS('My Title');
        $fixture->setSuperficie('My Title');
        $fixture->setPopulation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'province[NomFR]' => 'Something New',
            'province[NomMG]' => 'Something New',
            'province[ChefLieu]' => 'Something New',
            'province[CodeISO]' => 'Something New',
            'province[CodeFIPS]' => 'Something New',
            'province[Superficie]' => 'Something New',
            'province[Population]' => 'Something New',
        ]);

        self::assertResponseRedirects('/province/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomFR());
        self::assertSame('Something New', $fixture[0]->getNomMG());
        self::assertSame('Something New', $fixture[0]->getChefLieu());
        self::assertSame('Something New', $fixture[0]->getCodeISO());
        self::assertSame('Something New', $fixture[0]->getCodeFIPS());
        self::assertSame('Something New', $fixture[0]->getSuperficie());
        self::assertSame('Something New', $fixture[0]->getPopulation());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Province();
        $fixture->setNomFR('My Title');
        $fixture->setNomMG('My Title');
        $fixture->setChefLieu('My Title');
        $fixture->setCodeISO('My Title');
        $fixture->setCodeFIPS('My Title');
        $fixture->setSuperficie('My Title');
        $fixture->setPopulation('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/province/');
    }
}
