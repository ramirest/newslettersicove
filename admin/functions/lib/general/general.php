<?php
/**
* This file has some very basic functions in it. Generic functions are in here and should be useful with any product.
* This includes debug functions, file handling and so on.
*
* @version     $Id: general.php,v 1.13 2007/05/11 02:36:53 chris Exp $

*
* @package Library
* @subpackage General
*/

/**
* EasySize
* Turns a size into an appropriate unit. Eg bytes, Kb, Mb, Gb etc.
*
* @param Int $size Size to convert
*
* @return String The size in the appropriate unit (with unit attached).
*/
function EasySize($size=0)
{
	if ($size < 1024) {
		return $size . ' b';
	}

	if ($size >= 1024 && $size < (1024*1024)) {
		return number_format(($size/1024), 2) . ' Kb';
	}

	if ($size >= (1024*1024) && $size < (1024*1024*1024)) {
		return number_format(($size/1024/1024), 2) . ' Mb';
	}

	if ($size >= (1024*1024*1024)) {
		return number_format(($size/1024/1024/1024), 2) . ' Gb';
	}
}

/**
* remove_directory
* Will recursively remove directories and clean up files in each directory.
*
* @param String $directory Name of directory to clean up and clear.
*
* @return Boolean Returns false if it can't remove a directory or a file. Returns true if it all worked ok.
*/
function remove_directory($directory='')
{
	if (!is_dir($directory)) {
		return true;
	}

	if (!$handle = opendir($directory)) {
		return false;
	}

	while (($file = readdir($handle)) !== false) {
		if ($file == '.' || $file == '..') {
			continue;
		}

		$f = $directory . '/' . $file;
		if (is_dir($f)) {
			remove_directory($f);
			continue;
		}

		if (is_file($f)) {
			if (!unlink($f)) {
				closedir($handle);
				return false;
			}
		}
	}
	closedir($handle);
	$status = rmdir($directory);
	return $status;
}

/**
* CreateDirectory
* Creates a full path to a directory. If any part breaks (permissions), then it dies and returns false.
*
* @param String $dirname Full path to directory to make.
* @param String $checkbase The base path to check from. This will stop issues with open_basedir restrictions so it doesn't go back and check '/' etc.
*
* @return Boolean Returns true if the directory exists or if it's able to create it properly. Returns false if it can't create the directory or it's an invalid directory name passed in.
*/
function CreateDirectory($dirname=false, $checkbase=TEMP_DIRECTORY)
{
	if (!$dirname) {
		return false;
	}

	if (is_dir($dirname)) {
		return true;
	}

	$dirname = str_replace($checkbase, '', $dirname);

	$parts = explode('/', $dirname);
	$result = false;
	$size = count($parts);
	$base = $checkbase;
	for ($i = 0; $i < $size; $i++) {
		if ($parts[$i] == '') {
			continue;
		}
		$base .= '/' . $parts[$i];
		if (!is_dir($base)) {
			$result = mkdir($base, 0755);
			if (!$result) {
				return false;
			}
			chmod($base, 0755);
		}
	}
	return true;
}

/**
* list_files
* Lists files in a directory. Can also skip particular types of files.
*
* @param String $dir Name of directory to list files for.
* @param Array $skip_files Filenames to skip. Can be a single file or an array of filenames.
* @param Boolean $recursive Whether to recursively list files or not. Default is no.
* @param Boolean $only_directories Whether to only include directories in the file list or not. Default is no (ie include all files/directories).
*
* @return Mixed Returns false if it can't open a directory, else it returns a multi-dimensional array.
*/
function list_files($dir='', $skip_files = null, $recursive=false, $only_directories=false)
{
	if (empty($dir) || !is_dir($dir)) {
		return false;
	}

	$file_list = array();

	if (!$handle = opendir($dir)) {
		return false;
	}

	while (($file = readdir($handle)) !== false) {
		if ($file == '.' || $file == '..') {
			continue;
		}
		if (is_file($dir.'/'.$file)) {
			if ($only_directories) {
				continue;
			}
			if (empty($skip_files)) {
				$file_list[] = $file;
				continue;
			}
			if (!empty($skip_files)) {
				if (is_array($skip_files) && !in_array($file, $skip_files)) {
					$file_list[] = $file;
				}
				if (!is_array($skip_files) && $file != $skip_files) {
					$file_list[] = $file;
				}
			}
			continue;
		}

		if (is_dir($dir.'/'.$file) && !isset($file_list[$file])) {
			if ($recursive) {
				$file_list[$file] = list_files($dir.'/'.$file, $skip_files, $recursive, $only_directories);
			}
		}
	}
	closedir($handle);
	if (!$recursive) {
		natcasesort($file_list);
	}

	return $file_list;
}

