{header} <!-- Header -->
{sidebar} <!-- Sidebar -->
<!-- Content -->
<div class="content">

    <div class="c">

        <h5 class="content-title left">
            <?php foreach($urls as $url): ?>
                <a href="#!" class="breadcrumb"><?=ucwords($url)?></a>
            <?php endforeach; ?>
        </h5>





        <h5 class="content-title right"><i class="material-icons right">archive</i>
            <span class="right">آرشیو مطالب</span>
        </h5>

        <div class="row clear-both">
            <a href="<?=base_url('dashboard')?>/upload" class="right btn-small white-text cyan lighten-1 waves-effect"><i class="material-icons right">add</i>مطلب جدید</a>
            <div class="col m12 s12 card archive ">

                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>عکس</th>
                            <th>نام</th>
                            <th>تعداد بازدید</th>
                            <th>تعداد خوانده ها</th>
                            <th>تعداد گزارش ها</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        {entries}
                        <tr>
                            <td><img height='100' src="<?=base_url('public/img/post_images/{post_image}')?>"></td>
                            <td><a href="<?=base_url()?>{url_slug}/{id}">{name}</a></td>
                            <td>{view_count}</td>
                            <td>{read_count}</td>
                            <td>{alert_count}</td>
                            <td>
                                <a href="<?=base_url('dashboard')?>/edit_article/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                                <a href="<?=base_url('dashboard')?>/add_chapter/{id}" class="latest-comments-action waves-effect waves-light btn-small lime white-text"><i class="material-icons right">add_box</i>اضافه فصل</a>
                            </td>
                        </tr>
                        {/entries}
                    </tbody>
                </table>
                <?php if(empty($entries)): ?>
                    <p class="empty_entries">موردی یافت نشد</p>
                <?php endif; ?>
            </div>
            <?= $links ?>

        </div>
    </div>
</div>


{footer}