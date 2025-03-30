<?php

namespace App\Modules\Pages\Entities;

use App\Modules\Pages\Models\PagesModel;
use Bonfire\Core\Traits\HasMeta;
use CodeIgniter\Entity\Entity;

class Page extends Entity
{
    use HasMeta;

    protected string $configClass = 'Pages';

    /**
     * Returns the validation rules for all Page meta fields, if any.
     */
    public function validationRules(?string $prefix = null): array
    {
        return $this->metaValidationRules($prefix);
    }

    protected function setTitle(string $title): void
    {
        $this->attributes['title'] = $title;

        // Generate slug only if it is empty or deleted by the user
        if (!isset($this->attributes['slug']) || trim($this->attributes['slug']) === '') {
            $this->attributes['slug'] = $this->generateSlug($title);
        }
    }

    protected function setContent(string $content): void
    {
        $this->attributes['content'] = $content;

        // Ensure proper spacing in the content by replacing some tags with spaces
        $cleanedContent = str_replace(['</p>', '<br>', '<br/>', '</li>'], ' ', $content);

        helper('text');
        $this->attributes['excerpt'] = word_limiter(strip_tags($cleanedContent), 25);
    }

    protected function setSlug(?string $slug): void
    {
        // If the slug is null or empty, regenerate it from the title
        $this->attributes['slug'] = (is_null($slug) || trim($slug) === '') && isset($this->attributes['title'])
            ? $this->generateSlug($this->attributes['title'])
            : $slug;
    }

    // Ensure slug is checked during the fill process
    public function fill(?array $data = null): self
    {
        parent::fill($data);

        // Regenerate slug if it is empty after filling
        if (empty(trim($this->attributes['slug'] ?? '')) && isset($this->attributes['title'])) {
            $this->attributes['slug'] = $this->generateSlug($this->attributes['title']);
        }

        return $this;
    }

    private function generateSlug(string $title): string
    {
        $sep = '-';
        $slug = mb_url_title($title, $sep, true);

        // Ensure slug uniqueness (this logic may require database access in a model)
        $pagesModel = model(PagesModel::class);
        $query = $pagesModel->asArray()
            ->select('slug')
            ->like('slug', $slug, 'after');

        // Exclude the current page's slug if it exists
        if (isset($this->attributes['id'])) {
            $query->where('id !=', $this->attributes['id']);
        }

        $existingSlugs = $query->findAll();
        $flatList = array_column($existingSlugs, 'slug');
        $i = 0;

        while (in_array($slug . ($i > 0 ? $sep . $i : ''), $flatList)) {
            $i++;
        }

        return $i > 0 ? $slug . $sep . $i : $slug;
    }
}
