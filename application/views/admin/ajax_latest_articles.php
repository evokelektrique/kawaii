<table class="highlight">
    <thead>
        <tr>
            <th>اسم فصل</th>
            <th>تعداد بازدید</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {entries}
        <tr>
            <td><a href="<?=base_url('{url_slug}/{id}')?>">{name}</a></td>
            <td>{view_count}</td>
            <td>
                <a href="<?=base_url('dashboard')?>/edit_article/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                <a href="<?=base_url('dashboard')?>/add_chapter/{id}" class="latest-comments-action waves-effect waves-light btn-small lime white-text"><i class="material-icons right">add_box</i>اضافه</a>
            </td>
        </tr>
        {/entries}
    </tbody>
</table>