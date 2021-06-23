<table class="highlight">
    <thead>
        <tr>
            <th>نام</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {categories}
        <tr>
            <td>{cat_name}</td>
            <td>
                <a href="<?=base_url('dashboard')?>/edit_category/{cat_id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
            </td>
        </tr>
        {/categories}
    </tbody>
</table>