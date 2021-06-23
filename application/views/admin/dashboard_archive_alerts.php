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





        <h5 class="content-title right"><i class="material-icons right">error</i>
            <span class="right">گزارش ها</span>
        </h5>

        <div class="row clear-both">
            <div class="col m12 s12 card archive ">

                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>متن گزارش</th>
                            <th>لینک گزارش</th>
                            <th>نوع گزارش</th>
                            <th>تاریخ گزارش</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($alerts as $alert): ?>
                        <tr>
                            <td><?=$alert->text?></td>
                            <?php if($alert->type == "article"): ?>
                            <td><a href="<?=base_url('dashboard/edit_article/'.$alert->type_id)?>">لینک</a></td>
                            <td>پست</td>
                            <?php else: ?>
                            <td><a href="<?=base_url('dashboard/edit_comment/'.$alert->type_id)?>">لینک</a></td>
                            <td>نظر</td>
                            <?php endif; ?>
                            <td><?php 
$time = strtotime($alert->created_at );
$date = date('Y-m-d-H-i-s', $time);
$date_array = explode('-', $date);
$jalali_date = $this->jalalicalendar->gregorian_to_jalali($date_array[0],$date_array[1],$date_array[2]);
echo(implode('/', $jalali_date). ' ساعت ' . $date_array['3'] .':'.$date_array['4']);
                            ?></td>
                            <?php if($alert->type == "article"): ?>
                            <td>
<a href="<?=base_url('dashboard/edit_article/'.$alert->type_id)?>" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
<a href="<?=base_url('dashboard/remove_article/'.$alert->type_id)?>" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف مطلب </a>
                            <?php else: ?>
                            <td>
<a href="<?=base_url('dashboard/edit_comment/'.$alert->type_id)?>" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
<a href="<?=base_url('dashboard/remove_comment/'.$alert->type_id)?>" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف نظر </a>
                            <?php endif; ?>
<a href="<?=base_url('dashboard/alert/'.$alert->id)?>" class="latest-comments-action waves-effect waves-light btn-small grey white-text"><i class="material-icons right">settings_applications</i>مشاهده</a>

<a href="<?=base_url('dashboard/remove_alert/'.$alert->id)?>" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف گزارش</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            {links}
        </div>
    </div>
</div>


{footer}