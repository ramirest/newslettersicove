<?php
/**
* This file handles postgresql database connections, queries, procedures etc.
* Most functions are overridden from the base object.
*
* @version     $Id: pgsql.php,v 1.23 2007/06/22 01:45:51 rodney Exp $

*
* @package Db
* @subpackage PGSQLDb
* @filesource
*/

/**
* Include the base database class.
*/
require(dirname(__FILE__).'/db.php');

if (!function_exists('pg_connect')) {
	die("Your PHP installation does not have PostgreSQL support. Please enable PostgreSQL support in PHP or ask your web host to do so for you.");
}

/**
* Here are some backwards compatibility functions.
* If the pg_query function doesn't exist, try to use pg_exec.
*/
if (!function_exists('pg_query')) {

	/**
	* pg_query
	* Backwards compatible function
	*/
	function pg_query($query)
	{
		return pg_exec($query);
	}

}

/**
* Here are some backwards compatibility functions.
* If the pg_fetch_assoc function doesn't exist, try to use the pg_fetch_array function with appropriate variables.
*/
if (!function_exists('pg_fetch_assoc')) {

	/**
	* pg_fetch_assoc
	* Backwards compatible function
	*/
	function pg_fetch_assoc($result)
	{
		return pg_fetch_array($result, null, PGSQL_ASSOC);
	}

}

/**
* Here are some backwards compatibility functions.
* If the pg_free_result function doesn't exist, try to use the pg_freeresult function with appropriate variables.
*/
if (!function_exists('pg_free_result')) {

	/**
	* pg_free_result
	* Backwards compatible function
	*/
	function pg_free_result($result)
	{
		return pg_freeresult($result);
	}

}

/**
* This is the class for the PostgreSQL database system.
*
* @package Db
* @subpackage PGSQLDb
*/
class PGSQLDb extends Db
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
	function PGSQLDb($hostname='', $username='', $password='', $databasename='')
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

		$connection_string = 'dbname='.$databasename;
		if ($hostname != 'localhost') {
			$connection_string .= ' host='.$hostname;
		}
		$connection_string .= ' user='.$username;
		if ($password != '') {
			$connection_string .= ' password='.$password;
		}

		$connection_result = @pg_connect($connection_string);
		if (!$connection_result) {
			$this->SetError('Unable to connect to the database. Please check the settings and try again');
			return false;
		}
		$this->connection = &$connection_result;
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
		$close_success = pg_close($resource);
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
		$result = @pg_query($query);
		$timeend = $this->GetTime();
		$this->TimeQuery($query, $timestart, $timeend);

		$this->LogQuery("Result type: " . gettype($result) . "; value: " . $result . "\t" . $query);

		if (!$result) {
			$this->LogError($query, pg_last_error());
			$this->SetError(pg_last_error());
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
		return (get_magic_quotes_runtime()) ? $this->StripslashesArray(pg_fetch_assoc($resource)) : pg_fetch_assoc($resource);
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
		$query = "SELECT nextval('".$sequencename."') AS nextid";
		$nextid = $this->FetchOne($query);
		return $nextid;
	}

	/**
	* FullText
	* Fulltext works out how to handle full text searches. Returns an sql statement to append to enable full text searching.
	*
	* @param Mixed $fields Fields to search against. This can be an array or a single field.
	* @param String $searchstring String to search for against the fields
	* @param Bool $booleanmode MySQL specific switch. Doesn't do anything in PostgreSQL
	*
	* @return Mixed Returns false if either fields or searchstring aren't present, otherwise returns a string to append to an sql statement.
	*/
	function FullText($fields=null, $searchstring=null, $booleanmode=false)
	{
		if (is_null($fields) || is_null($searchstring)) {
			return false;
		}
		if (!is_array($fields)) {
			$fields = explode(',', $fields);
		}

		$query = '';
		$subqueries = array();
		foreach ($fields as $field) {
			$subqueries[]= $field.' ILIKE \'%'.$this->Quote($searchstring).'%\'';
		}
		$query = implode(' OR ', $subqueries);
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
		$query = ' LIMIT '.$numtofetch.' OFFSET '.$offset;
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
		$result = pg_free_result($resource);
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
		$count = pg_num_rows($resource);
		return $count;
	}

	/**
	* Concat
	* Concatentates multiple strings together. This method is postgresql specific. It doesn't matter how many arguments you pass in, it will handle them all.
	* If you pass in one argument, it will return it straight away.
	* Otherwise, it will use the postgresql specific CONCAT function to put everything together and return the new string.
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
		$returnstring = implode(' || ', $all_args);
		return $returnstring;
	}

	/**
	* Quote
	* Quotes the string ready for database queries. Runs pg_escape_string.
	*
	* @param String $string String you want to quote ready for database entry.
	*
	* @return String String with quotes!
	*/
	function Quote($string='')
	{
		return pg_escape_string($string);
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
		$query = "SELECT currval('".$seq."') AS lastid";
		$nextid = $this->FetchOne($query);
		return $nextid;
	}

	/**
	* CheckSequence
	*
	* Checks to make sure a sequence doesn't have multiple entries.
	*
	* @return True Postgresql doesn't have an issue with being able to have multiple id's in a sequence.
	*/
	function CheckSequence()
	{
		return true;
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

		$query = "SELECT setval('" . $seq . "', " . $newid . ", true)";
		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}
		return $this->CheckSequence;
	}

	/**
	* OptimizeTable
	*
	* Runs "analyze" over the tablename passed in. This is useful to keep the database reasonably speedy.
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
		$query = "ANALYZE " . $tablename;
		return $this->Query($query);
	}

}
?>
