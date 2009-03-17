<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* This is a SQL layer to handle Database standard queries :
* Listing, Edit, Delete, Add
*
* PHP versions 4 and 5
*
* A2Billing -- Asterisk billing solution.
* Copyright (C) 2004-2008 : A2Billing
*
* See http://www.asterisk2billing.org for more information about
* the A2Billing project.
* Please submit bug reports, patches, etc to <areski _atl_ gmail com>
*
* This software is released under the terms of the GNU Lesser General Public License v2.1
* A copy of which is available from http://www.gnu.org/copyleft/lesser.html
*
* @category   Database
* @package    Table
* @author     Arezqui Belaid <areski _atl_ gmail com>
* @author     Steve Dommett <steve@st4vs.net>
* @copyright  2004-2009 A2Billing
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.0
*
*/




/**
* Class Table used to abstract Database queries and processing
*
* @category   Database
* @package    Table
* @author     Arezqui Belaid <areski _atl_ gmail com>
* @author     Steve Dommett <steve@st4vs.net>
* @copyright  2004-2009 A2Billing
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.0
*/

include (dirname(__FILE__)."/Class.MytoPg.php");

$ADODB_CACHE_DIR = '/tmp';

class Table {

	var $fields 				= '*';
	var $table  				= '';
	var $errstr 				= '';
	var $debug_st 				= 0;
	var $debug_st_stop 			= 0;
	var $start_message_debug 	= "<table width=\"100%\" align=\"right\" style=\"float : left;\"><tr><td>QUERY: \n";
	var $end_message_debug 		= "\n</td></tr></table><br><br><br>";
	var $alert_query_time 		= 0.1;
	var $alert_query_long_time 	= 2;
	
	var $writelog 				= null;

    var $FK_TABLES;
    var $FK_EDITION_CLAUSE;
    // FALSE if you want to delete the dependent Records, TRUE if you want to update
    // Dependent Records to -1
    var $FK_DELETE 				= true;
    var $FK_ID_VALUE 			= 0;


	/* CONSTRUCTOR */
	function Table ($table = null, $liste_fields = null,  $fk_Tables = null, $fk_Fields = null, $id_Value = null, $fk_del_upd = true)
	{
        $this->writelog = defined('WRITELOG_QUERY') ? WRITELOG_QUERY : false;
		$this -> table = $table;
		$this -> fields = $liste_fields;
		$this -> mytopg = new MytoPg(0); // debug level 0 logs only >30ms CPU hogs

		if ((count($fk_Tables) == count($fk_Fields)) && (count($fk_Fields) > 0)) {
			$this -> FK_TABLES = $fk_Tables;
			$this -> FK_EDITION_CLAUSE = $fk_Fields;
			$this -> FK_DELETE = $fk_del_upd;
			$this -> FK_ID_VALUE = $id_Value;
		}
	}



	/* MODIFY PROPRIETY*/
	function Define_fields ($liste_fields )
	{
		$this -> fields = $liste_fields;
	}


	function Define_table ($table)
	{
		$this -> table = $table;
	}

