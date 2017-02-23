# taggy
Tag laravel Models using polymorphic relations.

Public API:

$model = Model::create([]);

$tags = Tag::create(["enter", "some", "tags"]);

$model->tag(["some", "tags"]);

$model->untag(["tags"]);

$model->retag(["some", "tags"]);

$model->removeAllTags();