/**
* list_directories
* Lists directories under a particular tree. Can also skip particular directory names of files.
*
* @param String $dir Name of directory to list directories for.
* @param Array $skip_dirs Directory names to skip. Can be a single name or an array.
* @param Boolean $recursive Whether to recursively list directories or not. Default is no.
*
* @return Mixed Returns false if it can't open a directory, else it returns a multi-dimensional array.
*/
function list_directories($dir='', $skip_dirs = null, $recursive=false)
{
	if (empty($dir) || !is_dir($dir)) {
		return false;
	}

	$file_list = array();

	if (!$handle = opendir($dir)) {
		return false;
	}

	if (substr($dir, -1) == '/') {
		$dir = substr($dir, 0, -1);
	}

	while (($file = readdir($handle)) !== false) {
		if ($file == '.' || $file == '..') {
			continue;
		}

		if (is_dir($dir.'/'.$file) && !isset($file_list[$file])) {
			$file_list[] = $dir . '/' . $file;
			if ($recursive) {
				$subdir = list_directories($dir.'/'.$file, $skip_dirs, $recursive);
				if (!empty($subdir)) {
					foreach ($subdir as $p => $dir) {
						$file_list[] = $dir;
					}
				}
			}
		}
	}
	closedir($handle);
	if (!$recursive) {
		natcasesort($file_list);
	}

	return $file_list;
}

/**
* CopyDirectory
* Copies an entire directory structure from source to destination. Works recursively.
*
* @param String $source Source directory to copy.
* @param String $destination Destination directory to create and copy to.
*
* @return Boolean Returns true if all files were worked ok, otherwise false.
*/
function CopyDirectory($source='', $destination='')
{
	if (!$source || !$destination) {
		return false;
	}

	if (!is_dir($source)) {
		return false;
	}

	if (!CreateDirectory($destination)) {
		return false;
	}

	$files_to_copy = list_files($source, null, true);

	$status = true;

	foreach ($files_to_copy as $pos => $name) {
		if (is_array($name)) {
			$dir = $pos;
			$status = CopyDirectory($source . '/' . $dir, $destination . '/' . $dir);
		}

		if (!is_array($name)) {
			$copystatus = copy($source . '/' . $name, $destination . '/' . $name);
			if ($copystatus) {
				chmod($destination . '/' . $name, 0644);
			}
			$status = $copystatus;
		}
	}
	return $status;
}

