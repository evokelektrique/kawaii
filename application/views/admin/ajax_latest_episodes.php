<table class="highlight">
    <thead>
        <tr>
            <th>شماره قسمت</th>
            <th>عکس</th>
            <th>عملیات</th>
        </tr>
    </thead>

    <tbody>
        {episodes}
        <tr>
            <td><a href="<?=base_url()?>public/img/episodes_images/{image_name}">لینک عکس</a></td>
            <td>
                <td>
                  <a href="<?=base_url('dashboard')?>/edit_chapter/<?=$article[0]->id?>/{chapter_id}/" class="latest-comments-action waves-effect waves-light btn-small grey white-text"><i class="material-icons right">book</i>چپتر</a>
                  <a href="<?=base_url('dashboard')?>/remove_chapter/<?=$article[0]->id?>/{id}" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>
                </td>
            </td>
        </tr>
        {/episodes}

    </tbody>
</table>