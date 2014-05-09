<?php
/**
* This file handles mysql database connections, queries, procedures etc.
* Most functions are overridden from the base object.
*
* @version     $Id: mysql.php,v 1.21 2007/06/22 01:45:51 rodney Exp $

*
* @package Db
* @subpackage MySQLDb
*/

/**
* Include the base database class.
*/
require(dirname(__FILE__).'/db.php');

if (!function_exists('mysql_connect')) {
	die("Your PHP installation does not have MySQL support. Please enable MySQL support in PHP or ask your web host to do so for you.");
}

/**
* This is the class for the MySQL database system.
*
* @package Db
* @subpackage MySQLDb
*/
class MySQLDb extends Db
{

	/**
	* Constructor
	* Sets up the database connection.
	* Can pass in the hostname, username, password and database name if you want to.
	* If you don't it will set up the base class, then you'll have to call Connect yourself.
	*
	* @param String $hostname Name of the server to connect to.
	* @param String $username Username to connect to the server with.
	* @param String $password Password to connect with.
	* @param String $databasename Database name to connect to.
	*
	* @see Connect
	* @see GetError
	*
	* @return Mixed Returns false if no connection can be made - the error can be fetched by the Error() method. Returns the connection result if it can be made. Will return Null if you don't pass in the connection details.
	*/
	function MySQLDb($hostname='', $username='', $password='', $databasename='')
	{
		if ($hostname && $username && $databasename) {
			$connection = $this->Connect($hostname, $username, $password, $databasename);
			return $connection;
		}
		return null;
	}

	/**
	* Connect
	* This function will connect to the database based on the details passed in.
	*
	* @param String $hostname Name of the server to connect to.
	* @param String $username Username to connect to the server with.
	* @param String $password Password to connect with.
	* @param String $databasename Database name to connect to.
	*
	* @see SetError
	*
	* @return False|Resource Returns the resource if the connection is successful. If anything is missing or incorrect, this will return false.
	*/
	function Connect($hostname='', $username='', $password='', $databasename='')
	{
		if ($hostname == '') {
			$this->SetError('No server name to connect to');
			return false;
		}

		if ($username == '') {
			$this->SetError('No username name to connect to server '.$hostname.' with');
			return false;
		}

		if ($databasename == '') {
			$this->SetError('No database name to connect to');
			return false;
		}

		$connection_result = @mysql_connect($hostname, $username, $password);
		if (!$connection_result) {
			$this->SetError(mysql_error());
			return false;
		}
		$this->connection = &$connection_result;

		$db_result = @mysql_select_db($databasename, $connection_result);
		if (!$db_result) {
			$this->SetError('Unable to select database \''.$databasename.'\': '.mysql_error());
			return false;
		}
		return $this->connection;
	}

