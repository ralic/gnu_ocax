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

/**
 * This is the model class for table "budget_desc_state".
 *
 * The followings are the available columns in table 'budget_desc_state':
 * @property integer $id
 * @property string $csv_id
 * @property string $language
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property string $description
 * @property string $text
 * @property string $modified
 */
class BudgetDescState extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BudgetDescState the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'budget_desc_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
/*
		return array(
			array('language, concept', 'required'),
			array('csv_id', 'length', 'max'=>100),
			array('language', 'length', 'max'=>2),
			array('code, label', 'length', 'max'=>32),
			array('concept', 'length', 'max'=>255),
			array('description, text, modified', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, csv_id, language, code, label, concept, description, text, modified', 'safe', 'on'=>'search'),
		);
*/
		return array();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	public function getDescription($csv_id, $lang)
	{
		if($desc = $this->findByAttributes(array('csv_id'=>$csv_id, 'language'=>$lang)))
			return $desc;
		if($desc = $this->findByAttributes(array('csv_id'=>$csv_id)))
			return $desc;
		return Null;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
/*
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'csv_id' => 'Csv',
			'language' => 'Language',
			'code' => 'Code',
			'label' => 'Label',
			'concept' => 'Concept',
			'description' => 'Description',
			'text' => 'Text',
			'modified' => 'Modified',
		);
	}
*/

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
/*
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
*/
}
