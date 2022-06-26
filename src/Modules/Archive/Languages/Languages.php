<?php

namespace App\Modules\Languages;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Languages
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllLanguages(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT * FROM `languages`";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getOneLanguage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $sql = "SELECT * FROM `languages` WHERE `id`='$id' LIMIT 1";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllActivityLanguages(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT * FROM `languages` WHERE `activity`='1'";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postLanguage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $title = $data["title"];
        $code = $data["code"];
        $activity = $data["activity"];

        $sql = "INSERT INTO `languages`(`title`, `code`, `activity`) VALUES (:title, :code, :activity);";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':code', $code);
        $statement->bindParam(':activity', $activity);

        $result = $statement->execute();

        $response->getBody()->write(json_encode((int)$this->connection->lastInsertId()));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putLanguage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $title = $data["title"];
        $code = $data["code"];
        $activity = $data["activity"];

        $sql = "UPDATE `languages` SET `title`=:title,`code`=:code,`activity`=:activity WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':code', $code);
        $statement->bindParam(':activity', $activity);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteLanguage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM `languages` WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
