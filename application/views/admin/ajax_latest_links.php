<table class="highlight">
    <thead>
        <tr>
            <th>نام</th>
            <th>آیکون</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {links}
        <tr>
            <td><a href="{address}">{name}</a></td>
            <td>{icon}</td>
            <td>
                <a href="<?=base_url('dashboard')?>/edit_link/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                <a href="<?=base_url('dashboard')?>/remove_link/{id}" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>            
            </td>
        </tr>
        {/links}
    </tbody>
</table>