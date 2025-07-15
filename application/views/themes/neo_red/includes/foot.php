    <script type="text/javascript" src="<?php public_assets('js/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php public_assets('js/popper.min.js') ?>"></script>
    <script type="text/javascript" src="<?php public_assets('js/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php public_assets('js/dropzone.min.js') ?>"></script>
    <?php if(isset($load_scripts)) { foreach($load_scripts as $src) { ?>
        <script type="text/javascript" src="<?php echo esc($src) ?>"></script>
    <?php } } 
    echo esc($scripts['footer'], true);
    echo_if($ads['pop']['status'], esc($ads['pop']['code'], true)); ?>
</body>
</html>