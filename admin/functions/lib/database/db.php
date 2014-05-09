<?php
/**
* This file only has the base Db class in it. This sets up class variables and basic constructs only.
* Each subclass (different database type) overrides most functions (except logging) and handles database specific instances.
*
* @version     $Id: db.php,v 1.17 2007/05/08 02:35:00 chris Exp $

*
* @package Library
* @subpackage Db
*/

/**
* This is the base class for the database system.
* Almost all methods are overwritten in subclasses, except for logging and for the 'FetchOne' method.
*
* @package Library
* @subpackage Db
*/
class Db
{

	/**
	* The global database connection.
	*
	* @see Connect
	*
	* @var Resource
	*/
	var $connection = null;


	/**
	* Where any database errors are stored.
	*
	* @see GetError
	* @see SetError
	*
	* @access private
	*
	* @var String
	*/
	var $_Error = null;


	/**
	* What type of error this is.
	*
	* @see GetError
	* @see SetError
	*
	* @access private
	*
	* @var String
	*/
	var $_ErrorLevel = E_USER_ERROR;


	/**
	* Determines whether a query will be logged or not. If it's false or null it won't log, if it's a filename (or path to a file) it will log.
	* Useful for debug / development purposes to see all the queries being run.
	*
	* @see LogQuery
	*
	* @var String
	*/
	var $QueryLog = null;

	/**
	* Determines whether a time query will be logged or not. If it's false or null it won't log, if it's a filename (or path to a file) it will log.
	* Useful for debug / development purposes to find slow queries.
	*
	* @see TimeQuery
	*
	* @var String
	*/
	var $TimeLog = null;

	/**
	* Determines whether an error will be logged or not. If it's false or null it won't log, if it's a filename (or path to a file) it will log.
	* Useful for debug / development purposes to find queries that create errors.
	*
	* @see LogError
	*
	* @var String
	*/
	var $ErrorLog = null;

	/**
	* The number of queries that have been executed
	*
	* @var Integer
	*/
	var $NumQueries = 0;

	/**
	* Constructor
	*
	* Sets up the database connection.
	* Since this is the parent class the others inherit from, this returns null when called directly.
	*
	* @return Null This will always return null in the base class.
	*/
	function Db()
	{
		return null;
	}

	/**
	* Connect
	*
	* This function will connect to the database based on the details passed in.
	* Since this is the parent class the others inherit from, this returns false when called directly.
	*
	* @return False Will always return false when called in the base class.
	*/
	function Connect()
	{
		$this->SetError('Cannot call base class method Connect directly');
		return false;
	}

	/**
	* Disconnect
	*
	* This function will disconnect from the database resource passed in.
	* Since this is the parent class the others inherit from, this returns false when called directly.
	*
	* @return False Will always return false when called in the base class.
	*/
	function Disconnect()
	{
		$this->SetError('Cannot call base class method Disconnect directly');
		return false;
	}

	/**
	* SetError
	*
	* Stores the error in the _error var for retrieval.
	*
	* @param String $error The error you wish to store for retrieval.
	* @param String $errorlevel The error level you wish to store.
	*
	* @return Void Doesn't return anything, only sets the values and leaves it at that.
	*/
	function SetError($error='', $errorlevel=E_USER_ERROR)
	{
		$this->_Error = $error;
		$this->_ErrorLevel = $errorlevel;
	}

	/**
	* GetError
	*
	* This simply returns the $_Error var and it's error level.
	*
	* @see SetError
	*
	* @return Array Returns the error and it's error level.
	*/
	function GetError()
	{
		return array($this->_Error, $this->_ErrorLevel);
	}

	/**
	* GetErrorMsg
	*
	* This simply returns the $_Error var
	*
	* @access public
	*
	* @see SetError
	*
	* @return String Returns the error
	*/
	function GetErrorMsg()
	{
		return $this->_Error;
	}

