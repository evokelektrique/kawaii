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
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>متن</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($contacts as $contact): ?>
                        <tr>
                            <td><?=$contact->name?></td>
                            <td><?=$contact->email?></td>
                            <td><?=$contact->text?></td>

                            <td><?php 
$time = strtotime($contact->created_at );
$date = date('Y-m-d-H-i-s', $time);
$date_array = explode('-', $date);
$jalali_date = $this->jalalicalendar->gregorian_to_jalali($date_array[0],$date_array[1],$date_array[2]);
echo(implode('/', $jalali_date). ' ساعت ' . $date_array['3'] .':'.$date_array['4']);
                            ?></td>
                            <td>
                                <a href="<?=base_url('dashboard')?>/remove_contact/<?=$contact->id?>" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if(empty($contacts)): ?>
                    <p class="empty_entries">موردی یافت نشد</p>
                <?php endif; ?>
            </div>
            {links}
        </div>
    </div>
</div>


{footer}