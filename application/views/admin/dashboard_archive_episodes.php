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
            <a href="<?=base_url('dashboard')?>/add_episode/<?=$article[0]->id?>" class="right btn-small white-text cyan lighten-1 waves-effect"><i class="material-icons right">add</i>فصل جدید</a>
            <div class="col m12 s12 card archive ">
                <table class="highlight responsive-table">
                    <thead>
                        <tr>
                            <th>عکس</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($episodes as $episode): ?>
                        <tr>
                          <td><img height='100' src="<?=base_url('public/img/episodes_images/'.$episode->image_name)?>"></td>
                            <td>
                                <a href="<?=base_url('dashboard')?>/remove_episode/<?=$article[0]->id?>/<?=$episode->id?>/" class="latest-comments-action waves-effect waves-light btn-small red white-text"><i class="material-icons right">remove_circle</i>حذف</a>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
<?= $links ?>

        </div>
    </div>
</div>


{footer}