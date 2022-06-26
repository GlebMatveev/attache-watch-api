<?php

namespace App\Modules\News;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class News
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllNews(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
            news.id,
            news.title_rus,
            news.title_eng,
            news.text_preview_rus,
            news.text_preview_eng,
            news.text_full_rus,
            news.text_full_eng,
            news.title_add_rus,
            news.title_add_eng,
            news.subtitle_add_rus,
            news.subtitle_add_eng,
            news.photo_main,
            news.photo_add,
            news.photo_detail,
            news.social,
            news.social_link_rus,
            news.social_link_eng,
            news.photo_banner,
            news.gallery_title_rus,
            news.gallery_title_eng,
            news.photo_gallery_1,
            news.photo_gallery_2,
            news.photo_gallery_3,
            news.photo_gallery_4,
            news.created_at,
            news.modified_at
            FROM news;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllNewsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "SELECT
            news.id,
            news.title_rus,
            news.title_eng,
            news.text_preview_rus,
            news.text_preview_eng,
            news.text_full_rus,
            news.text_full_eng,
            news.title_add_rus,
            news.title_add_eng,
            news.subtitle_add_rus,
            news.subtitle_add_eng,
            news.photo_main,
            news.photo_add,
            news.photo_detail,
            news.social,
            news.social_link_rus,
            news.social_link_eng,
            news.photo_banner,
            news.gallery_title_rus,
            news.gallery_title_eng,
            news.photo_gallery_1,
            news.photo_gallery_2,
            news.photo_gallery_3,
            news.photo_gallery_4,
            news.created_at,
            news.modified_at
            FROM news
            WHERE news.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllNewsByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT
            news.id,
            news.title_" . $lang . ", " .
            "news.text_preview_" . $lang . ", " .
            "news.text_full_" . $lang . ", " .
            "news.title_add_" . $lang . ", " .
            "news.subtitle_add_" . $lang . ", " .
            "news.photo_main, " .
            "news.photo_add, " .
            "news.photo_detail, " .
            "news.social, " .
            "news.social_link_" . $lang . ", " .
            "news.photo_banner, " .
            "news.gallery_title_" . $lang . ", " .
            "news.photo_gallery_1, " .
            "news.photo_gallery_2, " .
            "news.photo_gallery_3, " .
            "news.photo_gallery_4, " .
            "news.created_at, " .
            "news.modified_at " .
            "FROM news;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postNews(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        // $sql = "SELECT LAST_INSERT_ID(Id) FROM news ORDER BY LAST_INSERT_ID(Id) DESC LIMIT 1;";
        $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema = 'u1448502_attache' AND table_name = 'news';";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $id = (int)$result[0]["auto_increment"];
        $sql = 0;
        $statement = 0;
        $result = 0;


        $arrImg = explode(',', $data["imageMainUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        if (!is_dir($serverPath . '/assets/img/news/' . $id)) {
            mkdir($serverPath . '/assets/img/news/' . $id);
        }
        file_put_contents($dirSave, $base64_decode);
        $data["photo_main"] = $imagePath;

        $arrImg = explode(',', $data["imageAddUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_add"] = $imagePath;

        $arrImg = explode(',', $data["imageDetailUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_detail"] = $imagePath;

        $arrImg = explode(',', $data["imageBannerUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_banner"] = $imagePath;

        $arrImg = explode(',', $data["imageGallery1Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_1"] = $imagePath;

        $arrImg = explode(',', $data["imageGallery2Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_2"] = $imagePath;

        $arrImg = explode(',', $data["imageGallery3Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_3"] = $imagePath;

        $arrImg = explode(',', $data["imageGallery4Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_4"] = $imagePath;


        $sql = "INSERT INTO
        news(
            news.title_rus,
            news.title_eng,
            news.text_preview_rus,
            news.text_preview_eng,
            news.text_full_rus,
            news.text_full_eng,
            news.title_add_rus,
            news.title_add_eng,
            news.subtitle_add_rus,
            news.subtitle_add_eng,
            news.photo_main,
            news.photo_add,
            news.photo_detail,
            news.social,
            news.social_link_rus,
            news.social_link_eng,
            news.photo_banner,
            news.gallery_title_rus,
            news.gallery_title_eng,
            news.photo_gallery_1,
            news.photo_gallery_2,
            news.photo_gallery_3,
            news.photo_gallery_4
        ) VALUES (
            '$data[title_rus]',
            '$data[title_eng]',
            '$data[text_preview_rus]',
            '$data[text_preview_eng]',
            '$data[text_full_rus]',
            '$data[text_full_eng]',
            '$data[title_add_rus]',
            '$data[title_add_eng]',
            '$data[subtitle_add_rus]',
            '$data[subtitle_add_eng]',
            '$data[photo_main]',
            '$data[photo_add]',
            '$data[photo_detail]',
            '$data[social]',
            '$data[social_link_rus]',
            '$data[social_link_eng]',
            '$data[photo_banner]',
            '$data[gallery_title_rus]',
            '$data[gallery_title_eng]',
            '$data[photo_gallery_1]',
            '$data[photo_gallery_2]',
            '$data[photo_gallery_3]',
            '$data[photo_gallery_4]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteNewsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM news WHERE news.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putNewsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $str = substr($data["imageMainUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageMainUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_main"] = $imagePath;
        }

        $str = substr($data["imageAddUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageAddUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_add"] = $imagePath;
        }

        $str = substr($data["imageDetailUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageDetailUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_detail"] = $imagePath;
        }

        $str = substr($data["imageBannerUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageBannerUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_banner"] = $imagePath;
        }

        $str = substr($data["imageGallery1Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallery1Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_1"] = $imagePath;
        }

        $str = substr($data["imageGallery2Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallery2Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_2"] = $imagePath;
        }

        $str = substr($data["imageGallery3Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallery3Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_3"] = $imagePath;
        }

        $str = substr($data["imageGallery4Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallery4Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/news/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_4"] = $imagePath;
        }

        $sql = "UPDATE
            news
            SET 
            news.title_rus = '" . $data["title_rus"] . "', " .
            "news.title_eng = '" . $data["title_eng"] . "', " .
            "news.text_preview_rus = '" . $data["text_preview_rus"] . "', " .
            "news.text_preview_eng	 = '" . $data["text_preview_eng"] . "', " .
            "news.text_full_rus = '" . $data["text_full_rus"] . "', " .
            "news.text_full_eng = '" . $data["text_full_eng"] . "', " .
            "news.title_add_rus = '" . $data["title_add_rus"] . "', " .
            "news.title_add_eng = '" . $data["title_add_eng"] . "', " .
            "news.subtitle_add_rus = '" . $data["subtitle_add_rus"] . "', " .
            "news.subtitle_add_eng = '" . $data["subtitle_add_eng"] . "', " .
            "news.photo_main = '" . $data["photo_main"] . "', " .
            "news.photo_add = '" . $data["photo_add"] . "', " .
            "news.photo_detail = '" . $data["photo_detail"] . "', " .
            "news.social = '" . $data["social"] . "', " .
            "news.social_link_rus = '" . $data["social_link_rus"] . "', " .
            "news.social_link_eng = '" . $data["social_link_eng"] . "', " .
            "news.photo_banner = '" . $data["photo_banner"] . "', " .
            "news.gallery_title_rus = '" . $data["gallery_title_rus"] . "', " .
            "news.gallery_title_eng = '" . $data["gallery_title_eng"] . "', " .
            "news.photo_gallery_1 = '" . $data["photo_gallery_1"] . "', " .
            "news.photo_gallery_2 = '" . $data["photo_gallery_2"] . "', " .
            "news.photo_gallery_3 = '" . $data["photo_gallery_3"] . "', " .
            "news.photo_gallery_4 = '" . $data["photo_gallery_4"] . "' " .
            "WHERE
            news.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
