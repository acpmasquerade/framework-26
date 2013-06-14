<?php if (Config::get("navigation") === true): ?>
    </div> <!--docs-container -->
    </div> <!--container-fluid -->
    </div> <!--content -->
<?php endif; ?>

<script src="<?php echo Config::get("base_url"); ?>templates/lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
    $(function() {
        prettyPrint();
        $('.demo-cancel-click').click(function() {
            return false;
        });
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(ev) {
            $(this).closest('.accordion-group').addClass('btn-warning');
            $(this).datepicker('hide');
        });
    });
</script>

</body>
</html>


