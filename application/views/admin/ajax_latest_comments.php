<table class="highlight">
    <thead>
        <tr>
            <th>اسم فصل</th>
            <th>نام کامل</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {comments}
        <tr>
            <td><a href="<?=base_url("dashboard/edit_comment")?>/{id}">{text}</a></td>
            <td><a href="<?=base_url("user")?>/{user_id}">{username}</a></td>
            <td>
                <a href="<?=base_url("dashboard/edit_comment")?>/{id}" class="latest-comments-action edit">ویرایش</a>
            </td>
        </tr>
        {/comments}
    </tbody>
</table>