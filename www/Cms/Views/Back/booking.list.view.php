<div class="row" >
    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
            <div class="col-inner">
                <div class="pageTitle">
                    <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                    <?php if(isset($createButton)): ?>
                        <a href="<?= $createButton['link']?>"><button class="cta-green"><?=$createButton['label']?></button></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <a href="<?= \App\Core\Helpers::renderCMSLink( "admin/booking/edit/settings", $this->site)?>"><button>Modify the settings</button></a>
    <a href="<?= \App\Core\Helpers::renderCMSLink( "admin/booking/edit/planning", $this->site)?>"><button>Modify the planning</button></a>
    <a href="<?= \App\Core\Helpers::renderCMSLink( "admin/booking/history", $this->site)?>"><button>Records</button></a>
    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <section>
            <h2>Pending</h2>
            <div class="col-inner">
                <table id="pending" class="display" width="100%"></table>
            </div>
        <section>

        <section>
            <h2>Accepted</h2>
            <div class="col-inner">
                <table id="accepted" class="display" width="100%"></table>
            </div>
        <section>
    </div>
</div>

<script>
    var pendingDataSet = [
        <?php foreach($pendings['data'] as $data):?>
                [ <?=$data?> ],
        <?php endforeach;?>
        ];
    
    $(document).ready(function() {
        $('#pending').DataTable( {
            data: pendingDataSet,
            columns: [
                <?php foreach($pendings['fields'] as $field):?>
                { title: "<?=$field?>" },
                <?php endforeach;?>
            ]
        } );
    } );

    var acceptedDataSet = [
        <?php foreach($accepted['data'] as $data):?>
                [ <?=$data?> ],
        <?php endforeach;?>
        ];
    
    $(document).ready(function() {
        $('#accepted').DataTable( {
            data: acceptedDataSet,
            columns: [
                <?php foreach($accepted['fields'] as $field):?>
                { title: "<?=$field?>" },
                <?php endforeach;?>
            ]
        } );
    } );
</script>