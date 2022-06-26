<?php

namespace App\Modules\Distributors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Distributors
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllDistributors(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
        distributors.id,
        distributors.country_rus,
        distributors.country_eng,
        distributors.city_rus,
        distributors.city_eng,
        distributors.address_rus,
        distributors.address_eng,
        distributors.phone_rus,
        distributors.phone_eng
        FROM distributors";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllDistributorsByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT
            distributors.id,
            distributors.country_" . $lang . ", " .
            "distributors.city_" . $lang . ", " .
            "distributors.address_" . $lang . ", " .
            "distributors.phone_" . $lang . " " .
            "FROM distributors;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postDistributors(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO
        distributors(
            distributors.country_rus,
            distributors.country_eng,
            distributors.city_rus,
            distributors.city_eng,
            distributors.address_rus,
            distributors.address_eng,
            distributors.phone_rus,
            distributors.phone_eng
        ) VALUES (
            '$data[country_rus]',
            '$data[country_eng]',
            '$data[city_rus]',
            '$data[city_eng]',
            '$data[address_rus]',
            '$data[address_eng]',
            '$data[phone_rus]',
            '$data[phone_eng]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteDistributorsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM distributors WHERE distributors.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putDistributorsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $sql = "UPDATE
            distributors
            SET 
            distributors.country_rus = '" . $data["country_rus"] . "', " .
            "distributors.country_eng = '" . $data["country_eng"] . "', " .
            "distributors.city_rus = '" . $data["city_rus"] . "', " .
            "distributors.city_eng = '" . $data["city_eng"] . "', " .
            "distributors.address_rus = '" . $data["address_rus"] . "', " .
            "distributors.address_eng = '" . $data["address_eng"] . "', " .
            "distributors.phone_rus = '" . $data["phone_rus"] . "', " .
            "distributors.phone_eng = '" . $data["phone_eng"] . "' " .
            "WHERE
            distributors.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
