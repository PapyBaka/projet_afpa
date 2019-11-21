<?php
$categories = array_map(function ($category) use ($router) {
    $url = $router->url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]);
    $categoryName = htmlentities($category->getName());
    return "<a class='badge badge-info' href='{$url}'>{$categoryName}</a>";
},$post->getCategories());
?>
<div class="col-md-3 mt-2">
    <div class="card">
        <div class="card-body">
            <?= implode('',$categories) ?>
            <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>           
            <p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
            <p class="card-text"><?= $post->getExcerpt() ?></p>
            <a href="<?= $router->url('post',["id" => $post->getId(), "slug" => $post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
        </div>
    </div>
</div>