	/**
	* Disconnect
	* This function will disconnect from the database handler passed in.
	*
	* @param String $resource Resource to disconnect from
	*
	* @see SetError
	*
	* @return Boolean If the resource passed in is not valid, this will return false. Otherwise it returns the status from pg_close.
	*/
	function Disconnect($resource=null)
	{
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource '.$resource.' is not really a resource');
			return false;
		}
		$close_success = mysql_close($resource);
		return $close_success;
	}

	/**
	* Query
	* This function will run a query against the database and return the result of the query.
	*
	* @param String $query The query to run.
	*
	* @see LogQuery
	* @see SetError
	*
	* @return Mixed Returns false if the query is empty or if there is no result. Otherwise returns the result of the query.
	*/
	function Query($query='')
	{
		if (!$query) {
			$this->SetError('Query passed in is empty');
			return false;
		}
		if (!$this->connection) {
			$this->SetError('No valid connection');
			return false;
		}
		$this->NumQueries++;

		$timestart = $this->GetTime();
		$result = mysql_query($query);
		$timeend = $this->GetTime();
		$this->TimeQuery($query, $timestart, $timeend);

		$this->LogQuery("Result type: " . gettype($result) . "; value: " . $result . "\t" . $query);

		if (!$result) {
			$this->LogError($query, mysql_error());
			$this->SetError(mysql_error());
			return false;
		}

		return $result;
	}

	/**
	* Fetch
	* This function will fetch a result from the result set passed in.
	*
	* @param String $resource The result from calling Query. Returns an associative array (not an indexed based one)
	*
	* @see Query
	* @see SetError
	* @see StripslashesArray
	*
	* @return Mixed Returns false if the result is empty. Otherwise returns the next result.
	*/
	function Fetch($resource=null)
	{
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource '.$resource.' is not really a resource');
			return false;
		}
		return (get_magic_quotes_runtime()) ? $this->StripslashesArray(mysql_fetch_assoc($resource)) : mysql_fetch_assoc($resource);
	}

	/**
	* NextID
	* Fetches the next id from the sequence passed in
	*
	* @param String $sequencename Sequence Name to fetch the next id for.
	*
	* @see Query
	*
	* @return Mixed Returns false if there is no sequence name or if it can't fetch the next id. Otherwise returns the next id
	*/
	function NextId($sequencename=false)
	{
		if (!$sequencename) {
			return false;
		}
		$query = 'UPDATE '.$sequencename.' SET id=LAST_INSERT_ID(id+1)';
		$result = $this->Query($query);
		if (!$result) {
			return false;
		}
		return mysql_insert_id();
	}

	/**
	* FullText
	* Fulltext works out how to handle full text searches. Returns an sql statement to append to enable full text searching.
	*
	* @param Mixed $fields Fields to search against. This can be an array or a single field.
	* @param String $searchstring String to search for against the fields
	* @param Bool $booleanmode In MySQL, is this search in boolean mode ?
	*
	* @return Mixed Returns false if either fields or searchstring aren't present, otherwise returns a string to append to an sql statement.
	*/
	function FullText($fields=null, $searchstring=null, $booleanmode=false)
	{
		if (is_null($fields) || is_null($searchstring)) {
			return false;
		}
		if (is_array($fields)) {
			$fields = implode(',', $fields);
		}
		if ($booleanmode) {
			$query = 'MATCH ('.$fields.') AGAINST (\''.$this->Quote($searchstring).'\' IN BOOLEAN MODE)';
		} else {
			$query = 'MATCH ('.$fields.') AGAINST (\''.$this->Quote($searchstring).'\')';
		}
		return $query;
	}

	/**
	* AddLimit
	* This function creates the SQL to add a limit clause to an sql statement.
	*
	* @param Int $offset Where to start fetching the results
	* @param Int $numtofetch Number of results to fetch
	*
	* @return String The string to add to the end of the sql statement
	*/
	function AddLimit($offset=0, $numtofetch=0)
	{
		if ($offset < 0) {
			$offset = 0;
		}
		if ($numtofetch <= 0) {
			$numtofetch = 10;
		}
		$query = ' LIMIT '.$offset.', '.$numtofetch;
		return $query;
	}

	/**
	* FreeResult
	* Frees the result from memory.
	*
	* @param String $resource The result resource you want to free up.
	*
	* @return Boolean Whether freeing the result worked or not.
	*/
	function FreeResult($resource=null)
	{
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource '.$resource.' is not really a resource');
			return false;
		}
		$result = mysql_free_result($resource);
		return $result;
	}

	/**
	* CountResult
	* Returns the number of rows returned for the resource passed in
	*
	* @param String $resource The result from calling Query
	*
	* @see Query
	* @see SetError
	*
	* @return Int Number of rows from the result
	*/
	function CountResult($resource=null)
	{
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource '.$resource.' is not really a resource');
			return false;
		}
		$count = mysql_num_rows($resource);
		return $count;
	}

	/**
	* Concat
	* Concatentates multiple strings together. This method is mysql specific. It doesn't matter how many arguments you pass in, it will handle them all.
	* If you pass in one argument, it will return it straight away.
	* Otherwise, it will use the mysql specific CONCAT function to put everything together and return the new string.
	*
	* @return String Returns the new string with all of the arguments concatenated together.
	*/
	function Concat()
	{
		$num_args = func_num_args();
		if ($num_args < 1) {
			return func_get_arg(0);
		}
		$all_args = func_get_args();
		$returnstring = 'CONCAT('.implode(',', $all_args).')';
		return $returnstring;
	}

	/**
	* Quote
	* Quotes the string ready for database queries. Runs mysql_escape_string or mysql_real_escape_string depending on the php version.
	*
	* @param String $string String you want to quote ready for database entry.
	*
	* @return String String with quotes!
	*/
	function Quote($string='')
	{
		if (version_compare(PHP_VERSION, '4.3.0', '>=')) {
			return mysql_real_escape_string($string);
		} else {
			return mysql_escape_string($string);
		}
	}

	/**
	* LastId
	*
	* Returns the last insert id
	*
	* @param String $seq The sequence name to fetch the last id from.
	*
	* @return False|Int If there is no sequence name passed in, this will return false. Otherwise it returns the last number from the sequence name passed in.
	*/
	function LastId($seq='')
	{
		if (!$seq) {
			return false;
		}
		return mysql_insert_id();
	}

	/**
	* CheckSequence
	*
	* Checks to make sure a sequence doesn't have multiple entries.
	*
	* @return Boolean Returns true if there is exactly 1 entry in the sequence table, otherwise returns false.
	*/
	function CheckSequence($seq='')
	{
		if (!$seq) {
			return false;
		}
		$query = "SELECT COUNT(*) AS count FROM " . $seq;
		$count = $this->FetchOne($query, 'count');
		if ($count == 1) {
			return true;
		}
		return false;
	}

	/**
	* ResetSequence
	*
	* Resets a sequence to a new id.
	*
	* @param String $seq The sequence name to reset.
	* @param Int $newid The new id to set the sequence to.
	*
	* @return Boolean Returns true if the sequence is reset, otherwise false.
	*/
	function ResetSequence($seq='', $newid=0)
	{
		if (!$seq) {
			return false;
		}

		$newid = (int)$newid;
		if ($newid <= 0) {
			return false;
		}

		$query = "TRUNCATE TABLE " . $seq;
		$result = $this->Query($query);
		if (!$result) {
			return false;
		}

		// since a sequence table only has one field, we don't care what the fieldname is.
		$query = "INSERT INTO " . $seq . " VALUES (" . $newid . ")";
		$result = $this->Query($query);
		if (!$result) {
			return false;
		}

		return $this->CheckSequence($seq);
	}

	/**
	* OptimizeTable
	*
	* Runs "optimize" over the tablename passed in. This is useful to keep the database reasonably speedy.
	*
	* @param String $tablename The tablename to optimize.
	*
	* @see Query
	*
	* @return Mixed If no tablename is passed in, this returns false straight away. Otherwise it calls Query and returns the result from that.
	*/
	function OptimizeTable($tablename='')
	{
		if (!$tablename) {
			return false;
		}
		$query = "OPTIMIZE TABLE " . $tablename;
		return $this->Query($query);
	}

}
?>
