<?php
	/**
	 * Code source de la classe AnalysesqlTest.
	 *
	 * PHP 5.3
	 *
	 * @package AnalyseSql
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Analysesql', 'AnalyseSql.Utility' );
	
	/**
	 * La classe AnalysesqlTest ...
	 *
	 * @package AnalyseSql
	 * @subpackage Test.Case.Utility
	 */
	class AnalysesqlTest extends CakeTestCase
	{
		/**
		 * Test de la méthode Analysesql::Analyse()
		 */
		public function testAnalyse() {
			$sql = 'SELECT EXIST("SELECT id FROM tables2 INNER JOIN foo ON ((foo.id = tables2.foo_id)) LIMIT 1) AS "Table1__existtest", "Table1"."name" AS "Table1__name", "Table2"."foo", COUNT(*) FROM "tables1" AS "Table1" INNER JOIN "tables2" AS "Table2" ON ("Table1"."table2_id" = "Table2"."id") LEFT OUTER JOIN "tables3" ON ((SELECT id FROM tables4 WHERE tables4.name = "tables3"."name" LIMIT 1) = "Table1"."name") WHERE "Table2"."name" LIKE "foo%" AND "Table2"."name" LIKE "%bar" AND (("Table1"."name" = \'foobar\') OR ("Table2"."name" = \'foobar\')) ORDER BY "Table1"."id" DESC LIMIT 5';
			
			$result = Analysesql::analyse($sql);
			$expected = array (
  'text' => '
#################################################################################
Requète sans parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'noBraketsSqlReport'.$result['random'].'\').toggle();" checked="true"><div id="noBraketsSqlReport'.$result['random'].'" style="display:block;">SELECT EXIST<span title="&quot;SELECT id FROM tables2 INNER JOIN foo ON [7] LIMIT 1">[11]</span> AS "Table1__existtest",
 "Table1"."name" AS "Table1__name",
 "Table2"."foo",
 COUNT<span title="*">[1]</span> 
FROM "tables1" AS "Table1" 
INNER JOIN "tables2" AS "Table2" ON <span title="&quot;Table1&quot;.&quot;table2_id&quot; = &quot;Table2&quot;.&quot;id&quot;">[2]</span> 
LEFT OUTER JOIN "tables3" ON <span title="[3] = &quot;Table1&quot;.&quot;name&quot;">[8]</span> 
WHERE "Table2"."name" LIKE "foo%" 
AND "Table2"."name" LIKE "%bar" 
AND <span title="[4] OR [5]">[9]</span> 
ORDER BY "Table1"."id" DESC 
LIMIT 5</div>
#################################################################################
Contenu des parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'innerBraketsReport'.$result['random'].'\').toggle();"><div id="innerBraketsReport'.$result['random'].'" style="display:none;">array (
  1 => \'*\',
  2 => \'"Table1"."table2_id" = "Table2"."id"\',
  3 => \'SELECT id FROM tables4 WHERE tables4.name = "tables3"."name" LIMIT 1\',
  4 => \'"Table1"."name" = \\\'foobar\\\'\',
  5 => \'"Table2"."name" = \\\'foobar\\\'\',
  7 => \'foo.id = tables2.foo_id\',
  8 => \'<span title="SELECT id FROM tables4 WHERE tables4.name = &quot;tables3&quot;.&quot;name&quot; LIMIT 1">[3]</span> = "Table1"."name"\',
  9 => \'<span title="&quot;Table1&quot;.&quot;name&quot; = &#039;foobar&#039;">[4]</span> OR <span title="&quot;Table2&quot;.&quot;name&quot; = &#039;foobar&#039;">[5]</span>\',
  11 => \'"SELECT id FROM tables2 INNER JOIN foo ON <span title="foo.id = tables2.foo_id">[7]</span> LIMIT 1\',
)</div>
#################################################################################
Fields :
#################################################################################
<input type="checkbox" onchange="$(\'sqlFieldsReport'.$result['random'].'\').toggle();"><div id="sqlFieldsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'Table1.existtest\',
  1 => \'Table1.name\',
  2 => \'Table2.foo\',
  3 => \'COUNT<span title="*">[1]</span>\',
)</div>
#################################################################################
Jointures :
#################################################################################
<input type="checkbox" onchange="$(\'sqlJoinsReport'.$result['random'].'\').toggle();"><div id="sqlJoinsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => 
  array (
    \'table\' => \'"tables1"\',
    \'alias\' => \'Table1\',
    \'type\' => \'FROM\',
    \'conditions\' => \'\',
  ),
  1 => 
  array (
    \'table\' => \'"tables2"\',
    \'alias\' => \'Table2\',
    \'type\' => \'INNER\',
    \'conditions\' => \'"Table1"."table2_id" = "Table2"."id"\',
  ),
  2 => 
  array (
    \'table\' => \'"tables3"\',
    \'alias\' => \'\',
    \'type\' => \'LEFT\',
    \'conditions\' => \'<span title="SELECT id FROM tables4 WHERE tables4.name = &quot;tables3&quot;.&quot;name&quot; LIMIT 1">[3]</span> = "Table1"."name"\',
  ),
)</div>
#################################################################################
Conditions :
#################################################################################
<input type="checkbox" onchange="$(\'sqlConditionsReport'.$result['random'].'\').toggle();"><div id="sqlConditionsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'"Table2"."name" LIKE "foo%"\',
  1 => \'"Table2"."name" LIKE "%bar"\',
  2 => \'(<span title="&quot;Table1&quot;.&quot;name&quot; = &#039;foobar&#039;">[4]</span> OR <span title="&quot;Table2&quot;.&quot;name&quot; = &#039;foobar&#039;">[5]</span>)\',
)</div>',
  'innerBrackets' => 
  array (
    1 => '*',
    2 => '"Table1"."table2_id" = "Table2"."id"',
    3 => 'SELECT id FROM tables4 WHERE tables4.name = "tables3"."name" LIMIT 1',
    4 => '"Table1"."name" = \'foobar\'',
    5 => '"Table2"."name" = \'foobar\'',
    7 => 'foo.id = tables2.foo_id',
    8 => '<span title="SELECT id FROM tables4 WHERE tables4.name = &quot;tables3&quot;.&quot;name&quot; LIMIT 1">[3]</span> = "Table1"."name"',
    9 => '<span title="&quot;Table1&quot;.&quot;name&quot; = &#039;foobar&#039;">[4]</span> OR <span title="&quot;Table2&quot;.&quot;name&quot; = &#039;foobar&#039;">[5]</span>',
    11 => '"SELECT id FROM tables2 INNER JOIN foo ON <span title="foo.id = tables2.foo_id">[7]</span> LIMIT 1',
  ),
  'random' => $result['random']
);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$sql = 'SELECT myfunction(\'foo\')';
			$result = Analysesql::analyse($sql);
			$expected = array (
  'text' => '
#################################################################################
Requète sans parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'noBraketsSqlReport'.$result['random'].'\').toggle();" checked="true"><div id="noBraketsSqlReport'.$result['random'].'" style="display:block;">SELECT myfunction<span title="&#039;foo&#039;">[0]</span></div>
#################################################################################
Contenu des parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'innerBraketsReport'.$result['random'].'\').toggle();"><div id="innerBraketsReport'.$result['random'].'" style="display:none;">array (
  0 => \'\\\'foo\\\'\',
)</div>
#################################################################################
Fields :
#################################################################################
<input type="checkbox" onchange="$(\'sqlFieldsReport'.$result['random'].'\').toggle();"><div id="sqlFieldsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'myfunction<span title="&#039;foo&#039;">[0]</span>\',
)</div>
#################################################################################
Jointures :
#################################################################################
<input type="checkbox" onchange="$(\'sqlJoinsReport'.$result['random'].'\').toggle();"><div id="sqlJoinsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
)</div>
#################################################################################
Conditions :
#################################################################################
<input type="checkbox" onchange="$(\'sqlConditionsReport'.$result['random'].'\').toggle();"><div id="sqlConditionsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
)</div>',
  'innerBrackets' => 
  array (
    0 => '\'foo\'',
  ),
  'random' => $result['random'],
);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$sql = 'UPDATE "public"."foos" AS "Foo" SET "Foo"."name" = MYFUNCTION(\'Foo\', \'Bar\') WHERE "Foo"."name" LIKE \'Foobar%\'';
			$result = Analysesql::analyse($sql);
			$expected = array (
  'text' => '
#################################################################################
Requète sans parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'noBraketsSqlReport'.$result['random'].'\').toggle();" checked="true"><div id="noBraketsSqlReport'.$result['random'].'" style="display:block;">UPDATE "public"."foos" AS "Foo" SET "Foo"."name" = MYFUNCTION<span title="&#039;Foo&#039;, &#039;Bar&#039;">[0]</span> 
WHERE "Foo"."name" LIKE \'Foobar%\'</div>
#################################################################################
Contenu des parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'innerBraketsReport'.$result['random'].'\').toggle();"><div id="innerBraketsReport'.$result['random'].'" style="display:none;">array (
  0 => \'\\\'Foo\\\', \\\'Bar\\\'\',
)</div>
#################################################################################
Fields :
#################################################################################
<input type="checkbox" onchange="$(\'sqlFieldsReport'.$result['random'].'\').toggle();"><div id="sqlFieldsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'Foo.name\',
)</div>
#################################################################################
Jointures :
#################################################################################
<input type="checkbox" onchange="$(\'sqlJoinsReport'.$result['random'].'\').toggle();"><div id="sqlJoinsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => 
  array (
    \'table\' => \'"foos"\',
    \'alias\' => \'Foo\',
    \'type\' => \'UPDATE\',
    \'conditions\' => \'\',
  ),
)</div>
#################################################################################
Conditions :
#################################################################################
<input type="checkbox" onchange="$(\'sqlConditionsReport'.$result['random'].'\').toggle();"><div id="sqlConditionsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'"Foo"."name" LIKE \\\'Foobar%\\\'\',
)</div>',
  'innerBrackets' => 
  array (
    0 => '\'Foo\', \'Bar\'',
  ),
  'random' => $result['random'],
);
			
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$sql = 'UPDATE "public"."connections" SET "modified" = \'2015-07-17 10:40:23\' WHERE "public"."connections"."id" = 244397';
			$result = Analysesql::analyse($sql);
			$expected = array (
  'text' => '
#################################################################################
Requète sans parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'noBraketsSqlReport'.$result['random'].'\').toggle();" checked="true"><div id="noBraketsSqlReport'.$result['random'].'" style="display:block;">UPDATE "public"."connections" SET "modified" = \'2015-07-17 10:40:23\' 
WHERE "public"."connections"."id" = 244397</div>
#################################################################################
Contenu des parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'innerBraketsReport'.$result['random'].'\').toggle();"><div id="innerBraketsReport'.$result['random'].'" style="display:none;">array (
)</div>
#################################################################################
Fields :
#################################################################################
<input type="checkbox" onchange="$(\'sqlFieldsReport'.$result['random'].'\').toggle();"><div id="sqlFieldsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'modified\',
)</div>
#################################################################################
Jointures :
#################################################################################
<input type="checkbox" onchange="$(\'sqlJoinsReport'.$result['random'].'\').toggle();"><div id="sqlJoinsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => 
  array (
    \'table\' => \'"connections"\',
    \'alias\' => \'connections\',
    \'type\' => \'UPDATE\',
    \'conditions\' => \'\',
  ),
)</div>
#################################################################################
Conditions :
#################################################################################
<input type="checkbox" onchange="$(\'sqlConditionsReport'.$result['random'].'\').toggle();"><div id="sqlConditionsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'"public"."connections"."id" = 244397\',
)</div>',
  'innerBrackets' => 
  array (
  ),
  'random' => $result['random'],
);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$sql = 'DELETE FROM "foos" AS "Foo" WHERE "Foo"."bar" IS NULL';
			$result = Analysesql::analyse($sql);
			$expected = array (
  'text' => '
#################################################################################
Requète sans parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'noBraketsSqlReport'.$result['random'].'\').toggle();" checked="true"><div id="noBraketsSqlReport'.$result['random'].'" style="display:block;">DELETE 
FROM "foos" AS "Foo" 
WHERE "Foo"."bar" IS NULL</div>
#################################################################################
Contenu des parenthèses :
#################################################################################
<input type="checkbox" onchange="$(\'innerBraketsReport'.$result['random'].'\').toggle();"><div id="innerBraketsReport'.$result['random'].'" style="display:none;">array (
)</div>
#################################################################################
Fields :
#################################################################################
<input type="checkbox" onchange="$(\'sqlFieldsReport'.$result['random'].'\').toggle();"><div id="sqlFieldsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
)</div>
#################################################################################
Jointures :
#################################################################################
<input type="checkbox" onchange="$(\'sqlJoinsReport'.$result['random'].'\').toggle();"><div id="sqlJoinsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => 
  array (
    \'table\' => \'"foos"\',
    \'alias\' => \'Foo\',
    \'type\' => \'FROM\',
    \'conditions\' => \'\',
  ),
)</div>
#################################################################################
Conditions :
#################################################################################
<input type="checkbox" onchange="$(\'sqlConditionsReport'.$result['random'].'\').toggle();"><div id="sqlConditionsReport'.$result['random'].'" style="display:none;" class="restoreBrackets">array (
  0 => \'"Foo"."bar" IS NULL\',
)</div>',
  'innerBrackets' => 
  array (
  ),
  'random' => $result['random'],
);
			
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>