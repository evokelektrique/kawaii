<table class="highlight">
    <thead>
        <tr>
            <th>اسم فصل</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {chapters}
        <tr>
            <td><a href="<?=base_url()?><?=$article[0]->id?>/<?=$article[0]->url_slug?>/chapter/{id}">{name}</a></td>
            <td>
                <a href="<?=base_url('dashboard')?>/edit_chapter/{article_id}/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                <a href="<?=base_url('dashboard')?>/add_episode/<?=$article[0]->id?>/{id}" class="latest-comments-action waves-effect waves-light btn-small lime white-text"><i class="material-icons right">add_box</i>اضافه قسمت</a>
            </td>
        </tr>
        {/chapters}

    </tbody>
</table>