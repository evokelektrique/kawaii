<table class="highlight responsive-table">
    <thead>
        <tr>
            <th>نام کاربری</th>
            <th>ایمیل</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {users}
        <tr>
            <td><a href="<?=base_url('user/')?>{username}">{username}</a></td>
            <td>{email}</td>
            <td>
                <a href="<?=base_url('dashboard/edit_user/')?>{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
            </td>
        </tr>
        {/users}
    </tbody>
</table>