/**
* See if the file_get_contents function is available.
*/
if (!function_exists('file_get_contents')) {
	/**
	* file_get_contents
	* If there is no file_get_contents function, then recreate it.
	*
	* @param String $filename Filename to read (full path).
	*
	* @return Mixed Returns false if the file doesn't exist or isn't readable. If it is, then it reads the file and returns it's contents.
	*/
	function file_get_contents($filename=false)
	{
		if (!is_file($filename) || !is_readable($filename)) {
			return false;
		}
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
}

/**
* Timedifference
* Returns the time difference in an easy format / unit system (eg how many seconds, minutes, hours etc).
*
* @param Int $timedifference Time difference as an integer to transform.
*
* @return String Time difference plus units.
*/
function timedifference($timedifference=0)
{
	if ($timedifference < 60) {
		$timechange = number_format($timedifference, 0) . ' second';
		if ($timedifference > 1) {
			$timechange .= 's';
		}
	}

	if ($timedifference >= 60 && $timedifference < 3600) {
		$num_mins = floor($timedifference / 60);
		$timechange  = number_format($num_mins, 0) . ' minute';
		if ($num_mins > 1) {
			$timechange .= 's';
		}
	}

	if ($timedifference >= 3600) {
		$hours = floor($timedifference/3600);
		$mins = floor($timedifference % 3600) / 60;

		$timechange = number_format($hours, 0) . ' hour';
		if ($hours > 1) {
			$timechange .= 's';
		}

		$timechange .= ' and ' . number_format($mins, 0) . ' minute';
		if ($mins > 1) {
			$timechange .= 's';
		}
	}
	return $timechange;
}


/**
* array_contents
* Recursively prints an array. Works well with associative arrays and objects.
*
* @see bam
*
* @param Array $array Array or object to print
* @param Int $max_depth Maximum depth to print
* @param Int $depth Used internally to make sure the array doesn't go past max_depth.
* @param Boolean $ignore_ints So it doesn't show numbers only.
*
* @return String The contents of the array / object is returned as a string.
*/
function array_contents(&$array, $max_depth=0, $depth=0, $ignore_ints=false)
{
	$string = $indent = "";
	for ($i = 0; $i < $depth; $i++) {
		$indent .= "\t";
	}
	if (!empty($max_depth) && $depth >= $max_depth) {
		return $indent."[Max Depth Reached]\n";
	}
	if (empty($array)) {
		return $indent."[Empty]\n";
	}
	reset($array);
	while ( list($key,$value) = each($array) ) {
		$print_key = str_replace("\n","\\n",str_replace("\r","\\r",str_replace("\t","\\t",addslashes($key))));
		if ($ignore_ints && gettype($key) == "integer") {
			continue;
		}
		$type = gettype($value);
		if ($type == "array" || $type == "object") {
			$string .= $indent
					.  ((is_string($key)) ? "\"$print_key\"": $key) . " => "
					.  (($type == "array")?"array (\n":"")
					.  (($type == "object")?"new ".get_class($value)." Object (\n":"");
			$string .= array_contents($value, $max_depth, $depth + 1,  $ignore_ints);
			$string .= $indent . "),\n";
		} else {
			if (is_string($value)) {
				$value = str_replace("\n","\\n",str_replace("\r","\\r",str_replace("\t","\\t",addslashes($value))));
			}
			$string .= $indent
					.  ((is_string($key)) ? "\"$print_key\"": $key) . " => "
					.  ((is_string($value)) ? "\"$value\"": $value) . ",\n";
		}
	}
	$string[ strlen($string) - 2 ] = " ";
	return $string;
}

/**
* bam
* Prints out a variable, possibly recursively if the variable is an array or object.
*
* @see array_contents
*
* @param String $x Message to print out.
* @param Int $max_depth Maximum depth to print out of the variable if it's an object / array.
* @param String $style Stylesheet to apply.
*
* @return Void Doesn't return anything.
*/
function bam($x='BAM!', $max_depth=0, $style='')
{
?>
	<div align="left"><pre style="<?php echo $style; ?>font-family: courier, monospace;"><?php
	$type = gettype($x);
	if ($type == "object" && !$max_depth) {
		print_r($x);
	} else {
		if ($type == "object" || $type == "array") {
			# get the contents, then
			if (!$max_depth) {
				$max_depth = 10;
			}
			$x = array_contents($x, $max_depth);
		}
		echo htmlspecialchars(ereg_replace("\t", str_repeat (" ", 4), $x));
	}#end switch
	?></pre></div>
<?php
}

/**
* FloatTime
* Returns seconds and microtime. Used to check performance.
*
* @return Float Returns a floating point number of seconds and microseconds.
*/
function floattime()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

/**
* MemoryUsage
* Returns memory usage in KB's to 4 decimal places.
* Used for debug purposes only.
*/
function MemoryUsage()
{
	$mem = memory_get_usage();
	return number_format(($mem / 1024), 4);
}

?>
