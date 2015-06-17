<?php

class Page {

	public static $regex = [];
	public static $resources_templ_arr = [];
	public $global_vars = [];
	public $templates = [];

	public function __construct($url) {
		$this->getMacros("$url.htm");
	}

	protected $pages = ['index.php'];
	protected function getMacros($url) {
		if (file_exists($url)) {
			$this->pages[] = $url;
			$file_contents = file_get_contents($url);
		} else {
			echo "[".end($this->pages)."] Cannot find $url"; exit;
		}

		$this->getVars($file_contents);
		$this->getBlocks($file_contents);

		if ( isset($this->global_vars['template']) ) {
			$template =  $this->global_vars['template'];
			if ( in_array($template,$this->templates) ) {
				echo "[$url] Multiple requests for template '$template'";
				exit;
			} else {
				$this->templates[] = $template;
			}
			unset($this->global_vars['template']);
			$this->getMacros($template);
		}
	}

	protected function getVars($file_contents) {
		if ( $file_vars = match(self::$regex['vars'],$file_contents) ) {
			$globals =& $this->global_vars;
			$vars = get_json_arr($file_vars);
			foreach ( $vars as $key => $val ) {
				if ( isset($globals[$key]) AND !is_array($globals[$key]) ) {
					echo "[$url] VARS[\"$key\"] is already set";
					exit;
				}
				$globals[$key] =
					(isset($globals[$key]) AND is_array($globals[$key])) ?
						array_merge($globals[$key],$val) : $val;
			}
		}
	}

	protected function getBlocks($file_contents) {
		$blocks = match_all(self::$regex['blocks'],$file_contents);
		foreach ( $blocks as $key => $val ) {
			if ( isset($this->global_vars[$val[0]]) ) {
				echo "[$url] @$key is already set";
				exit;
			}
			$this->global_vars[$val[0]] = $val[1];
		}
	}

	public function render() {
		$globals =& $this->global_vars;
		foreach ( self::$resources_templ_arr as $key => $val ) {
			if ( isset($globals[$key]) ) {
				$output = '';
				if ( is_array($globals[$key]) ) {
					foreach ( $globals[$key] as $ref ) {
						$output .= str_replace('~', $ref, $val) . "\n";
					}
				} else {
					$output = str_replace('~', $globals[$key], $val);
				}
				$globals[$key] = $output;
			}
		}

		foreach ( array_keys($this->global_vars) as $macro ) {
			$this->global_vars = str_replace("<%= $macro %>", $this->global_vars[$macro], $this->global_vars);
		}
		echo preg_replace(self::$regex['macro'], "", $this->global_vars['@base']);
	}
}

function match($pattern,$subject) {
	preg_match($pattern,$subject,$match);
	return $match ? $match[1] : null;
}
function match_all($pattern,$subject) {
	preg_match_all($pattern,$subject,$matches);
	array_shift($matches);
	$tmp_arr = [];
	foreach ( $matches[0] as $arr_val => $arr_key ) {
		$tmp_arr2 = [];
		foreach ( $matches as $key => $arr )
			$tmp_arr2[] = $arr[$arr_val];
		$tmp_arr[] = (count($tmp_arr2)>1) ? $tmp_arr2 : $tmp_arr2[0];
	}
	return $tmp_arr;
}

function get_json_arr($input) {
	$json = json_decode($input,1);
	if ( json_last_error() ) { echo "JSON error \"".json_last_error_msg()."\" in:"; var_dump($input); exit; }
	return $json;
}
