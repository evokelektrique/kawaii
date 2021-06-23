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
            <span class="right">آرشیو لینک ها</span>
        </h5>

        <div class="row clear-both">
            <a href="<?=base_url('dashboard')?>/add_link/" class="right btn-small white-text cyan lighten-1 waves-effect"><i class="material-icons right">add</i>لینک جدید</a>
            <div class="col m12 s12 card archive ">

                <table class="highlight responsive-table">
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
                            <td>{name}</td>
                            <td>{icon}</td>
                            <td>
                                <a href="<?=base_url('dashboard')?>/edit_link/{id}" class="latest-comments-action waves-effect waves-light btn-small orange white-text"><i class="material-icons right">settings_applications</i>ویرایش</a>
                                <a href="<?=base_url('dashboard')?>/remove_link/{id}" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>
                            </td>
                        </tr>
                        {/links}
                    </tbody>
                </table>
            </div>
            {pagination}
        </div>
    </div>
</div>


{footer}