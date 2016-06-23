        </div> <!-- /container -->

    <!--    <footer>
                <div class="container">
                 <p>&copy; mania.tn 2015</p>
                </div>
            </footer>-->



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <?php if ($ajax_url['ajax_url']): ?>
        <script>var ajax_object_url= '<?php echo $ajax_url['object_services_urls'] ;?>'</script>
        <?php foreach($ajax_url['ajax_sript'] as $ajax_file){?>
        <script src="<?php echo base_url().'/js/'. $ajax_file  ?>"></script>
        <?php }endif ?>
        <script src="<?php echo base_url()."/js/libs/jquery.nicescroll.js"?>"></script>
        <script src="<?php echo base_url()."/js/libs/country.js" ?>"></script> 
        <script src="<?php echo base_url()."/js/libs/prefixfree.min.js"?>"></script> 
        <script src="<?php echo base_url()."/js/main.js"?>"></script>
        
    </body>
</html>
