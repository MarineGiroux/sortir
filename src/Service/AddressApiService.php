<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressApiService
{

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function geocodeAddress(string $address): array
    {
        $response = $this->httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search', [
            'query' => [
                'q' => $address,
                // Autres paramètres d'authentification ou de configuration
            ],
        ]);

        $content = json_decode($response->getContent(), true);
        // Traitez la réponse de l'API et retournez les données de géocodage
        return $content;
    }

    // Ajoutez d'autres méthodes pour interagir avec l'API d'adresse, par exemple pour rechercher des adresses


}