<?php namespace GamingPassion\Factories;

use GamingPassion\Database;
use GamingPassion\Mappers\PostMapper;
use GamingPassion\Models\Post;

class PostFactory
{
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getSinglePostFor($id)
    {
        $databaseResponse = $this->database->connection->query( "SELECT * FROM `posts` WHERE `public` = 1 AND `post_id` = {$id} ORDER BY `post_id` DESC LIMIT 1");

        $row = $databaseResponse->fetch_assoc();

        return PostMapper::map($row);
    }

    public function getAllPosts()
    {
        $response = [];

        $databaseResponse = $this->database->connection->query( "SELECT * FROM `posts` WHERE `public` = 1 ORDER BY `post_id` DESC LIMIT 0, 10");

        while($row = $databaseResponse->fetch_assoc())
        {
            $post = PostMapper::map($row);
            $post->content = preg_replace('/\s+?(\S+)?$/', '', substr($row['post_content'], 0, 255));

            array_push($response, $post);
        }

        return $response;
    }

    public function getAllPostsFor($category)
    {
        $response = [];

        $databaseResponse = $this->database->connection->query( "SELECT * FROM `posts` WHERE `post_category` = '{$category}' AND `public` = 1 ORDER BY `post_id` DESC LIMIT 0, 10");

        while($row = $databaseResponse->fetch_assoc())
        {
            $post = PostMapper::map($row);
            $post->content = preg_replace('/\s+?(\S+)?$/', '', substr($row['post_content'], 0, 255));

            array_push($response, $post);
        }

        return $response;
    }

    public function getAllArchivedPosts()
    {
        $response = [];

        $databaseResponse = $this->database->connection->query( "SELECT * FROM `posts` WHERE `public` = 1 ORDER BY `post_id` DESC LIMIT 10, 18446744073709551615");

        while($row = $databaseResponse->fetch_assoc())
        {
            $post = PostMapper::map($row);
            $post->content = preg_replace('/\s+?(\S+)?$/', '', substr($row['post_content'], 0, 255));

            array_push($response, $post);
        }

        return $response;
    }
}