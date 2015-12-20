<?php
/**
 * skript pro generovani xml souboru se strukturou db tabulek
 * pro instalaci rozsireni moodle
 *
 * @version 2.0
 * - prevedeni na tridu
 * - odstraneni notice hlaseni
 *
 * @version 1.1
 * - odstraneni chyby s "not null"
 * - doplneni komentaru
 *
 * @version 1.0
 * - zakladni funkcnost
 *
 * @author Pragodata {@link http://pragodata.cz}; Valusek, Mikulica, Vlahovic
 */

/* ************************************************************************** */
/* vic nastavovat neni potreba */
/* ************************************************************************** */
// tabulky k prevedeni
$table_name_array=array(
		'mapletadp',
		
);
$file_path='mod/mapletadp/db';
$plugin_name='mod PDC Certificate';
/* ************************************************************************** */

// spusteni...
require_once('../../config.php');
global $DB, $CFG;
$db_script=new DbScript($DB, $CFG);
$db_script
				->setTablesToExport($table_name_array)
				->setFilePath($file_path)
				->setPluginName($plugin_name)
				->exportXml()
				;

/**
 * skript pro generovani xml souboru se strukturou db tabulek
 * pro instalaci rozsireni moodle
 */
class DbScript{
	// nastavitelne pomoci metod
	private $db_type='mysql';
	private $tables_to_export;
	private $file_path;
	private $xml_version;
	private $plugin_name;

	// nenastavitelne
	private $db;
	private $nbsp_lvl_0='  ';
	private $nbsp_lvl_1='    ';
	private $nbsp_lvl_2='      ';
	private $nbsp_lvl_3='        ';
	private $nbsp_lvl_4='          ';
	private $file_name='install.xml';
	private $xml_comment='XMLDB file';
	private $xml_type_all;
	private $table_structure_array=array();
	private $table=array();
	private $xml;
	private $dir_root;
	private $table_keys;

	// XML type INT
	private $xml_type_int=array('mysql'=>array('bigint','int','mediumint','smallint','tinyint'),'postgre'=>array('bigint','integer','smallint'),'oracle'=>array('number'),'mssql'=>array('bigint','integer','smallint'));
	// XML type NUMBER
	private $xml_type_number=array('mysql'=>array('numeric'),'postgre'=>array('numeric'),'oracle'=>array('number'),'mssql'=>array('decimal'));
	// XML type FLOAT
	private $xml_type_float=array('mysql'=>array('float','double'),'postgre'=>array('real','double precision'),'oracle'=>array('number'),'mssql'=>array('real','float'));
	// XML type CHAR
	private $xml_type_char=array('mysql'=>array('varchar'),'postgre'=>array('varchar'),'oracle'=>array('varchar2'),'mssql'=>array('nvarchar'));
	// XML type TEXT
	private $xml_type_text=array('mysql'=>array('longtext','mediumtext','text'),'postgre'=>array('text'),'oracle'=>array('clob'),'mssql'=>array('ntext'));
	// XML type BINARY
	private $xml_type_binary=array('mysql'=>array('longblob','mediumblob','blob'),'postgre'=>array('bytea'),'oracle'=>array('blob'),'mssql'=>array('image'));
	// XML type DATETIME
	private $xml_type_datetime=array('mysql'=>array('datetime','timestamp'),'postgre'=>array('timestamp'),'oracle'=>array('date'),'mssql'=>array('datetime'));

	/* ************************************************************************ */
	/* magic methods */
	/* ************************************************************************ */

	public function __construct(moodle_database $db, stdClass $cfg){
		$this->db=$db;
		$this->dir_root=$cfg->dirroot;
		$db_type=($cfg->dbtype==='pgsql' ? 'postgre' : 'mysql');
		$this->setXmlTypeAll()->setXmlVersion(date('Ymd').'00')->setDbType($db_type);

	}

	/* ************************************************************************ */
	/* public methods */
	/* ************************************************************************ */

	/**
	 * provede samotny export
	 */
	public function exportXml(){
		$this
						->setTableStructureArray()
						->setTable()
						->setTablePrevNextElem()
						->setXml()
						->saveXmlFile()
						->printXmlFile()
						;
		exit();
	}

	/**
	 * @param string $plugin_name
	 * @return \DbScript
	 */
	public function setPluginName($plugin_name){
		$plugin_name=(string)$plugin_name;
		$this->plugin_name=$plugin_name;
		$this->xml_comment.=' for the '.$plugin_name;
		return $this;
	}

	public function setXmlVersion($xml_version){
		$xml_version=(string)$xml_version;
		$this->xml_version=$xml_version;
		return $this;
	}

	/**
	 * path to file from dirroot
	 * @param string $file_path
	 * @return \DbScript
	 */
	public function setFilePath($file_path){
		$file_path=(string)$file_path;
		$this->file_path=trim($file_path,'/');
		return $this;
	}

	/**
	 * @param array $tables_to_export
	 * @return \DbScript
	 */
	public function setTablesToExport(array $tables_to_export){
		$this->tables_to_export=$tables_to_export;
		return $this;
	}