	/*
	 * ExecuteQuery
	 */
	function ExecuteQuery ($DBHandle, $QUERY, $cache = 0)
	{
		global $A2B;
		if ($this -> writelog) {
			$time_start = microtime(true);
		}

		if ($A2B->config["database"]['dbtype'] == 'postgres') {
			// convert MySQLisms to be Postgres compatible
			$this -> mytopg -> My_to_Pg($QUERY);
		} else {
			// the MySQL schema takes care of case-insensitivity
			$QUERY = eregi_replace('([[:space:]]+)I(LIKE[[:space:]]+)', '\1\2', $QUERY);
		}
		
		if ($this -> debug_st) echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		if ($cache > 0) {
			$res = $DBHandle -> CacheExecute($cache, $QUERY);
		} else {
			$res = $DBHandle -> Execute($QUERY);
		}
		
		if ($DBHandle -> ErrorNo() != 0) {
			$this -> errstr = $DBHandle -> ErrorMsg();
			if ($this -> debug_st)
				echo $DBHandle -> ErrorMsg();
			if ($this -> debug_st_stop)
				exit;
		}
		
		if ($this -> writelog) {
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}
        
        if ($this -> writelog) {
			if ($time > $this->alert_query_time) {
				if ($time > $this->alert_query_long_time ) 
					$A2B -> debug( WARN, false, __FILE__, __LINE__, "EXTRA_TOOLONG_DB_QUERY - RUNNING TIME = $time");
				else 
					$A2B -> debug( WARN, false, __FILE__, __LINE__, "TOOLONG_DB_QUERY - RUNNING TIME = $time");
			}
			$A2B -> debug( DEBUG, false, __FILE__, __LINE__, "Running time=$time - QUERY=\n$QUERY\n");
        }
        
		return $res;
	}

	// If $select is not supplied then function check numrows
	// so expect a SELECT query.

	function SQLExec ($DBHandle, $QUERY, $select = 1, $cache = 0)
	{
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, $cache);
		if (!$res) return false;

