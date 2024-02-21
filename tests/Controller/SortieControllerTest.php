<?php

namespace App\Test\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SortieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/sorties/display/filter/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Sortie::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sortie index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'sortie[nomSortie]' => 'Testing',
            'sortie[dateHeureDebut]' => 'Testing',
            'sortie[duree]' => 'Testing',
            'sortie[dateLimiteInscription]' => 'Testing',
            'sortie[nbInscriptionMax]' => 'Testing',
            'sortie[infosSortie]' => 'Testing',
            'sortie[lieu]' => 'Testing',
            'sortie[etat]' => 'Testing',
            'sortie[site]' => 'Testing',
            'sortie[organisateur]' => 'Testing',
            'sortie[participantsInscrits]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Sortie();
        $fixture->setNomSortie('My Title');
        $fixture->setDateHeureDebut('My Title');
        $fixture->setDuree('My Title');
        $fixture->setDateLimiteInscription('My Title');
        $fixture->setNbInscriptionMax('My Title');
        $fixture->setInfosSortie('My Title');
        $fixture->setLieu('My Title');
        $fixture->setEtat('My Title');
        $fixture->setSite('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setParticipantsInscrits('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sortie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Sortie();
        $fixture->setNomSortie('Value');
        $fixture->setDateHeureDebut('Value');
        $fixture->setDuree('Value');
        $fixture->setDateLimiteInscription('Value');
        $fixture->setNbInscriptionMax('Value');
        $fixture->setInfosSortie('Value');
        $fixture->setLieu('Value');
        $fixture->setEtat('Value');
        $fixture->setSite('Value');
        $fixture->setOrganisateur('Value');
        $fixture->setParticipantsInscrits('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'sortie[nomSortie]' => 'Something New',
            'sortie[dateHeureDebut]' => 'Something New',
            'sortie[duree]' => 'Something New',
            'sortie[dateLimiteInscription]' => 'Something New',
            'sortie[nbInscriptionMax]' => 'Something New',
            'sortie[infosSortie]' => 'Something New',
            'sortie[lieu]' => 'Something New',
            'sortie[etat]' => 'Something New',
            'sortie[site]' => 'Something New',
            'sortie[organisateur]' => 'Something New',
            'sortie[participantsInscrits]' => 'Something New',
        ]);

        self::assertResponseRedirects('/sorties/display/filter/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomSortie());
        self::assertSame('Something New', $fixture[0]->getDateHeureDebut());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getDateLimiteInscription());
        self::assertSame('Something New', $fixture[0]->getNbInscriptionMax());
        self::assertSame('Something New', $fixture[0]->getInfosSortie());
        self::assertSame('Something New', $fixture[0]->getLieu());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getSite());
        self::assertSame('Something New', $fixture[0]->getOrganisateur());
        self::assertSame('Something New', $fixture[0]->getParticipantsInscrits());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Sortie();
        $fixture->setNomSortie('Value');
        $fixture->setDateHeureDebut('Value');
        $fixture->setDuree('Value');
        $fixture->setDateLimiteInscription('Value');
        $fixture->setNbInscriptionMax('Value');
        $fixture->setInfosSortie('Value');
        $fixture->setLieu('Value');
        $fixture->setEtat('Value');
        $fixture->setSite('Value');
        $fixture->setOrganisateur('Value');
        $fixture->setParticipantsInscrits('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/sorties/display/filter/');
        self::assertSame(0, $this->repository->count([]));
    }
}
