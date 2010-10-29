<?php
/***************************************************************************
 20Couch 
  
 Copyright (c) 2009-2010 Matt Curry 
 
 @author      Matt Curry <matt@pseudocoder.com>
 @license     MIT
 
 More info at: http://www.20couch.com
****************************************************************************/
?>
<?php
class Tag extends AppModel {

	var $name = 'Tag';
	var $hasAndBelongsToMany = array('Origin');

	function updateItemCounterCache($id=null, $return=false) {
		$tags = parent::find('all', array(
															'fields' => array('Tag.id, Tag.name, count(*) AS cnt'),
															'joins' => array(
																					 array(
																							 'table' => 'origins_tags',
																							 'alias' => 'OriginsTag',
																							 'type' => 'inner',
																							 'foreignKey' => false,
																							 'conditions'=> array('OriginsTag.tag_id = Tag.id')
																					 ),
																					 array(
																							 'table' => 'origins',
																							 'alias' => 'Origin',
																							 'type' => 'inner',
																							 'foreignKey' => false,
																							 'conditions'=> array('Origin.id = OriginsTag.origin_id')
																					 ),
																					 array(
																							 'table' => 'items',
																							 'alias' => 'Item',
																							 'type' => 'inner',
																							 'foreignKey' => false,
																							 'conditions'=> array(
																																'Item.origin_id = Origin.id',
																																'Item.read' => 0
																														)
																					 )
																			 ),
															'group' => array('Tag.id', 'Tag.name')));

		$this->updateAll(array('Tag.item_count' => 0), '1=1');
		foreach($tags as $i => $tag) {
			$tags[$i]['Tag']['item_count'] = $tag[0]['cnt'];
			$this->save($tags[$i]);
		}
		
		return $tags;
	}

	function __findOrSave($tags) {
		$existing = parent::find('list', array('fields' => array('Tag.Id', 'Tag.name'),
																					 'conditions' => array('Tag.name' => $tags)));

		$new = array_diff($tags, $existing);

		foreach($new as $tag) {
			$this->create();
			$this->save(array('name' => $tag));
			$existing[$this->id] = $tag;
		}

		return $existing;
	}

}
?>