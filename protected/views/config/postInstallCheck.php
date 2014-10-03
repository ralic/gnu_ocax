<?php
/**
 * OCAX -- Citizen driven Observatory software
 * Copyright (C) 2014 OCAX Contributors. See AUTHORS.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */



/*
 * Check basic installation sanity
 */ 

?>

<?php
$config = Config::model();

/*
$schema = new Schema;
if(!$schema->isSchemaUptodate($config->getOCAXVersion()))
	$schema->migrate();
*/
?>

<p>
<?php
$cnt =1;
$errors = 0;

// Check directory permisions
$err_msg = '';
if(!is_writable(Yii::app()->basePath.'/runtime/tmp')){
	$err_msg = $err_msg.$cnt.'. '.__('Error: protected/runtime/tmp ').' <i class="icon-attention"></i><br />';
	$cnt +=1;
}
if(!is_writable(Yii::app()->basePath.'/runtime/html')){
	$err_msg = $err_msg.$cnt.'. '.__('Error: protected/runtime/html ').' <i class="icon-attention"></i><br />';
	$cnt +=1;
}
if($err_msg){
	echo $err_msg;
	$errors +=1;
}else{
	echo $cnt.'. '.__('Directory permissions seem Ok').' <i class="icon-ok-circled"></i><br />';
	$cnt +=1;
}


echo $cnt.'. ';
if(isExecAvailable() === false){
	$dumpMethod = $config->findByPk('databaseDumpMethod');
	$dumpMethod->value = 'alternative';
	$dumpMethod->save();
	echo __('Info: Native mysqldump not available. Using PHP alternative for backups');
}else{
	// need to add test for mysqldump
	echo __('Using native mysqldump for backups');
}
echo '. <i class="icon-ok-circled"></i><br />';
$cnt +=1;


echo $cnt.'. ';
if(class_exists('ZipArchive'))
	echo __('ZipArchive is installed').'. <i class="icon-ok-circled"></i>';
else{
	echo __('Error: ZipArchive is not installed').' <i class="icon-attention"></i>';
	$errors +=1;
}
echo '<br />';
$cnt +=1;


$requirementsCheck = $config->findByPk('siteConfigStatusPostInstallChecked');
$requirementsCheck->save();
	
?>
</p>