	/* ************************************************************************ */
	/* private methods */
	/* ************************************************************************ */

	/**
	 * @param string $db_type
	 * @return \DbScript
	 */
	private function setDbType($db_type){
		$db_type=(string)$db_type;
		if(isset($this->xml_type_int[$db_type])){
			$this->db_type=$db_type;
			$this->setXmlTypeAll();
		}
		return $this;
	}

	private function printXmlFile(){
		header('Content-Type: application/xml; charset=utf-8');
		echo $this->xml;
	}

	/**
	 * @return \DbScript
	 */
	private function setXmlTypeAll(){
		$xml_type_all=array(
				'int'=>$this->xml_type_int[$this->db_type],
				'number'=>$this->xml_type_number[$this->db_type],
				'float'=>$this->xml_type_float[$this->db_type],
				'char'=>$this->xml_type_char[$this->db_type],
				'text'=>$this->xml_type_text[$this->db_type],
				'binary'=>$this->xml_type_binary[$this->db_type],
				'datetime'=>$this->xml_type_datetime[$this->db_type]
		);

		$this->xml_type_all=$xml_type_all;
		return $this;
	}

	/**
	 * @return \DbScript
	 * @todo Vlahovic: upravit i pro postre
	 */
	private function setTableStructureArray(){
		foreach($this->tables_to_export as $name){
			$table_structure_array[$name]=$this->db->get_records_sql('SHOW FULL COLUMNS FROM {'.$name.'}');
			// verze pro pgsql
//			$query="SELECT column_name FROM information_schema.columns WHERE table_name ='{".$this->module_table."}'";
		}
		$this->table_structure_array=$table_structure_array;
		return $this;
	}

	/**
	 * @return \DbScript
	 */
	private function setTable(){
		foreach($this->table_structure_array as $tsa=> $val){
			$rows=array();
			foreach($val as $col){
				$col_type=$this->printColType($col->type);

				// Promenne pro ENUM
				$enum_values=array();
				$enum=false;

				// Poku je typ ENUM -> typ = char, delka = 255
				if($col_type['type'] == 'enum'){
					$col_type['type']='char';
					$enum_values=explode(',', $col_type['length']);
					$col_type['length']='255';
					$enum=true;
				}

				$row_prop=new stdClass();
				$row_prop->name=$col->field;
				$row_prop->type=$col_type['type'];
				$row_prop->length=$col_type['length'];
				$row_prop->notnull=$this->printBoolValue($col->null, 'notnull');

				// Pokud je sloupec nulovy a ma nejakou vychozi hodnotu, ulozime ji, jinak bude null
				$row_prop->default=null;
				if($row_prop->notnull == 'false' && $col->default != null){
					$row_prop->default=$col->default;
				}

				// Pokud se jedna o ENUM
				if($enum === true){
					$row_prop->enum=true;
					$row_prop->enum_vals=$enum_values;
				}

				$row_prop->sequence=$this->printBoolValue($col->extra, 'sequence');
				$row_prop->prev=null;
				$row_prop->next=null;
				$row_prop->comment=$col->comment;
				$rows[]=$row_prop;
			}
			$table[$tsa]=$rows;
		}
		$this->table=$table;
		return $this;
	}

	/**
	 * @return \DbScript
	 */
	private function setTablePrevNextElem(){
		foreach($this->table as $tab=> $val){
			for($i=0; $i <= count($val); $i++){
				if($i == 0){
					$this->table[$tab][$i]->prev=null;
					if(isset($this->table[$tab][$i + 1])){
						$this->table[$tab][$i]->next=$this->table[$tab][$i + 1]->name;
					}
				}

				if($i == count($val) - 1){
					$this->table[$tab][$i]->next=null;
					if(isset($this->table[$tab][$i - 1])){
						$this->table[$tab][$i]->prev=$this->table[$tab][$i - 1]->name;
					}
				}

				if($i > 0 && $i < count($val) - 1){
					$this->table[$tab][$i]->prev=$this->table[$tab][$i - 1]->name;
					$this->table[$tab][$i]->next=$this->table[$tab][$i + 1]->name;
				}
			}
		}

		return $this;
	}


	private function setXml(){
		$xml='';

		$xml .= '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
		$xml .= '<XMLDB PATH="'.$this->file_path.'" VERSION="'.$this->xml_version.'" COMMENT="'.$this->xml_comment.'"'.PHP_EOL;
		$xml .= $this->nbsp_lvl_1.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'.PHP_EOL;
		$xml .= $this->nbsp_lvl_1.'xsi:noNamespaceSchemaLocation="../../../lib/xmldb/xmldb.xsd"'.PHP_EOL;
		$xml .= '>'.PHP_EOL;

		$xml .= $this->nbsp_lvl_0.'<TABLES>'.PHP_EOL;
		$xml.=$this->printXmlTables();
		$xml .= $this->nbsp_lvl_0.'</TABLES>'.PHP_EOL;

		$xml .= '</XMLDB>';

		$this->xml=$xml;
		return $this;
	}

