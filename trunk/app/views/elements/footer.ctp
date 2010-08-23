<div id="pageFooter">
    webrsa v. <?php echo app_version();?> (CakePHP v. <?php echo core_version();?>) - 2009 - 2010 @ Adullact.
    <?php echo sprintf( "Page construite en %s secondes. %s utilisés.", number_format( getMicrotime() - $GLOBALS['TIME_START'] , 2, ',', ' ' ), byteSize( memory_get_peak_usage() ) );?>
    $LastChangedDate: 2010-08-23 16:46:05 +0200 (lun., 23 août 2010)$
</div>
