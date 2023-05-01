<?php /** This file should be copied to themes/Admin/Search/pages.php */ ?>
<table class="table table-hover">
    <?= $this->setData(['headers' => [
        'id'            => lang('Pages.id'),
        'title'         => lang('Pages.title'),
        'excerpt'       => lang('Pages.excerpt'),
        'updated_at'    => lang('Pages.Updated')
    ]])->include('_table_head') ?>
    <tbody>
        <?php foreach ($rows as $page) : ?>
            <tr>
                <?= view('App\Modules\Pages\Views\_row_info', ['page' => $page]) ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
