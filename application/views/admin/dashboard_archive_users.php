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
            <span class="right">آرشیو کاربران</span>
        </h5>

        <div class="row clear-both">
            <a href="<?= base_url('dashboard/create_user') ?>" class="right btn-small white-text cyan lighten-1 waves-effect"><i class="material-icons right">add</i>کاربر جدید</a>
            <div class="col m12 s12 card archive ">

                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>نام کاربری</th>
                            <th>ایمیل</th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        {users}
                        <tr>
                            <td><a href="#">{username}</a></td>
                            <td>{email}</td>
                            <td>{firstname}</td>
                            <td>{lastname}</td>
                            <td>
                                <a href="<?=base_url('dashboard/edit_user/')?>{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                            </td>
                        {/users}
                        </tr>
                    </tbody>
                </table>
            </div>
            {links}
        </div>
    </div>
</div>


{footer}