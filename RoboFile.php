<?php

include '.tk/RoboFileBase.php';

class RoboFile extends RoboFileBase {
	public function directoriesStructure() {
		return array( 'assets', 'spinner', 'languages' );
	}

	public function fileStructure() {
		return array( 'all-in-one-invite-codes-buddypress.php', 'composer.json', 'license.txt', 'readme.txt' );
	}

	public function cleanPhpDirectories() {
		return array( '' );
	}

	public function pluginMainFile() {
		return 'all-in-one-invite-codes-buddypress';
	}

	public function pluginFreemiusId() {
		return 3322;
	}

	public function minifyAssetsDirectories() {
		return array( 'assets' );
	}

	public function minifyImagesDirectories() {
		return array();
	}

	/**
	 * @return array Pair list of sass source directory and css target directory
	 */
	public function sassSourceTarget() {
		return array( array( 'scss/source' => 'assets/css' ) );
	}

	/**
	 * @return string Relative paths from the root folder of the plugin
	 */
	public function sassLibraryDirectory() {
		return 'scss/library';
	}
}