		if ($select) {
			$num = $res -> RecordCount();
			if ($num==0) {
				return false;
			}

			for($i=0;$i<$num;$i++) {
				$row [] =$res -> fetchRow();
			}

			return ($row);
		}
		return true;
	}


	function Get_list ($DBHandle, $clause = NULL, $order = NULL, $sens = NULL, $field_order_letter = NULL, $letters = NULL, $limite = NULL, $current_record = NULL, $sql_group= NULL, $cache = 0)
	{
		$sql = 'SELECT '.$this -> fields.' FROM '.trim($this -> table);
		$sql_clause='';
		if ($clause!='') {
			$sql_clause=' WHERE '.$clause;
		}

		$sqlletters = "";
		if (!is_null ($letters) && (ereg("^[A-Za-z]+$", $letters)) && !is_null ($field_order_letter) && ($field_order_letter!='')) {
			$sql_letters= ' (".$field_order_letter." LIKE \''.strtolower($letters).'%\') ';

			if ($sql_clause != "") {
				$sql_clause .= " AND ";
			} else {
				$sql_clause .= " WHERE ";
			}
		}

		$sql_orderby = '';
		if (  !is_null ($order) && ($order!='') && !is_null ($sens) && ($sens!='') ) {
			$sql_orderby = " ORDER BY $order $sens";
		}

		$sql_limit ='';
		if (!is_null ($limite) && (is_numeric($limite)) && !is_null ($current_record) && (is_numeric($current_record)) ) {
			$sql_limit = " LIMIT $current_record,$limite";
		}

		$QUERY = $sql.$sql_clause.$sql_group.$sql_orderby.$sql_limit;

		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, $cache);
		if (!$res) return false;
		
		$num = $res -> RecordCount();
		if ($num==0) {
			return 0;
		}

		for( $i=0 ; $i<$num ; $i++ ) {
			$row [] =$res -> fetchRow();
		}
		
		return ($row);
	}


	function Table_count ($DBHandle, $clause=null, $id_Value = null, $cache = 0)
	{
		$sql = 'SELECT count(*) FROM '.trim($this -> table);

		$sql_clause='';
		if ($clause!='') {
            if ($id_Value == null || $id_Value == '') {
			    $sql_clause=' WHERE '.$clause;
            } else {
                $sql_clause=' WHERE '.$clause." = ".$id_Value;
            }
        }

		$QUERY = $sql.$sql_clause;
		
		if(eregi("[ ]+group[ ]+by[ ]+",$sql_clause)) $QUERY="SELECT count(*) FROM (".$QUERY.") as tmp";
		
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, $cache);
		if (!$res) return false;
		
		$row =$res -> fetchRow();

		return ($row['0']);
	}


	function Add_table ($DBHandle, $value, $func_fields = null, $func_table = null, $id_name = null, $subquery = false) {
		if ($func_fields!="") {
			$this -> fields = $func_fields;
		}

		if ($func_table !="") {
			$this -> table = $func_table;
		}
		if ($subquery) {
			$QUERY = "INSERT INTO ".$this -> table." (".$this -> fields.") (".trim ($value).")";
		} else {
			$QUERY = "INSERT INTO ".$this -> table." (".$this -> fields.") values (".trim ($value).")";
		}
		
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, 0);
		if (!$res) return false;
		
		// Fix that , make PEAR complaint
		if ($id_name != "") {

			if (DB_TYPE == "postgres") {

				$oid = $DBHandle -> Insert_ID();
				if ($oid <= 0 || $oid==''){
					return (true);
				}
				$sql = 'SELECT '.$id_name.' FROM '.$this -> table.' WHERE oid=\''.$oid.'\'';
				$res = $DBHandle -> Execute($sql);
				if (!$res) {
					return (false);
				}
				$row [] =$res -> fetchRow();
				if ($this -> debug_st)
					echo "\n <br> psql_insert_id = ".$row[0][0];
				
				return $row[0][0];

			} else {
				$insertid = $DBHandle -> Insert_ID();
				if ($this -> debug_st)
					echo "\n <br> mysql_insert_id = $insertid";
				echo "\n <br> mysql_insert_id = $insertid";
				return $insertid;
			}
		}

		return (true);
	}


	function Update_table ($DBHandle, $param_update, $clause, $func_table = null)
	{
		
		if ($func_table !="")
			$this -> table = $func_table;
		
		$QUERY = "UPDATE ".$this -> table." SET ".trim ($param_update)." WHERE ".trim ($clause);
		
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, 0);

		return ($res);
	}



	function Delete_table ($DBHandle, $clause, $func_table = null)
	{
		
		if ($func_table !="")
			$this -> table = $func_table;
		
        $countFK = count($this->FK_TABLES);
        for ($i = 0; $i < $countFK; $i++) {
            if ($this -> FK_DELETE == false) {
            	$QUERY = "UPDATE ".$this -> FK_TABLES[$i]." SET ".
							trim ($this -> FK_EDITION_CLAUSE[$i])." = -1 WHERE (".trim ($this -> FK_EDITION_CLAUSE[$i])." = ".$this -> FK_ID_VALUE." )";
            } else {
                $QUERY = "DELETE FROM ".$this -> FK_TABLES[$i].
							" WHERE (".trim ($this -> FK_EDITION_CLAUSE[$i])." = ".$this -> FK_ID_VALUE." )";
            }
			if ($this -> debug_st) echo "<br>$QUERY";
            $res = $DBHandle -> Execute($QUERY);
        }

		$QUERY = "DELETE FROM ".$this -> table." WHERE (".trim ($clause).")";
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, 0);
		
		return ($res);
	}


    function Delete_Selected ($DBHandle, $clause=null, $order=null, $sens=null, $field_order_letter=null, $letters = null, $limite=null, $current_record=NULL, $sql_group= NULL)
	{
		$sql = 'DELETE FROM '.trim($this -> table);

		$sql_clause='';
		if ($clause!='') {
			$sql_clause=' WHERE '.$clause;
		}

		$sqlletters = "";
		if (!is_null ($letters) && (ereg("^[A-Za-z]+$", $letters)) && !is_null ($field_order_letter) && ($field_order_letter!='') ) {
			$sql_letters= ' (".$field_order_letter." LIKE \''.strtolower($letters).'%\') ';

			if ($sql_clause != "") {
				$sql_clause .= " AND ";
			} else {
				$sql_clause .= " WHERE ";
			}
		}

        $QUERY = $sql.$sql_clause;
		
		$res = $this -> ExecuteQuery ($DBHandle, $QUERY, 0);
		
		return ($res);
	}

};

