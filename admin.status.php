<?php 
	// If the form has been submitted, things may have changed.
	if ( ( !empty( $_POST['hmbkp_options_submit'] ) ) && ( !wp_next_scheduled('hmbkp_schedule_backup_hook') || hmbkp_get_disable_automatic_backup() ) )	{
		hmbkp_constant_changes();
	}	
?>
<p>&#10003;
<?php if ( hmbkp_get_disable_automatic_backup() && !wp_next_scheduled( 'hmbkp_schedule_backup_hook' ) ) : ?>

    <?php printf( __( 'Automatic backups are %s.', 'hmbkp' ), '<strong>' . __( 'disabled', 'hmbkp' ) . '</strong>' ); ?>

<?php else :

    if ( !hmbkp_get_database_only() && !hmbkp_get_files_only()  )
    	$what_to_backup = '<code>' . __( 'database', 'hmbkp' ) . '</code> ' . __( '&amp;', 'hmbkp' ) . ' <code>' . __( 'files', 'hmbkp' ) . '</code>';

    elseif( hmbkp_get_database_only() )
    	$what_to_backup = '<code>' . __( 'database', 'hmbkp' ) . '</code>';

    else
    	$what_to_backup = '<code>' . __( 'files', 'hmbkp' ) . '</code>'; ?>

    <?php 
    	$offset = current_time( 'timestamp' ) - time();
    	$schedule = wp_get_schedules();
    	$schedule = $schedule[ wp_get_schedule('hmbkp_schedule_backup_hook') ]['display'];
    	printf( __( 'Your %s will be automatically backed up <code>%s</code>. The next backup will occur at %s and be saved to %s.', 'hmbkp' ), $what_to_backup , $schedule, '<code title="' . sprintf( __( 'It\'s currently %s', 'hmbkp' ), date_i18n( 'H:i' ) ) . '">' . date_i18n( 'd/m/y H:i', wp_next_scheduled( 'hmbkp_schedule_backup_hook' ) + $offset ) . '</code>', '<code>' . hmbkp_path() . '</code>' ); ?>

<?php endif; ?>
</p>

<p>&#10003; <span class="hmbkp_estimated-size"><?php printf( __( 'Your site is %s. Backups will be compressed and should be smaller than this.', 'hmbkp' ), get_transient( 'hmbkp_estimated_filesize' ) ? '<code>' . hmbkp_calculate() . '</code>' : '<code class="calculate">' . __( 'Calculating Size...', 'hmbkp' ) . '</code>' ); ?></span></p>

<?php if ( !hmbkp_shell_exec_available() ) : ?>
<p>&#10007; <?php printf( __( '%s is disabled which means we have to use the slower PHP fallbacks, you could try contacting your host and asking them to enable it.', 'hmbkp' ), '<code>shell_exec</code>' ); ?></p>
<?php endif; ?>

<?php if ( hmbkp_shell_exec_available() ) : ?>

    <?php if ( hmbkp_zip_path() && !hmbkp_get_database_only() ) : ?>
<p>&#10003; <?php printf( __( 'Your %s will be backed up using %s.', 'hmbkp' ), '<code>' . __( 'files', 'hmbkp' ) . '</code>', '<code>' . hmbkp_zip_path() . '</code>' ); ?></p>
    <?php endif; ?>

    <?php if ( hmbkp_mysqldump_path() && !hmbkp_get_files_only() ) : ?>
<p>&#10003; <?php printf( __( 'Your %s will be backed up using %s.', 'hmbkp' ), '<code>' . __( 'database', 'hmbkp' ) . '</code>', '<code>' . hmbkp_mysqldump_path() . '</code>' ); ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php if ( hmbkp_get_email_address() ) : ?>
<p>&#10003; <?php printf( __( 'A copy of each backup will be emailed to %s.', 'hmbkp' ), '<code>' . hmbkp_get_email_address() . '</code>' ); ?></p>
<?php endif; ?>

<?php if ( ( $valid_excludes = hmbkp_valid_custom_excludes() ) && ( !hmbkp_get_database_only() ) ) : ?>
<p>&#10003; <?php printf( __( 'The following paths will be excluded from your backups %s.', 'hmbkp' ), '<code>' . implode( '</code>, <code>', $valid_excludes ) . '</code>' ); ?></p>
<?php else : ?>
<p>&#10003; <?php _e( 'All files in the root directory of your site will be backed up.', 'hmbkp' ); ?></p>
<?php endif; ?>