	/**
	* Query
	*
	* This runs a query against the database and returns the resource result.
	*
	* @return False Will always return false when called in the base class.
	*/
	function Query()
	{
		$this->SetError('Cannot call base class method Query directly');
		return false;
	}

	/**
	* Fetch
	* This function will fetch a result from the result set passed in.
	*
	* @see Query
	* @see SetError
	*
	* @return False Will always return false when called in the base class.
	*/
	function Fetch()
	{
		$this->SetError('Cannot call base class method Fetch directly');
		return false;
	}

	/**
	* LogError
	* This will log all errors if ErrorLog is not false or null. Is called from Query.
	*
	* @param String $query The query to log.
	* @param String $error The error message to log.
	*
	* @see ErrorLog
	* @see Query
	*
	* @return Boolean Returns whether the query & error are logged or not. Will return false if there is no query, or if the ErrorLog variable is set to false or null.
	*/
	function LogError($query='', $error=false)
	{
		if (!$query) {
			return false;
		}

		if (!$this->ErrorLog || is_null($this->ErrorLog)) {
			return false;
		}

		if (!$fp = fopen($this->ErrorLog, 'a+')) {
			return false;
		}

		$backtrace = '';
		if (function_exists('debug_backtrace')) {
			$backtrace = debug_backtrace();
			$called_from = $backtrace[1];
			$backtrace = $called_from['file'] . ' (' . $called_from['line'] . ')'. "\t";
		}
		$line = date('M d H:i:s') . "\t" . getmypid() . "\t" . $backtrace . "\t" . "Error!: " . $error . "\t" . $query . "\n";
		fputs($fp, $line, strlen($line));
		fclose($fp);
		return true;
	}

	/**
	* LogQuery
	* This will log all queries if QueryLog is not false or null. Is called from Query.
	*
	* @param String $query The query to log.
	*
	* @see QueryLog
	* @see Query
	* @see SetError
	*
	* @return Boolean Returns whether the query is logged or not. Will return false if there is no query or if the QueryLog variable is set to false or null.
	*/
	function LogQuery($query='')
	{
		if (!$query) {
			return false;
		}

		if (!$this->QueryLog || is_null($this->QueryLog)) {
			return false;
		}

		if (!$fp = fopen($this->QueryLog, 'a+')) {
			return false;
		}

		$backtrace = '';
		if (function_exists('debug_backtrace')) {
			$backtrace = debug_backtrace();
			$called_from = $backtrace[1];
			$backtrace = $called_from['file'] . ' (' . $called_from['line'] . ')'. "\t";
		}
		$line = date('M d H:i:s') . "\t" . getmypid() . "\t" . $backtrace . $query . "\n";
		fputs($fp, $line, strlen($line));
		fclose($fp);
		return true;
	}

	function TimeQuery($query='', $timestart=0, $timeend=0)
	{
		if (!$this->TimeLog || is_null($this->TimeLog)) {
			return false;
		}

		if (!$query) {
			return false;
		}

		if (!$fp = fopen($this->TimeLog, 'a+')) {
			return false;
		}

		$backtrace = '';
		if (function_exists('debug_backtrace')) {
			$backtrace = debug_backtrace();
			$called_from = $backtrace[1];
			$backtrace = $called_from['file'] . ' (' . $called_from['line'] . ')'. "\t";
		}
		$line = date('M d H:i:s') . "\t" . getmypid() . "\t" . sprintf("%01.2f", ($timeend - $timestart)) . "\t" . $query . "\t" . $backtrace . "\n";
		fputs($fp, $line, strlen($line));
		fclose($fp);
		return true;
	}

	/**
	* FreeResult
	* Frees the result from memory. The base class always returns false.
	*
	* @see Query
	* @see SetError
	*
	* @return Boolean Whether freeing the result worked or not.
	*/
	function FreeResult()
	{
		$this->SetError('Can\'t call FreeResult from the base class.');
		return false;
	}