	private function printXmlTables(){
		$return='';
		foreach($this->table as $table_name=> $fields){
			$return.=$this->printXmlTable($table_name, $fields);
		}
		return $return;
	}

	private function printXmlTable($table_name, $fields){
		$return='';
		$this->table_keys=new stdClass();
		$return .= $this->nbsp_lvl_1.'<TABLE NAME="'.$table_name.'" COMMENT="">'.PHP_EOL;
		$return .= $this->nbsp_lvl_2.'<FIELDS>'.PHP_EOL;
		$return.=$this->printXmlFields($fields);
		$return .= $this->nbsp_lvl_2.'</FIELDS>'.PHP_EOL;

		if(!empty($this->table_keys)){
			$return .= $this->nbsp_lvl_2.'<KEYS>'.PHP_EOL;
			$return .= $this->nbsp_lvl_3.'<KEY NAME="'.$this->table_keys->name.'" TYPE="'.$this->table_keys->type.'" FIELDS="'.$this->table_keys->fields.'" />'.PHP_EOL;
			$return .= $this->nbsp_lvl_2.'</KEYS>'.PHP_EOL;
		}

		$return .= $this->nbsp_lvl_1.'</TABLE>'.PHP_EOL;
		return $return;
	}

	/**
	 * @param array $fields
	 * @return string
	 */
	private function printXmlFields(array $fields){
		$return='';
		foreach($fields as $field){
			$return.=$this->printXmlField($field);
		}
		return $return;
	}

	/**
	 * @param stdClass $field
	 * @return string
	 */
	private function printXmlField(stdClass $field){
		$return='';
		$close_tag=' />';

		if($field->sequence == 'true'){
			$this->table_keys->name='primary';
			$this->table_keys->type='primary';
			$this->table_keys->fields=$field->name;
		}

		$prev=(isset($field->prev) && $field->prev !== null ? ' PREVIOUS="'.$field->prev.'"' : '');
		$next=(isset($field->next) && $field->next !== null ? ' NEXT="'.$field->next.'"' : '');
		$def=(isset($field->default) && $field->default !== null ? ' DEFAULT="'.$field->default.'"' : '');
		$enum=(isset($field->enum) && $field->enum !== null ? ' ENUM="true"' : '');

		if(isset($field->enum)){
			$close_tag='>';
		}

		$return .= $this->nbsp_lvl_3.'<FIELD NAME="'.$field->name.'" TYPE="'.$field->type.'" LENGTH="'.$field->length.'" NOTNULL="'.$field->notnull.'"'.$def.' SEQUENCE="'.$field->sequence.'"'.$enum.' COMMENT="'.$field->comment.'"'.$prev.$next.$close_tag.PHP_EOL;

		if(isset($field->enum)){
			$return.=$this->printXmlEnums($field->enum_vals);
			$return .= $this->nbsp_lvl_3.'</FIELD>'.PHP_EOL;
		}
		return $return;
	}

	/**
	 * @param array $enum_vals
	 * @return string
	 */
	private function printXmlEnums(array $enum_vals){
		$return='';
		if($enum_vals){
			foreach($enum_vals as $enum_val){
				$return .= $this->nbsp_lvl_4.'<ENUMVALUE>'.str_replace('\'', '', $enum_val).'</ENUMVALUE>'.PHP_EOL;
			}
		}
		return $return;
	}

	/**
	 * @return \DbScript
	 */
	private function saveXmlFile(){
		$full_file_path=$this->dir_root.'/'.$this->file_path.'/'.$this->file_name;
		if(!$this->file_path){
			throw new Exception('File path must be set.');
		}
		elseif(!is_writable($full_file_path)){
			throw new Exception('Cant write to file. ('.$full_file_path.')');
		}
		else{
			file_put_contents($full_file_path, $this->xml);
		}
		return $this;
	}

	/**
	 * Vrati datovy typ sloupce tabulky
	 * @param object $col
	 * @return array
	 */
	private function printColType($col){
		$return=array();

		$ret=explode(' ', $col);
		$typ=explode('(', $ret[0]);

		$return['type']=$typ[0];

		if(isset($typ[1])){
			$len=explode(')', $typ[1]);
			$return['length']=$len[0];
		}
		else{
			$return['length']='';
		}

		foreach($this->xml_type_all as $all=> $val){
			foreach($val as $xml_type){
				if($return['type'] == $xml_type){
					$return['type']=$all;
				}
			}
		}

		return $return;
	}

	/**
	 * Prevede hodnoty slopce na true/false
	 * @param string $col
	 * @param string $type
	 * @return string
	 */
	private function printBoolValue($col, $type){
		$return='false';

		switch($type){
			// Typ = sequence
			case 'sequence':
				if($col == 'auto_increment'){
					$return='true';
				}
				break;

			// Typ = notnull
			case 'notnull':
				if($col == 'NO'){
					$return='true';
				}
				break;
		}

		return $return;
	}
}