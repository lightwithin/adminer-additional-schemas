<?php
/**
 * Adds the additional schemas to include in search_path for query for PostgreSQL
 * @link https://www.adminer.org/plugins/#use
 * @author Ted Martin, https://github.com/lightwitin
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class AdminerAdditionalSchemas {
	private $additionalSchema;

	/**
	* @param string[] $additionalSchema Include additional schemas to include in search_path for query
	*/
	function __construct(array $additionalSchema = ['public']) {
		$this->additionalSchema = $additionalSchema;
	}
	function database() {
		if (Adminer\connection()) {
			
			if (!in_array(strtolower(Adminer\connection()->extension), ['pgsql', 'pdo_pgsql'])) {
				//if not postgres, return the default DB class
				return Adminer\DB;
			}
			$currentSchema = Adminer\get_schema();
			if (in_array($currentSchema, $this->additionalSchema) && count($this->additionalSchema) > 1) {
				//if the current schema is in the additional schemas, we set the search_path to include it first then the others
				Adminer\queries("SET search_path = " .$currentSchema .",". implode(", ",array_diff($this->additionalSchema, [$currentSchema])).";");
			}
		}
		return Adminer\DB;
	}
}