	/**
	* CountResult
	* Returns the number of rows returned for the resource passed in. The base class always returns 0.
	*
	* @see Query
	* @see SetError
	*
	* @return False Always returns false in the base class.
	*/
	function CountResult()
	{
		$this->SetError('Can\'t call CountResult from the base class.');
		return false;
	}

	/**
	* FetchOne
	* Fetches one item from a result and returns it.
	*
	* @param String $result Result to fetch the item from.
	* @param String $item The item to look for and return.
	*
	* @see Fetch
	*
	* @return Mixed Returns false if there is no result or item, or if the item doesn't exist in the result. Otherwise returns the item's value.
	*/
	function FetchOne($result=null, $item=null)
	{
		if (is_null($result)) {
			return false;
		}
		if (!is_resource($result)) {
			$result = $this->Query($result);
		}
		$row = $this->Fetch($result);
		if (!$row) {
			return false;
		}
		if (is_null($item)) {
			$item = key($row);
		}
		if (!isset($row[$item])) {
			return false;
		}
		return (get_magic_quotes_runtime()) ? stripslashes($row[$item]) : $row[$item];
	}

	/**
	* Concat
	* Concatentates multiple strings together. The base class returns nothing - it needs to be overridden for each database type.
	*
	* @return False Always returns false in the base class.
	*/
	function Concat()
	{
		return false;
	}

	/**
	* StripslashesArray
	* Strips the slashes from all the elements in an entire (multidimensional) array
	*
	* @param Mixed $value Item you want to strip the slashes from
	*
	* @return Mixed Returns the same type as passed in
	*/
	function StripslashesArray($value)
	{
		$value = is_array($value) ? array_map($this->{'StripslashesArray'}, $value) : stripslashes($value);
		return $value;
	}

	/**
	* Quote
	* Quotes the string ready for database queries. This uses addslashes if not overridden in subclasses.
	*
	* @param String $string String you want to quote ready for database entry.
	*
	* @return String String with quotes!
	*/
	function Quote($string='')
	{
		return addslashes($string);
	}

	/**
	* LastId
	*
	* Returns the last insert id
	*
	* @param String $seq The sequence name to fetch the last id from.
	*
	* @return False The base class always returns false.
	*/
	function LastId($seq='')
	{
		$this->SetError('Can\'t call LastId from the base class.');
		return false;
	}

	/**
	* NextId
	*
	* Returns the next insert id
	*
	* @param String $seq The sequence name to fetch the next id from.
	*
	* @return False The base class always returns false.
	*/
	function NextId($seq='')
	{
		$this->SetError('Can\'t call NextId from the base class.');
		return false;
	}

	/**
	* CheckSequence
	*
	* Checks to make sure a sequence doesn't have multiple entries.
	*
	* @return False The base class always returns false.
	*/
	function CheckSequence()
	{
		$this->SetError('Can\'t call CheckSequence from the base class.');
		return false;
	}

	/**
	* ResetSequence
	*
	* Resets a field in a sequence table.
	*
	* @return False The base class always returns false.
	*/
	function ResetSequence()
	{
		$this->SetError('Can\'t call ResetSequence from the base class.');
		return false;
	}

	/**
	* OptimizeTable
	*
	* Runs "optimize" or "analyze" over the tablename passed in. This is useful to keep the database reasonably speedy.
	*
	* @param String $tablename The tablename to optimize.
	*
	* @return False The base class always returns false.
	*/
	function OptimizeTable($tablename='')
	{
		$this->SetError('Can\'t call OptimizeTable from the base class.');
		return false;
	}

	/**
	* GetTime
	*
	* Returns a float microtime so we can record how long it took to get a result from the database.
	*
	* @see TimeQuery
	* @see MySQLDB::Query
	* @see PgSQLDB::Query
	*
	* @return Float Returns a float microtime.
	*/
	function GetTime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

}

?>
