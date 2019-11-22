<?php
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;
use Exception;

final class PostTable extends Table
{

    protected $table = "post";
    protected $class = Post::class;


    public function update(Post $post)
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name WHERE id = :id");
        $ok = $query->execute([
            "id" => $post->getId(),
            "name" => $post->getName()
        ]);
        if ($ok === false) {
            throw new Exception("La modification de l'article {$post->getId()} a échoué");
        }
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("La suppression de l'article $id a échoué");
        }
    }

    public function findPaginated()
    {
        $pagination = new PaginatedQuery(
            "SELECT count(id) FROM {$this->table}",
            "SELECT * FROM post ORDER BY created_at DESC",
            Post::class
        );
        $posts = $pagination->getItems();
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$pagination];
    }

    public function findPaginatedForCategory(int $categoryId)
    {
        $pagination = new PaginatedQuery(
            "SELECT count(category_id) FROM post_category pc
            WHERE pc.category_id = {$categoryId}",
            "SELECT post.* FROM {$this->table}
            INNER JOIN post_category pc ON pc.post_id = post.id
            WHERE pc.category_id = {$categoryId}",
            $this->class
        );
        $posts = $pagination->getItems();
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts,$pagination];
    }
}