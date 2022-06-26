<?php

namespace App\Modules\Properties;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Properties
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllProperties(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
        properties.id,
        properties.title_rus,
        properties.title_eng
        FROM properties";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllPropertiesByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT
            properties.id,
            properties.title_" . $lang . " " .
            "FROM properties;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postProperties(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO
        properties(
            properties.title_rus,
            properties.title_eng
        ) VALUES (
            '$data[title_rus]',
            '$data[title_eng]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deletePropertiesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM properties WHERE properties.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putPropertiesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $sql = "UPDATE
            properties
            SET 
            properties.title_rus = '" . $data["title_rus"] . "', " .
            "properties.title_eng = '" . $data["title_eng"] . "' " .
            "WHERE
            properties.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
